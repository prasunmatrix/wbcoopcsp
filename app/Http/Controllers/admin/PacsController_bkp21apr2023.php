<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Helper;
use Image, Auth, Hash, Redirect, Validator, View;
use AdminHelper;
use App\User;
use App\UserDetails;
use App\Bank;
use App\Block;
use App\District;
use App\Software;
use App\Societie;

class PacsController extends Controller
{
    /*****************************************************/
    # PacsController
    # Function name : List
    # Author        :
    # Created Date  : 24-09-2020
    # Purpose       : Showing List of Range
    # Params        : Request $request
    /*****************************************************/
    public function list(Request $request) {
        $data['page_title'] = 'PACS List';
        $data['panel_title']= 'PACS List';
        
        try
        {
            $pageNo = $request->input('page');
            Session::put('pageNo',$pageNo);

            $data['order_by']   = 'created_at';
            $data['order']      = 'desc';

            $query = User::whereNull('deleted_at')->whereUserType('4');

            $data['searchText'] = $key = $request->searchText;

            if ($key) {
                // if the search key is provided, proceed to build query for search
                $query->where(function ($q) use ($key) {
                    $q->where('full_name', 'LIKE', '%' . $key . '%');
                    $q->orWhere('email', 'LIKE', '%' . $key . '%');
                    $q->orWhere('phone_no', 'LIKE', '%' . $key . '%');
                    
                    
                });
            }
            $exists = $query->count();
            if ($exists > 0) {
               //\DB::enableQueryLog();
                $list = $query->orderBy($data['order_by'], $data['order'])->paginate(AdminHelper::ADMIN_LIST_LIMIT);
                //dd(\DB::getQueryLog());
                $data['list'] = $list;
            } else {
                $data['list'] = array();
            }    

            return view('admin.pacs.list',$data);

        } catch (Exception $e) {
            return redirect()->route('admin.pacs.list')->with('error', $e->getMessage());
        }
    }

   /*****************************************************/
    # PacsController
    # Function name : Add
    # Author        :
    # Created Date  : 24-09-2020
    # Purpose       : Add new Range user
    # Params        : Request $request
    /*****************************************************/
    public function add(Request $request) {
        $data['page_title']     = 'Add PACS';
        $data['panel_title']    = 'Add PACS';
    
        try
        {
            $bankList = User::whereUserParrentId('0')->whereUserType('1')->where('status', '1')->orderBy('full_name', 'asc')->get();
            $data['bankList'] = $bankList;
            $zoneList = User::whereUserParrentId('0')->whereUserType('2')->where('status', '1')->orderBy('full_name', 'asc')->get();
            $data['zoneList'] = $zoneList;
            $rangeList = User::whereUserType('3')->where('status', '1')->orderBy('full_name', 'asc')->get();
            $data['rangeList'] = $rangeList;
            $districtList = District::select('id','district_name')->where('status', '1')->orderBy('district_name', 'asc')->get();
            $data['districtList'] = $districtList;
            //block list open by pk date: 13/10/2022
            $blockList = Block::select('id','block_name')->where('status', '1')->orderBy('block_name', 'asc')->get();
            $data['blockList'] = $blockList;
            //block list open by pk date: 13/10/2022
            $softwareList = Software::select('id','full_name')->where('status', '1')->orderBy('full_name', 'asc')->get();
            $data['softwareList'] = $softwareList;
            $societiesList = Societie::select('id','name')->where('status', '1')->orderBy('name', 'asc')->get();
            $data['societiesList'] = $societiesList;
        	if ($request->isMethod('POST'))
        	{
				$validationCondition = array(
                    'full_name'     => 'required|min:2|max:255',
                    'email'         => 'required|regex:/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/|unique:'.(new User)->getTable().',email',
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
                    'software_using.required'   => 'Please select Software',
                    'address.required'          => 'Please enter address',
                    'address.min'               => 'Address should be at least 2 characters',
                    'address.max'               => 'Address should not be more than 255 characters',
				);

				$Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
				if ($Validator->fails()) {
					return redirect()->route('admin.pacs.add')->withErrors($Validator)->withInput();
				} else {
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
                            $filename = 'member_'.strtotime(date('Y-m-d H:i:s')).'.'.$extension;

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
                        $savePacsDetails                                = $newPacsDetails->save();

                        // Send Mail to Bank User with Username, Password
                            \Mail::send('email_templates.bank_credential',
                            [
                                'user' => $newPacs,
                                'app_config' => [
                                    'Password'      => $generatedPassword,  
                                    'appLink'       => Helper::getBaseUrl(),
                                    'controllerName'=> 'user',
                                    'subject'       => 'Pacs User Login Credential',
                                ],
                            ], function ($m) use ($newPacs) {
                                $m->to($newPacs->email, $newPacs->full_name)->subject('Pacs User Credential');
                            }); 


						$request->session()->flash('alert-success', 'Pacs has been added successfully');
						return redirect()->route('admin.pacs.list');
					} else {
						$request->session()->flash('alert-danger', 'An error occurred while adding the pacs');
						return redirect()->back();
					}
				}
            }
           // dd($data);
			return view('admin.pacs.add',$data);
		} catch (Exception $e) {
			return redirect()->route('admin.pacs.list')->with('error', $e->getMessage());
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
    public function edit(Request $request, $id = null) {
        $data['page_title']  = 'Edit PACS';
        $data['panel_title'] = 'Edit PACS';

        try
        {           
            $pageNo = Session::get('pageNo') ? Session::get('pageNo') : '';
            $data['pageNo'] = $pageNo;
            $pacsDetails = User::find($id);
            // dd($pacsDetails->userProfile);
            $bankList = User::whereUserParrentId('0')->whereUserType('1')->where('status', '1')->orderBy('full_name', 'asc')->get();
            $data['bankList'] = $bankList;
            $zoneList = User::whereUserParrentId('0')->whereUserType('2')->where('status', '1')->orderBy('full_name', 'asc')->get();
            $data['zoneList'] = $zoneList;
            //\DB::enableQueryLog();
            //$rangeList = User::whereUserParrentId($pacsDetails->userProfile->zone_id)->whereUserType('3')->where('status', '1')->orderBy('full_name', 'asc')->get();
            //changes the query by PK date: 12/10/2022
            //(\DB::getQueryLog());
            $rangeList = User::whereUserType('3')->where('status', '1')->orderBy('full_name', 'asc')->get();
            $data['rangeList'] = $rangeList;
            //dd($rangeList);
            $districtList = District::select('id','district_name')->where('status', '1')->orderBy('district_name', 'asc')->get();
            $data['districtList'] = $districtList;
            //block list open by pk date: 12/10/2022
            $blockList = Block::select('id','block_name')->where('status', '1')->orderBy('block_name', 'asc')->get();
            $data['blockList'] = $blockList;
            //block list open by pk date: 12/10/2022
            $softwareList = Software::select('id','full_name')->where('status', '1')->orderBy('full_name', 'asc')->get();
            $data['softwareList'] = $softwareList;
            $societiesList = Societie::select('id','name')->where('status', '1')->orderBy('name', 'asc')->get();
            $data['societiesList'] = $societiesList;
            
            $data['id'] = $id;

            if ($request->isMethod('POST')) {
               // dd($request->all());
                $pacsDetails = User::find($request->user_id);
                if ($pacsDetails == null) {
                    return redirect()->route('admin.pacs.list');
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
                            $filename = 'member_'.strtotime(date('Y-m-d H:i:s')).'.'.$extension;
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
                        $savePacsDetails                                = $newPacsDetails->save();

                        $request->session()->flash('alert-success', 'Pacs has been updated successfully');
                        return redirect()->route('admin.pacs.list', ['page' => $pageNo]);
                    } else {
                        $request->session()->flash('alert-danger', 'An error occurred while updating the pacs');
                        return redirect()->back();
                    }
                }
            }
            
            
            return view('admin.pacs.edit')->with(['details' => $pacsDetails, 'data' => $data, 'bankList' => $bankList,'zoneList' => $zoneList,'rangeList' => $rangeList,
            'districtList' => $districtList, 'softwareList' => $softwareList, 'societiesList' => $societiesList,'blockList' => $blockList]);

        } catch (Exception $e) {
            return redirect()->route('admin.pacs.list')->with('error', $e->getMessage());
        }
    }

    
    /*****************************************************/
    # PacsController
    # Function name : Pacs Acknowledgement
    # Author        :
    # Created Date  : 24-09-2020
    # Purpose       : Show Pacs Details
    # Params        : Request $request
    /*****************************************************/
    public function pacsAcknowledement(Request $request) {
        $data['page_title']  = 'Acknowledge PACS Details';
        $data['panel_title'] = 'Acknowledge PACS Details';

        $user = \Auth::guard('admin')->user();

        try
        {           
            $pageNo = Session::get('pageNo') ? Session::get('pageNo') : '';
            $data['pageNo'] = $pageNo;
            $pacsDetails = User::find($user->id);
            //dd($pacsDetails->userProfile);
            $bankList = User::whereUserParrentId('0')->whereUserType('1')->where('status', '1')->orderBy('full_name', 'asc')->get();
            $data['bankList'] = $bankList;
            $zoneList = User::whereUserParrentId('0')->whereUserType('2')->where('status', '1')->orderBy('full_name', 'asc')->get();
            $data['zoneList'] = $zoneList;
            //\DB::enableQueryLog();
            //$rangeList = User::whereUserParrentId($pacsDetails->userProfile->zone_id)->whereUserType('3')->where('status', '1')->orderBy('full_name', 'asc')->get();
            //changes the query by PK date: 27/09/2022 
            $rangeList = User::whereUserType('3')->where('status', '1')->orderBy('full_name', 'asc')->get(); 
            //dd(\DB::getQueryLog());
            $data['rangeList'] = $rangeList;
            // dd($rangeList);
            $districtList = District::select('id','district_name')->where('status', '1')->orderBy('district_name', 'asc')->get();
            $data['districtList'] = $districtList;
            $blockList = Block::select('id','block_name')->where('district_id',$pacsDetails->userProfile['district_id'])->where('status', '1')->orderBy('block_name', 'asc')->get();
            $data['blockList'] = $blockList;
            $softwareList = Software::select('id','full_name')->where('status', '1')->orderBy('full_name', 'asc')->get();
            $data['softwareList'] = $softwareList;
            $societiesList = Societie::select('id','name')->where('status', '1')->orderBy('name', 'asc')->get();
            $data['societiesList'] = $societiesList;
            
            $data['id'] = $user->id;

            if ($request->isMethod('POST')) {
                //dd($request->all());
                $pacsDetails = User::find($request->user_id);
                if ($pacsDetails == null) {
                    return redirect()->route('admin.pacs.list');
                }
                $validationCondition = array(
                    'full_name'     => 'required|min:2|max:255',
                    // 'email'         => 'required|regex:/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/|unique:'.(new User)->getTable().',email',
                    'phone_no'      => 'required|regex:/^[0-9]\d*(\.\d+)?$/',

                    'address'       => 'required|min:2|max:255',
                    'information_correct_verified' => 'required',
                    'unique_id_noted' => 'required',
                    'pacs_using_software' => 'required',
                    'pacs_uploaded_format' => 'required',

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
                    'block_id.required'         => 'Please select Block',
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
                            $filename = 'member_'.strtotime(date('Y-m-d H:i:s')).'.'.$extension;
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
                        //$newPacsDetails->block_id                       = isset($request->block_id) ? $request->block_id : NULL; \\changes by pk date:27/09/2022
                        //$newPacsDetails->software_using                 = isset($request->software_using) ? $request->software_using : NULL;
                        $newPacsDetails->information_correct_verified   = '1';
                        $newPacsDetails->unique_id_noted                = '1';
                        $newPacsDetails->pacs_using_software            = '1';
                        $newPacsDetails->pacs_uploaded_format           = '1';
                        $savePacsDetails                                = $newPacsDetails->save();

                        
                         session()->forget('pacs_acknowledge');
                         \Session::put('clicked', 'No');

                        $request->session()->flash('alert-success', 'Pacs has been updated successfully');
                        return redirect()->route('admin.dashboard', ['page' => $pageNo]);
                    } else {
                        $request->session()->flash('alert-danger', 'An error occurred while updating the pacs');
                        return redirect()->back();
                    }
                }
            }
            $readonlyStatus = false;
            if($pacsDetails->userProfile->information_correct_verified != 0 || $pacsDetails->userProfile->unique_id_noted != 0 ||$pacsDetails->userProfile->pacs_using_software != 0 || $pacsDetails->userProfile->pacs_uploaded_format != 0){
                $readonlyStatus = true;
            }
            
            return view('admin.pacs.acknowledge')->with(['details' => $pacsDetails, 'data' => $data, 'bankList' => $bankList,'zoneList' => $zoneList,'rangeList' => $rangeList,
            'districtList' => $districtList, 'blockList' => $blockList, 'softwareList' => $softwareList, 'societiesList' => $societiesList,'readonlyStatus' => $readonlyStatus]);

        } catch (Exception $e) {
            return redirect()->route('admin.dashboard')->with('error', $e->getMessage());
        }
    }

    


    /*****************************************************/
    # PacsController
    # Function name : Status
    # Author        :
    # Created Date  : 24-09-2020
    # Purpose       : Changing Range users status
    # Params        : Request $request
    /*****************************************************/
    public function status(Request $request, $id = null)
    {
        try
        {
            if ($id == null) {
                return redirect()->route('admin.pacs.list');
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
                return redirect()->route('admin.pacs.list')->with('error', 'Invalid state');
            }
        } catch (Exception $e) {
            return redirect()->route('admin.pacs.list')->with('error', $e->getMessage());
        }
    }

    
    /*****************************************************/
    # PacsController
    # Function name : Random String
    # Author        :
    # Created Date  : 24-09-2020
    # Purpose       : Generate Random Password
    # Params        : Request $length
    /*****************************************************/

    public function rand_string($length) {

        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        return substr(str_shuffle($chars), 0, $length);
        

    }

    /*****************************************************/
    # RangeController
    # Function name : Getzone
    # Author        :
    # Created Date  : 25-09-2020
    # Purpose       : Get Bank wise Zone List
    # Params        : Request $request
    /*****************************************************/

    public function getPacsZone(Request $request)
    {
         $validator = Validator::make($request->all(), [ 
            'bank_id' => 'required',
            ]);

           if ($validator->fails()) { 
              return response()->json(['success' =>false,'message'=>$validator->errors()->first()], 200);
            }

        $allZone = User::where('user_parrent_id', $request->bank_id)->get();
        return response()->json(['status'=>true, 'allZone'=>$allZone,],200);
    }

    /*****************************************************/
    # RangeController
    # Function name : Getzone
    # Author        :
    # Created Date  : 25-09-2020
    # Purpose       : Get Bank wise Zone List
    # Params        : Request $request
    /*****************************************************/

    public function getPacsRange(Request $request)
    {
         $validator = Validator::make($request->all(), [ 
            'zone_id' => 'required',
            ]);

           if ($validator->fails()) { 
              return response()->json(['success' =>false,'message'=>$validator->errors()->first()], 200);
            }

        $allRange = User::where('user_parrent_id', $request->zone_id)->get();
        return response()->json(['status'=>true, 'allRange'=>$allRange,],200);
    }

    /*****************************************************/
    # RangeController
    # Function name : Getzone
    # Author        :
    # Created Date  : 25-09-2020
    # Purpose       : Get Bank wise Zone List
    # Params        : Request $request
    /*****************************************************/

    public function getPacsBlock(Request $request)
    {
         $validator = Validator::make($request->all(), [ 
            'district_id' => 'required',
            ]);

           if ($validator->fails()) { 
              return response()->json(['success' =>false,'message'=>$validator->errors()->first()], 200);
            }

        $allBlock = Block::where('district_id',$request->district_id)->get();
        return response()->json(['status'=>true, 'allBlock'=>$allBlock,],200);
    }
    
}
