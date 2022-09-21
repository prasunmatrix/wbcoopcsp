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
    public function list(Request $request) {
        $data['page_title'] = 'Bank List';
        $data['panel_title']= 'Bank List';
        
        try
        {
            $pageNo = $request->input('page');
            Session::put('pageNo',$pageNo);

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
    public function add(Request $request) {
        $data['page_title']     = 'Add Bank';
        $data['panel_title']    = 'Add Bank';
    
        try
        {
        	if ($request->isMethod('POST'))
        	{
				$validationCondition = array(
                    'full_name'     => 'required|min:2|max:255|unique:'.(new User)->getTable().',full_name',
                    'email'         => 'required|regex:/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/|unique:'.(new User)->getTable().',email',
                    'phone_no'      => 'required|regex:/^[0-9]\d*(\.\d+)?$/',

                    'ifsc_code'     => 'required|min:4|max:255|unique:'.(new UserDetails)->getTable().',ifsc_code',
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
                            $filename = 'member_'.strtotime(date('Y-m-d H:i:s')).'.'.$extension;

                            $image_resize = Image::make($image->getRealPath());
                            $image_resize->save(public_path('uploads/member/' . $filename));

                            $newBankDetails->profile_image = $filename;
                       
                        }                 	    
                      
                        
                        $newBankDetails->user_id        = $newBank->id;
                        $newBankDetails->address        = isset($request->address) ? $request->address : NULL;
                        $newBankDetails->ifsc_code      = isset($request->ifsc_code) ? $request->ifsc_code : NULL;
                        $saveBankDetails                = $newBankDetails->save();


                        // Send Mail to Bank User with Username, Password
                            \Mail::send('email_templates.bank_credential',
                            [
                                'user' => $newBank,
                                'app_config' => [
                                    'Password'      => $generatedPassword,  
                                    'appLink'       => Helper::getBaseUrl(),
                                    'controllerName'=> 'user',
                                    'subject'       => 'Bank User Login Credential',
                                ],
                            ], function ($m) use ($newBank) {
                                $m->to($newBank->email, $newBank->full_name)->subject('Bank User Credential');
                            }); 


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
    public function edit(Request $request, $id = null) {
        $data['page_title']  = 'Edit Bank';
        $data['panel_title'] = 'Edit Bank';

        try
        {           
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
                    'full_name'     => 'required|min:2|max:255|unique:'.(new User)->getTable().',full_name',
                    
                    'phone_no'      => 'required|regex:/^[0-9]\d*(\.\d+)?$/',

                    'ifsc_code'     => 'required|min:4|max:255|unique:'.(new UserDetails)->getTable().',ifsc_code,'.$request->user_id.',user_id' ,
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
                            $filename = 'member_'.strtotime(date('Y-m-d H:i:s')).'.'.$extension;
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
        try
        {
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

    public function rand_string($length) {

        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        return substr(str_shuffle($chars), 0, $length);

    }
    
}
