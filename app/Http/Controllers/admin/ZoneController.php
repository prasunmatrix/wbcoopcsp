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

class ZoneController extends Controller
{
    /*****************************************************/
    # ZoneController
    # Function name : List
    # Author        :
    # Created Date  : 24-09-2020
    # Purpose       : Showing List of Zone
    # Params        : Request $request
    /*****************************************************/
    public function list(Request $request) {
        $data['page_title'] = 'Zone List';
        $data['panel_title']= 'Zone List';
        
        try
        {
            $pageNo = $request->input('page');
            Session::put('pageNo',$pageNo);

            $data['order_by']   = 'created_at';
            $data['order']      = 'desc';

            $query = User::whereNull('deleted_at')->whereUserType('2');

            $data['searchText'] = $key = $request->searchText;

            if ($key) {
                // if the search key is provided, proceed to build query for search
                $query->where(function ($q) use ($key) {
                    $q->where('full_name', 'LIKE', '%' . $key . '%');
                    $q->orWhere('email', 'LIKE', '%' . $key . '%');
                    $q->orWhere('phone_no', 'LIKE', '%' . $key . '%');
                    $q->orWhereHas('userProfile.userBank', function ($qe) use ($key) {
                        $qe->where('full_name', 'like', '%' . $key . '%');
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

            return view('admin.zone_user.list')->with(['data' => $data]);

        } catch (Exception $e) {
            return redirect()->route('admin.zone.list')->with('error', $e->getMessage());
        }
    }

   /*****************************************************/
    # ZoneController
    # Function name : Add
    # Author        :
    # Created Date  : 24-09-2020
    # Purpose       : Add new Zone user
    # Params        : Request $request
    /*****************************************************/
    public function add(Request $request) {
        $data['page_title']     = 'Add Zone';
        $data['panel_title']    = 'Add Zone';
    
        try
        {
            // $bankList = User::whereUserParrentId('0')->whereUserType('1')->orderBy('full_name', 'asc')->get();
        	if ($request->isMethod('POST'))
        	{
				$validationCondition = array(
                    'full_name'     => 'required|min:2|max:255',
                    'email'         => 'required|regex:/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/|unique:'.(new User)->getTable().',email,NULL,NULL,deleted_at,NULL',
                    'phone_no'      => 'required|regex:/^[0-9]\d*(\.\d+)?$/',
                    'address'       => 'required|min:2|max:255',
				);
				$validationMessages = array(
					'full_name.required'    => 'Please enter name',
					'full_name.min'         => 'Name should be at least 2 characters',
                    'full_name.max'         => 'Name should not be more than 255 characters',
                    'email.required'        => 'Sorry!! Email is required',
                    'email.regex'           => 'Sorry!! Please send a valid email',
                    'phone_no.required'     => 'Sorry!! Phone number is required',
                    'phone_no.regex'        => 'Sorry!! Only number required',
                    'address.required'      => 'Please enter address',
                    'address.min'           => 'Address should be at least 2 characters',
                    'address.max'           => 'Address should not be more than 255 characters',
				);

				$Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
				if ($Validator->fails()) {
					return redirect()->route('admin.zone.add')->withErrors($Validator)->withInput();
				} else {
                    $generatedPassword = $this->rand_string(8);
                    $newBank = new User;
                    $newBank->user_type         = '2';
                    $newBank->full_name         = isset($request->full_name) ? $request->full_name : NULL;
                    $newBank->email             = isset($request->email) ? $request->email : NULL; 
                    $newBank->phone_no          = isset($request->phone_no) ? $request->phone_no : NULL;
                    $newBank->password          = $generatedPassword;
                    $newBank->status            = '1';
                    $saveBankUser               = $newBank->save();
                
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
                        $saveBankDetails                = $newBankDetails->save();

                        // Send Mail to Bank User with Username, Password
                            \Mail::send('email_templates.bank_credential',
                            [
                                'user' => $newBank,
                                'app_config' => [
                                    'Password'      => $generatedPassword,  
                                    'appLink'       => Helper::getBaseUrl(),
                                    'controllerName'=> 'user',
                                    'subject'       => 'Zone User Login Credential',
                                ],
                            ], function ($m) use ($newBank) {
                                $m->to($newBank->email, $newBank->full_name)->subject('Zone User Credential');
                            }); 


						$request->session()->flash('alert-success', 'Zone has been added successfully');
						return redirect()->route('admin.zone.list');
					} else {
						$request->session()->flash('alert-danger', 'An error occurred while adding the district');
						return redirect()->back();
					}
				}
            }
           // dd($data);
			return view('admin.zone_user.add')->with(['bankUserDetails' => $data]);
		} catch (Exception $e) {
			return redirect()->route('admin.zone.list')->with('error', $e->getMessage());
		}
    }

    /*****************************************************/
    # ZoneController
    # Function name : Edit
    # Author        :
    # Created Date  : 24-09-2020
    # Purpose       : Edit Zone user
    # Params        : Request $request
    /*****************************************************/
    public function edit(Request $request, $id = null) {
        $data['page_title']  = 'Edit Zone';
        $data['panel_title'] = 'Edit Zone';
        try
        {           
            $pageNo = Session::get('pageNo') ? Session::get('pageNo') : '';
            $data['pageNo'] = $pageNo;
            
            // $bankList = User::whereUserParrentId('0')->whereUserType('1')->orderBy('full_name', 'asc')->get();
            $zoneDetails = User::find($id);
            $data['id'] = $id;

            if ($request->isMethod('POST')) {
               // dd($request->all());
                $zoneDetails = User::find($request->user_id);
                if ($zoneDetails == null) {
                    return redirect()->route('admin.zone.list');
                }
                $validationCondition = array(
                    'full_name'     => 'required|min:2|max:255',
                    
                    'phone_no'      => 'required|regex:/^[0-9]\d*(\.\d+)?$/',
                    'address'       => 'required|min:2|max:255',
                );
                $validationMessages = array(
                    'full_name.required'    => 'Please enter name',
                    'full_name.min'         => 'Name should be at least 2 characters',
                    'full_name.max'         => 'Name should not be more than 255 characters',
                    'phone_no.required'     => 'Sorry!! Phone number is required',
                    'phone_no.regex'        => 'Sorry!! Only number required',
                    'address.required'      => 'Please enter address',
                    'address.min'           => 'Address should be at least 2 characters',
                    'address.max'           => 'Address should not be more than 255 characters',
                );

                
                $Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
                if ($Validator->fails()) {
                    return redirect()->back()->withErrors($Validator)->withInput();
                } else {
                  
  
                        $zoneDetails->full_name         = isset($request->full_name) ? $request->full_name : NULL;
                        $zoneDetails->email             = isset($request->email) ? $request->email : NULL; 
                        $zoneDetails->phone_no          =   isset($request->phone_no) ? $request->phone_no : NULL;
                        $saveBank = $zoneDetails->save();

                    if ($saveBank) {

                        $newZoneDetails = UserDetails::whereUserId($request->user_id)->first();

                        $image = $request->file('profile_image');
                            if ($image != '') {
                            $originalFileNameCat = $image->getClientOriginalName();
                            $extension = pathinfo($originalFileNameCat, PATHINFO_EXTENSION);
                            $filename = 'member_'.strtotime(date('Y-m-d H:i:s')).'.'.$extension;
                            $image_resize = Image::make($image->getRealPath());
                            $image_resize->save(public_path('uploads/member/' . $filename));

                            $newZoneDetails->profile_image = $filename;
                       
                        }                       
                      
                        $newZoneDetails->address        = isset($request->address) ? $request->address : NULL;
                        $saveBankDetails                = $newZoneDetails->save();

                        $request->session()->flash('alert-success', 'Zone has been updated successfully');
                        return redirect()->route('admin.zone.list', ['page' => $pageNo]);
                    } else {
                        $request->session()->flash('alert-danger', 'An error occurred while updating the state');
                        return redirect()->back();
                    }
                }
            }
            
            
            return view('admin.zone_user.edit')->with(['zoneUserDetails' => $zoneDetails, 'data' => $data]);

        } catch (Exception $e) {
            return redirect()->route('admin.zone.list')->with('error', $e->getMessage());
        }
    }

    /*****************************************************/
    # ZoneController
    # Function name : Status
    # Author        :
    # Created Date  : 24-09-2020
    # Purpose       : Changing status for Zone users
    # Params        : Request $request
    /*****************************************************/
    public function status(Request $request, $id = null)
    {
        try
        {
            if ($id == null) {
                return redirect()->route('admin.zone.list');
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
                return redirect()->route('admin.zone.list')->with('error', 'Invalid state');
            }
        } catch (Exception $e) {
            return redirect()->route('admin.zone.list')->with('error', $e->getMessage());
        }
    }

    
    /*****************************************************/
    # ZoneController
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
    
}
