<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Helper;
use Image, Auth, Redirect, Validator, View;
use Illuminate\Support\Facades\Hash;
use AdminHelper;
use App\User;
use App\UserDetails;
use App\Bank;
use App\Block;
use App\District;
use App\Software;
use App\Societie;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExportUserBank;

class BankController extends Controller
{
  /*****************************************************/
  # BankController
  # Function name : List
  # Author        :
  # Created Date  : 23-09-2020
  # Purpose       : Showing List of Bank
  # Params        : Request $request
  /*****************************************************/
  public function list(Request $request)
  {
    $data['page_title'] = 'Bank List';
    $data['panel_title'] = 'Bank List';

    try {
      $pageNo = $request->input('page');
      Session::put('pageNo', $pageNo);

      $data['order_by']   = 'created_at';
      $data['order']      = 'desc';

      $query = User::whereNull('deleted_at')->whereUserType('1');

      $data['searchText'] = $key = $request->searchText;

      if ($key) {
        // if the search key is provided, proceed to build query for search
        $query->where(function ($q) use ($key) {
          $q->where('full_name', 'LIKE', '%' . $key . '%');
          $q->orWhere('email', 'LIKE', '%' . $key . '%');
          $q->orWhere('phone_no', 'LIKE', '%' . $key . '%');
          $q->orWhereHas('userProfile', function ($qe) use ($key) {
            $qe->where('ifsc_code', 'like', '%' . $key . '%');
          });
        });
      }
      $exists = $query->count();
      if ($exists > 0) {
        $list = $query->orderBy($data['order_by'], $data['order'])->paginate(AdminHelper::ADMIN_LIST_LIMIT);
        $data['list'] = $list;
      } else {
        $data['list'] = array();
      }

      return view('admin.bank_user.list')->with(['data' => $data]);
    } catch (Exception $e) {
      return redirect()->route('admin.bank.list')->with('error', $e->getMessage());
    }
  }

  /*****************************************************/
  # BankController
  # Function name : Add
  # Author        :
  # Created Date  : 23-09-2020
  # Purpose       : Add new Bnk user
  # Params        : Request $request
  /*****************************************************/
  public function add(Request $request)
  {
    $data['page_title']     = 'Add Bank';
    $data['panel_title']    = 'Add Bank';

    try {
      if ($request->isMethod('POST')) {
        $validationCondition = array(
          'full_name'     => 'required|min:2|max:255|unique:' . (new User)->getTable() . ',full_name',
          'email'         => 'required|regex:/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/|unique:' . (new User)->getTable() . ',email',
          'phone_no'      => 'required|regex:/^[0-9]\d*(\.\d+)?$/',

          'ifsc_code'     => 'required|min:4|max:255|unique:' . (new UserDetails)->getTable() . ',ifsc_code',
          'address'       => 'required|min:2|max:255',
        );
        $validationMessages = array(
          'full_name.required'    => 'Please enter name',
          'full_name.unique'      => 'The name is already taken',
          'full_name.min'         => 'Name should be at least 2 characters',
          'full_name.max'         => 'Name should not be more than 255 characters',
          'email.required'        => 'Sorry!! Email is required',
          'email.regex'           => 'Sorry!! Please send a valid email',
          'phone_no.required'     => 'Sorry!! Phone number is required',
          'phone_no.regex'        => 'Sorry!! Only number required',
          'ifsc_code.required'    => 'Please enter IFSC Code',
          'ifsc_code.min'         => 'IFSC Code should be should be at least 2 characters',
          'ifsc_code.max'         => 'IFSC Code should not be more than 255 characters',
          'address.required'      => 'Please enter address',
          'address.min'           => 'Address should be at least 2 characters',
          'address.max'           => 'Address should not be more than 255 characters',
        );

        $Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
        if ($Validator->fails()) {
          return redirect()->route('admin.bank.add')->withErrors($Validator)->withInput();
        } else {
          $generatedPassword = $this->rand_string(8);
          //$generatedPassword       = 
          $newBank = new User;
          $newBank->user_type      = '1';
          $newBank->full_name      = isset($request->full_name) ? $request->full_name : NULL;
          $newBank->email          = isset($request->email) ? $request->email : NULL;
          $newBank->phone_no       = isset($request->phone_no) ? $request->phone_no : NULL;
          $newBank->password       = $generatedPassword;
          $newBank->status         = '1';
          $saveBankUser            = $newBank->save();

          if ($saveBankUser) {

            $newBankDetails = new UserDetails;

            $image = $request->file('profile_image');
            if ($image != '') {
              $originalFileNameCat = $image->getClientOriginalName();
              $extension = pathinfo($originalFileNameCat, PATHINFO_EXTENSION);
              $filename = 'member_' . strtotime(date('Y-m-d H:i:s')) . '.' . $extension;

              $image_resize = Image::make($image->getRealPath());
              $image_resize->save(public_path('uploads/member/' . $filename));

              $newBankDetails->profile_image = $filename;
            }


            $newBankDetails->user_id        = $newBank->id;
            $newBankDetails->address        = isset($request->address) ? $request->address : NULL;
            $newBankDetails->ifsc_code      = isset($request->ifsc_code) ? $request->ifsc_code : NULL;
            $saveBankDetails                = $newBankDetails->save();


            // Send Mail to Bank User with Username, Password
            \Mail::send(
              'email_templates.bank_credential',
              [
                'user' => $newBank,
                'app_config' => [
                  'Password'      => $generatedPassword,
                  'appLink'       => Helper::getBaseUrl(),
                  'controllerName' => 'user',
                  'subject'       => 'Bank User Login Credential',
                ],
              ],
              function ($m) use ($newBank) {
                $m->to($newBank->email, $newBank->full_name)->subject('Bank User Credential');
              }
            );


            $request->session()->flash('alert-success', 'Bank has been added successfully');
            return redirect()->route('admin.bank.list');
          } else {
            $request->session()->flash('alert-danger', 'An error occurred while adding the district');
            return redirect()->back();
          }
        }
      }
      // dd($data);
      return view('admin.bank_user.add')->with(['bankUserDetails' => $data]);
    } catch (Exception $e) {
      return redirect()->route('admin.bank.list')->with('error', $e->getMessage());
    }
  }

  /*****************************************************/
  # BankController
  # Function name : Edit
  # Author        :
  # Created Date  : 23-09-2020
  # Purpose       : Edit Bank user
  # Params        : Request $request
  /*****************************************************/
  public function edit(Request $request, $id = null)
  {
    $data['page_title']  = 'Edit Bank';
    $data['panel_title'] = 'Edit Bank';

    try {
      $pageNo = Session::get('pageNo') ? Session::get('pageNo') : '';
      $data['pageNo'] = $pageNo;

      $bankDetails = User::find($id);
      $data['id'] = $id;

      if ($request->isMethod('POST')) {
        // dd($request->all());
        $bankDetails = User::find($request->user_id);
        if ($bankDetails == null) {
          return redirect()->route('admin.bank.list');
        }
        $validationCondition = array(
          'full_name'     => 'required|min:2|max:255|unique:' . (new User)->getTable() . ',full_name,'.$bankDetails->id,

          'phone_no'      => 'required|regex:/^[0-9]\d*(\.\d+)?$/',

          'ifsc_code'     => 'required|min:4|max:255|unique:' . (new UserDetails)->getTable() . ',ifsc_code,' . $request->user_id . ',user_id',
          'address'       => 'required|min:2|max:255',
        );
        $validationMessages = array(
          'full_name.required'    => 'Please enter name',
          'full_name.unique'      => 'The name is already taken',
          'full_name.min'         => 'Name should be at least 2 characters',
          'full_name.max'         => 'Name should not be more than 255 characters',
          'phone_no.required'     => 'Sorry!! Phone number is required',
          'phone_no.regex'        => 'Sorry!! Only number required',
          'ifsc_code.required'    => 'Please enter IFSC Code',
          'ifsc_code.min'         => 'IFSC Code should be should be at least 2 characters',
          'ifsc_code.max'         => 'IFSC Code should not be more than 255 characters',
          'address.required'      => 'Please enter address',
          'address.min'           => 'Address should be at least 2 characters',
          'address.max'           => 'Address should not be more than 255 characters',
        );


        $Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
        if ($Validator->fails()) {
          return redirect()->back()->withErrors($Validator)->withInput();
        } else {


          $bankDetails->full_name      = isset($request->full_name) ? $request->full_name : NULL;
          $bankDetails->email          = isset($request->email) ? $request->email : NULL;
          $bankDetails->phone_no       = isset($request->phone_no) ? $request->phone_no : NULL;
          $saveBank = $bankDetails->save();

          if ($saveBank) {

            $newBankDetails = UserDetails::whereUserId($request->user_id)->first();

            $image = $request->file('profile_image');
            if ($image != '') {
              $originalFileNameCat = $image->getClientOriginalName();
              $extension = pathinfo($originalFileNameCat, PATHINFO_EXTENSION);
              $filename = 'member_' . strtotime(date('Y-m-d H:i:s')) . '.' . $extension;
              $image_resize = Image::make($image->getRealPath());
              $image_resize->save(public_path('uploads/member/' . $filename));

              $newBankDetails->profile_image = $filename;
            }

            $newBankDetails->address        = isset($request->address) ? $request->address : NULL;
            $newBankDetails->ifsc_code      = isset($request->ifsc_code) ? $request->ifsc_code : NULL;
            $saveBankDetails            = $newBankDetails->save();

            $request->session()->flash('alert-success', 'Bank has been updated successfully');
            return redirect()->route('admin.bank.list', ['page' => $pageNo]);
          } else {
            $request->session()->flash('alert-danger', 'An error occurred while updating the state');
            return redirect()->back();
          }
        }
      }


      return view('admin.bank_user.edit')->with(['bankUserDetails' => $bankDetails, 'data' => $data]);
    } catch (Exception $e) {
      return redirect()->route('admin.bank.list')->with('error', $e->getMessage());
    }
  }

  /*****************************************************/
  # BankController
  # Function name : Status
  # Author        :
  # Created Date  : 23-09-2020
  # Purpose       : Showing subAdminList of users
  # Params        : Request $request
  /*****************************************************/
  public function status(Request $request, $id = null)
  {
    try {
      if ($id == null) {
        return redirect()->route('admin.bank.list');
      }
      $details = User::where('id', $id)->first();
      if ($details != null) {
        if ($details->status == 1) {

          $details->status = '0';
          $details->save();

          $request->session()->flash('alert-success', 'Status updated successfully');
        } else if ($details->status == 0) {
          $details->status = '1';
          $details->save();
          $request->session()->flash('alert-success', 'Status updated successfully');
        } else {
          $request->session()->flash('alert-danger', 'Something went wrong');
        }
        return redirect()->back();
      } else {
        return redirect()->route('admin.bank.list')->with('error', 'Invalid state');
      }
    } catch (Exception $e) {
      return redirect()->route('admin.bank.list')->with('error', $e->getMessage());
    }
  }


  /*****************************************************/
  # BankController
  # Function name : Random String
  # Author        :
  # Created Date  : 23-09-2020
  # Purpose       : Generate Random Password
  # Params        : Request $length
  /*****************************************************/

  public function rand_string($length)
  {

    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    return substr(str_shuffle($chars), 0, $length);
  }
  /*****************************************************/
  # BankController
  # Function name : pacslist
  # Author        :
  # Created Date  : 28-09-2022
  # Purpose       : Showing List of Pacs
  # Params        : Request $request
  /*****************************************************/
  public function pacslist(Request $request)
  {
    $data['page_title'] = 'PACS List';
    $data['panel_title'] = 'PACS List';

    try {
      $logginUser = AdminHelper::loggedUser();
      // dd($logginUser);
      $pageNo = $request->input('page');
      Session::put('pageNo', $pageNo);

      $data['order_by']   = 'user_details.created_at';
      $data['order']      = 'desc';

      //$query = User::whereNull('deleted_at')->whereUserType('4')->where('user_parrent_id',$logginUser->id);
      $query = UserDetails::whereBankId($logginUser->id)->whereNotNull('range_id');
      $data['searchText'] = $key = $request->searchText;

      if ($key) {
        // if the search key is provided, proceed to build query for search
        //\DB::enableQueryLog();
        // $query->where(function ($q) use ($key) {
        //   //$q->on('user_details.user_id', '=', 'users.id'); 
        //   $q->where('users.full_name', 'LIKE', '%' . $key . '%');
        //   $q->orWhere('users.email', 'LIKE', '%' . $key . '%');
        //   $q->orWhere('users.phone_no', 'LIKE', '%' . $key . '%');                    
        // })
        // ->join('users','user_details.user_id', '=', 'users.id')->get();

        $query->where(function ($q) use ($key) {
          //$q->on('user_details.user_id', '=', 'users.id'); 
          $q->where('users.full_name', 'LIKE', '%' . $key . '%');
          $q->orWhere('users.email', 'LIKE', '%' . $key . '%');
          $q->orWhere('users.phone_no', 'LIKE', '%' . $key . '%');
        })
          ->join('users', 'user_details.user_id', '=', 'users.id')
          ->select('user_details.*','users.full_name','users.email','users.phone_no'); //change for ID issue date: 08 May 2023

        //dd(\DB::getQueryLog());
      }
      $exists = $query->count();
      if ($exists > 0) {
        //\DB::enableQueryLog();
        $list = $query->orderBy($data['order_by'], $data['order'])->paginate(AdminHelper::ADMIN_LIST_LIMIT);
        //dd(\DB::getQueryLog());
        //dd($list);
        $data['list'] = $list;
      } else {
        $data['list'] = array();
      }

      return view('admin.bank_user.pacslist', $data);
    } catch (Exception $e) {
      return redirect()->route('admin.bank.pacslist')->with('error', $e->getMessage());
    }
  }
  /*****************************************************/
  # BankController
  # Function name : pacsadd
  # Author        :
  # Created Date  : 29-09-2022
  # Purpose       : Add new Pacs user
  # Params        : Request $request
  /*****************************************************/
  public function pacsadd(Request $request)
  {
    $data['page_title']     = 'Add PACS';
    $data['panel_title']    = 'Add PACS';

    try {
      $logginUser = AdminHelper::loggedUser();
      $bankList = User::whereUserParrentId('0')->whereUserType('1')->where('status', '1')->orderBy('full_name', 'asc')->get();
      $data['bankList'] = $bankList;
      // $zoneList = User::whereUserParrentId('0')->where('id',$logginUser->user_parrent_id)->whereUserType('2')->where('status', '1')->orderBy('full_name', 'asc')->get();
      // $data['zoneList'] = $zoneList;
      //14-12-2020 New Upadate
      $zoneList = User::whereUserType('2')->where('status', '1')->orderBy('full_name', 'asc')->get();
      $data['zoneList'] = $zoneList;
      // dd($zoneList);
      // $rangeList = User::whereUserParrentId($logginUser->user_parrent_id)->where('id',$logginUser->id)->whereUserType('3')->where('status', '1')->orderBy('full_name', 'asc')->get();
      // $data['rangeList'] = $rangeList;
      //14-12-2020 New Upadte
      $rangeList = User::whereUserType('3')->where('status', '1')->orderBy('full_name', 'asc')->get();
      $data['rangeList'] = $rangeList;
      $districtList = District::select('id', 'district_name')->where('status', '1')->orderBy('district_name', 'asc')->get();
      $data['districtList'] = $districtList;
      $blockList = Block::select('id', 'block_name')->where('status', '1')->orderBy('block_name', 'asc')->get();
      $data['blockList'] = $blockList;
      $softwareList = Software::select('id', 'full_name')->where('status', '1')->orderBy('full_name', 'asc')->get();
      $data['softwareList'] = $softwareList;
      $societiesList = Societie::select('id', 'name')->where('status', '1')->orderBy('name', 'asc')->get();
      $data['societiesList'] = $societiesList;
      if ($request->isMethod('POST')) {
        $validationCondition = array(
          'full_name'     => 'required|min:2|max:255',
          'email'         => 'required|regex:/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/|unique:' . (new User)->getTable() . ',email',
          'phone_no'      => 'required|regex:/^[0-9]\d*(\.\d+)?$/',

          'address'       => 'required|min:2|max:255',
        );
        $validationMessages = array(
          'full_name.required'        => 'Please enter name',
          'full_name.min'             => 'Name should be at least 2 characters',
          'full_name.max'             => 'Name should not be more than 255 characters',
          'email.required'            => 'Sorry!! Email is required',
          'email.regex'               => 'Sorry!! Please send a valid email',
          'phone_no.required'         => 'Sorry!! Phone number is required',
          'phone_no.regex'            => 'Sorry!! Only number required',
          'bank_id.required'          => 'Please select Bank',
          'zone_id.required'          => 'Please select Zone',
          // 'range_id.required'         => 'Please select Range',
          'district_id.required'      => 'Please select District',
          // 'block_id.required'         => 'Please select Block',
          'block.required'            => 'Please select Block',
          'software_using.required'   => 'Please select Software',
          'address.required'          => 'Please enter address',
          'address.min'               => 'Address should be at least 2 characters',
          'address.max'               => 'Address should not be more than 255 characters',
        );

        $Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
        if ($Validator->fails()) {
          return redirect()->route('admin.bank.pacsadd')->withErrors($Validator)->withInput();
        } else {
          //dd($request->all());
          $generatedPassword = $this->rand_string(8);
          $newPacs = new User;
          $newPacs->user_type      = '4';
          $newPacs->user_parrent_id   = isset($request->range_id) ? $request->range_id : NULL;
          $newPacs->full_name      = isset($request->full_name) ? $request->full_name : NULL;
          $newPacs->email          = isset($request->email) ? $request->email : NULL;
          $newPacs->phone_no       = isset($request->phone_no) ? $request->phone_no : NULL;
          $newPacs->password       = $generatedPassword;
          $newPacs->status         = '1';
          $savePacsUser            = $newPacs->save();

          if ($savePacsUser) {

            $newPacsDetails = new UserDetails;

            $image = $request->file('profile_image');
            if ($image != '') {
              $originalFileNameCat = $image->getClientOriginalName();
              $extension = pathinfo($originalFileNameCat, PATHINFO_EXTENSION);
              $filename = 'member_' . strtotime(date('Y-m-d H:i:s')) . '.' . $extension;

              $image_resize = Image::make($image->getRealPath());
              $image_resize->save(public_path('uploads/member/' . $filename));

              $newPacsDetails->profile_image = $filename;
            }

            $newPacsDetails->user_id                        = $newPacs->id;
            $newPacsDetails->address                        = isset($request->address) ? $request->address : NULL;
            $newPacsDetails->socity_type                    = isset($request->socity_type) ? $request->socity_type : NULL;
            $newPacsDetails->socity_registration_no         = isset($request->socity_registration_no) ? $request->socity_registration_no : NULL;
            $newPacsDetails->district_registration_no       = isset($request->district_registration_no) ? $request->district_registration_no : NULL;
            $newPacsDetails->bank_id                        = isset($request->bank_id) ? $request->bank_id : NULL;
            $newPacsDetails->zone_id                        = isset($request->zone_id) ? $request->zone_id : NULL;
            $newPacsDetails->range_id                       = isset($request->range_id) ? $request->range_id : NULL;
            $newPacsDetails->district_id                    = isset($request->district_id) ? $request->district_id : NULL;
            $newPacsDetails->block                          = isset($request->block) ? $request->block : NULL;
            $newPacsDetails->software_using                 = isset($request->software_using) ? $request->software_using : NULL;
            $newPacsDetails->unique_id                      = isset($request->unique_id) ? $request->unique_id : NULL;

            // new field add date:24/04/2023 by PK
            $newPacsDetails->whether_the_pacs_received_csp_fund_from_ncdc = isset($request->whether_the_PACS_received_CSP_fund_from_NCDC) ? $request->whether_the_PACS_received_CSP_fund_from_NCDC : NULL;
            $newPacsDetails->whether_the_csp_infrastructure_is_ready = isset($request->whether_the_CSP_infrastructure_is_ready) ? $request->whether_the_CSP_infrastructure_is_ready : NULL;
            $newPacsDetails->whether_csp_is_live = isset($request->whether_CSP_is_live) ? $request->whether_CSP_is_live : NULL;
            // new field add date:24/04/2023 by PK

            $savePacsDetails                                = $newPacsDetails->save();

            // Send Mail to Bank User with Username, Password
            \Mail::send(
              'email_templates.bank_credential',
              [
                'user' => $newPacs,
                'app_config' => [
                  'Password'      => $generatedPassword,
                  'appLink'       => Helper::getBaseUrl(),
                  'controllerName' => 'user',
                  'subject'       => 'Pacs User Login Credential',
                ],
              ],
              function ($m) use ($newPacs) {
                $m->to($newPacs->email, $newPacs->full_name)->subject('Pacs User Credential');
              }
            );


            $request->session()->flash('alert-success', 'Pacs has been added successfully');
            return redirect()->route('admin.bank.pacslist');
          } else {
            $request->session()->flash('alert-danger', 'An error occurred while adding the pacs');
            return redirect()->back();
          }
        }
      }
      // dd($data);
      return view('admin.bank_user.pacsadd', $data);
    } catch (Exception $e) {
      return redirect()->route('admin.bank.pacslist')->with('error', $e->getMessage());
    }
  }
  /*****************************************************/
  # BankController
  # Function name : Status
  # Author        :
  # Created Date  : 29-09-2022
  # Purpose       : Changing Pacs users status
  # Params        : Request $request
  /*****************************************************/
  public function pacsstatus(Request $request, $id = null)
  {
    try {
      if ($id == null) {
        return redirect()->route('admin.bank.pacslist');
      }
      $userDetails = UserDetails::where('id', $id)->first();
      //dd($userDetails);
      $details = User::where('id', $userDetails->user_id)->first();
      //dd($details);
      if ($details != null) {
        if ($details->status == 1) {

          $details->status = '0';
          $details->save();

          $request->session()->flash('alert-success', 'Status updated successfully');
        } else if ($details->status == 0) {
          $details->status = '1';
          $details->save();
          $request->session()->flash('alert-success', 'Status updated successfully');
        } else {
          $request->session()->flash('alert-danger', 'Something went wrong');
        }
        return redirect()->back();
      } else {
        return redirect()->route('admin.bank.pacslist')->with('error', 'Invalid state');
      }
    } catch (Exception $e) {
      return redirect()->route('admin.bank.pacslist')->with('error', $e->getMessage());
    }
  }
  /*****************************************************/
  # PacsController
  # Function name : Edit
  # Author        :
  # Created Date  : 24-09-2020
  # Purpose       : Edit Range user
  # Params        : Request $request
  /*****************************************************/
  public function pacsedit(Request $request, $id = null)
  {
    $data['page_title']  = 'Edit PACS';
    $data['panel_title'] = 'Edit PACS';

    try {
      $logginUser = AdminHelper::loggedUser();
      $pageNo = Session::get('pageNo') ? Session::get('pageNo') : '';
      $data['pageNo'] = $pageNo;
      $userDetails = UserDetails::where('id', $id)->first();
      //dd($userDetails);
      $pacsDetails = User::find($userDetails->user_id);
      //dd($pacsDetails->userProfile);
      $bankList = User::whereUserParrentId('0')->whereUserType('1')->where('status', '1')->orderBy('full_name', 'asc')->get();
      $data['bankList'] = $bankList;
      $zoneList = User::whereUserParrentId('0')->whereUserType('2')->where('status', '1')->orderBy('full_name', 'asc')->get();
      $data['zoneList'] = $zoneList;
      $rangeList = User::whereUserType('3')->where('status', '1')->orderBy('full_name', 'asc')->get();
      $data['rangeList'] = $rangeList;
      // dd($rangeList);
      $districtList = District::select('id', 'district_name')->where('status', '1')->orderBy('district_name', 'asc')->get();
      $data['districtList'] = $districtList;
      //$blockList = Block::select('id','block_name')->where('status', '1')->orderBy('block_name', 'asc')->get();
      $blockList = Block::select('id','block_name')->where('status', '1')->where('district_id',$pacsDetails->userProfile['district_id'])->orderBy('block_name', 'asc')->get();
      //dd($blockList);
      $data['blockList'] = $blockList;
      $softwareList = Software::select('id', 'full_name')->where('status', '1')->orderBy('full_name', 'asc')->get();
      $data['softwareList'] = $softwareList;
      $societiesList = Societie::select('id', 'name')->where('status', '1')->orderBy('name', 'asc')->get();
      $data['societiesList'] = $societiesList;

      $data['id'] = $id;
      //dd($data);
      if ($request->isMethod('POST')) {
        //dd($request->all());
        $pacsDetails = User::find($request->user_id);
        if ($pacsDetails == null) {
          return redirect()->route('admin.bank.pacslist');
        }
        $validationCondition = array(
          'full_name'     => 'required|min:2|max:255',
          // 'email'         => 'required|regex:/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/|unique:'.(new User)->getTable().',email',
          'phone_no'      => 'required|regex:/^[0-9]\d*(\.\d+)?$/',

          'address'       => 'required|min:2|max:255',
        );
        $validationMessages = array(
          'full_name.required'        => 'Please enter name',
          'full_name.min'             => 'Name should be at least 2 characters',
          'full_name.max'             => 'Name should not be more than 255 characters',
          // 'email.required'            => 'Sorry!! Email is required',
          // 'email.regex'               => 'Sorry!! Please send a valid email',
          'phone_no.required'         => 'Sorry!! Phone number is required',
          'phone_no.regex'            => 'Sorry!! Only number required',
          'bank_id.required'          => 'Please select Bank',
          'zone_id.required'          => 'Please select Zone',
          'range_id.required'         => 'Please select Range',
          'district_id.required'      => 'Please select District',
          // 'block_id.required'         => 'Please select Block',
          'block.required'            => 'Please select Block',
          'software_using.required'   => 'Please select Software',
          'address.required'          => 'Please enter address',
          'address.min'               => 'Address should be at least 2 characters',
          'address.max'               => 'Address should not be more than 255 characters',
        );


        $Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
        if ($Validator->fails()) {
          return redirect()->back()->withErrors($Validator)->withInput();
        } else {


          $pacsDetails->full_name      = isset($request->full_name) ? $request->full_name : NULL;
          $pacsDetails->email          = isset($request->email) ? $request->email : NULL;
          $pacsDetails->phone_no       = isset($request->phone_no) ? $request->phone_no : NULL;
          $savePacs                    = $pacsDetails->save();

          if ($savePacs) {

            $newPacsDetails = UserDetails::whereUserId($request->user_id)->first();

            $image = $request->file('profile_image');
            if ($image != '') {
              $originalFileNameCat = $image->getClientOriginalName();
              $extension = pathinfo($originalFileNameCat, PATHINFO_EXTENSION);
              $filename = 'member_' . strtotime(date('Y-m-d H:i:s')) . '.' . $extension;
              $image_resize = Image::make($image->getRealPath());
              $image_resize->save(public_path('uploads/member/' . $filename));

              $newPacsDetails->profile_image = $filename;
            }

            $newPacsDetails->address                        = isset($request->address) ? $request->address : NULL;
            $newPacsDetails->socity_type                    = isset($request->socity_type) ? $request->socity_type : NULL;
            $newPacsDetails->socity_registration_no         = isset($request->socity_registration_no) ? $request->socity_registration_no : NULL;
            $newPacsDetails->district_registration_no       = isset($request->district_registration_no) ? $request->district_registration_no : NULL;
            $newPacsDetails->bank_id                        = isset($request->bank_id) ? $request->bank_id : NULL;
            $newPacsDetails->zone_id                        = isset($request->zone_id) ? $request->zone_id : NULL;
            $newPacsDetails->range_id                       = isset($request->range_id) ? $request->range_id : NULL;
            $newPacsDetails->district_id                    = isset($request->district_id) ? $request->district_id : NULL;
            $newPacsDetails->block                          = isset($request->block) ? $request->block : NULL;
            $newPacsDetails->software_using                 = isset($request->software_using) ? $request->software_using : NULL;
            $newPacsDetails->unique_id                      = isset($request->unique_id) ? $request->unique_id : NULL;

            // new field edit date:24/04/2023 by PK
            $newPacsDetails->whether_the_pacs_received_csp_fund_from_ncdc = isset($request->whether_the_PACS_received_CSP_fund_from_NCDC) ? $request->whether_the_PACS_received_CSP_fund_from_NCDC : NULL;
            $newPacsDetails->whether_the_csp_infrastructure_is_ready = isset($request->whether_the_CSP_infrastructure_is_ready) ? $request->whether_the_CSP_infrastructure_is_ready : NULL;
            $newPacsDetails->whether_csp_is_live = isset($request->whether_CSP_is_live) ? $request->whether_CSP_is_live : NULL;
            // new field edit date:24/04/2023 by PK

            $savePacsDetails                                = $newPacsDetails->save();

            $request->session()->flash('alert-success', 'Pacs has been updated successfully');
            return redirect()->route('admin.bank.pacslist', ['page' => $pageNo]);
          } else {
            $request->session()->flash('alert-danger', 'An error occurred while updating the pacs');
            return redirect()->back();
          }
        }
      }


      return view('admin.bank_user.pacsedit')->with([
        'details' => $pacsDetails, 'data' => $data, 'bankList' => $bankList, 'zoneList' => $zoneList, 'rangeList' => $rangeList,
        'districtList' => $districtList, 'softwareList' => $softwareList, 'societiesList' => $societiesList,'blockList'=>$blockList
      ]);
    } catch (Exception $e) {
      return redirect()->route('admin.bank.pacslist')->with('error', $e->getMessage());
    }
  }

   /*****************************************************/
  # BankController
  # Function name : exportUserBank
  # Author        : prasun
  # Created Date  : 11-05-2023
  # Purpose       : Export pacs user
  # Params        : 
  /*****************************************************/
  public function exportUserBank() 
  {
    return Excel::download(new ExportUserBank, 'pacsusers.xlsx');
  }
}
