<?php
/*****************************************************/
# UserController
# Page/Class name   : UserController
# Author            :
# Created Date      : 10-07-2020
# Functionality     : add,edit,delete and listing  
#                     
# Purpose           : Account Management
/*****************************************************/
namespace App\Http\Controllers\admin;

// Segment Use
use App;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// Use Extended 
use Helper, AdminHelper, Image, Auth, Hash, Redirect, Validator, View;

// DB Use
use App\SiteSetting;
use App\User;
use App\Role;
use App\UserDetails;

class UserController extends Controller
{

    /*****************************************************/
    # UserController
    # Function name : subAdminList
    # Author        :
    # Created Date  : 10-07-2020
    # Purpose       : Showing subAdminList of users
    # Params        : Request $request
    /*****************************************************/
    public function subAdminList(Request $request){
        try
        {   
            //=======================Page Data==========================================            
            $data['page_title']  = 'Sub Admins';
            $data['panel_title'] = 'List Of Sub-Admins';
            $loggedUser = AdminHelper::loggedUser();
            $adminName  = $loggedUser->full_name;
            //=======================Page Data==========================================
            $allRole = Role::where('id','!=','1')->get();
            
            $data['searchText'] = $key = isset($request->searchText) ? $request->searchText : '';
            $data['role_id']  = $key1 = isset($request->role_id) ? $request->role_id : '';
            $data['dateDuration']  = $dateDuration  = isset($request->date_duration) ? $request->date_duration :'';
            
            $roleData = User::where('role_id','!=', '1')->groupBy('role_id')->get();
            $filter = new User;
           
            if ($key) {
                $filter = $filter->where(function ($q) use ($key) {
                    $q->where('phone_no', '=', $key)
                        ->orWhere('full_name', 'LIKE', '%'.$key.'%')
                        ->orWhere('email', '=', $key);
                });
            }
            if ($key1) {
                $filter = $filter->where('role_id', $key1);
            }
            else {
                if ($dateDuration != '') {
                    if (strpos($dateDuration, ' - ') !== false) {
                        $filterDateDuration = explode(" - ",$dateDuration);
                        //dd($filterDateDuration);
                        $filter = $filter->whereDate('created_at', '>=', date('Y-m-d', strtotime($filterDateDuration[0])))
                                        ->whereDate('created_at', '<=', date('Y-m-d', strtotime($filterDateDuration[1])));                    
                    }
                }
            }     
            $filter = $filter->where('role_id','=','13')
                        ->orderBy(AdminHelper::ADMIN_ORDER_BY, AdminHelper::ADMIN_ORDER)
                        ->paginate(AdminHelper::ADMIN_USER_LIMIT);
           
            //=================Return render the List Page================================
            if($filter != null) {
                return view('admin.sub_admin.list')->with([
                    'data'      => $data, 
                    'allUser'   => $filter,
                    'adminName' => $adminName,
                    'allRole'  => $allRole,
                    'roleData' => $roleData  
                ]);
            } else {
                return Redirect::back()->with('alert-danger', trans('admin.no_record'));
            }
            //=================Return render the List Page================================v    

        }catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

    }

    /*****************************************************/
    # UserController
    # Function name : addSubAdmin
    # Author        :
    # Created Date  : 23-07-2020
    # Purpose       : Add a Sub Admin
    # Params        : Request $request
    /*****************************************************/
    public function addSubAdmin(Request $request)
    {
        try
        {
            //=======================Page Data==========================================            
            $data['page_title']  = 'Sub Admins';
            $data['panel_title'] = 'List Of Sub-Admins';
            $loggedUser = AdminHelper::loggedUser();
            $adminName  = $loggedUser->full_name;
            $siteSetting = Helper::getSiteSettings();
            //=======================Page Data==========================================
            
            if ($request->isMethod('POST'))
            {
                // Checking validation
                $validationCondition = array(
                    'full_name'   => 'required|min:3|max:30',
                    'email'       => 'required|regex:/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/|unique:'.(new User)->getTable().',email',
                    'phone_no'    => 'required|regex:/^[0-9]\d*(\.\d+)?$/',
                    'password'    => 'required|regex:/^(?=.*?[a-z])(?=.*?[A-Z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/',
                    'confirm_password'  => 'required|regex:/^(?=.*?[a-z])(?=.*?[A-Z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/',
                    'role_id'           => 'required|regex:/^[0-9]\d*(\.\d+)?$/',
                ); // validation condition
                $validationMessages = array(
                    'full_name.required'   => 'Sorry!! Full name is required',
                    'full_name.min'        => 'Sorry!! Full name should have at list 3 Chat',
                    'full_name.max'        => 'Sorry!! Full name not more then 30 Char',
                    'email.required'       => 'Sorry!! Email is required',
                    'email.regex'          => 'Sorry!! Please send a valid email',
                    'phone_no.required'    => 'Sorry!! Phone number is required',
                    'phone_no.regex'       => 'Sorry!! Only number required',   
                    'password.required'    => 'Sorry!! Password is required',
                    'password.regex'       => 'Sorry!! Password should be minimum 8 characters, at least one lowercase letter, at least one uppercase letter, one number and one special character',
                    'confirm_password.required' => 'Sorry!! Confirm password is required',
                    'confirm_password.regex'    => 'Sorry!! Confirm password should be minimum 8 characters, at least one lowercase letter, at least one uppercase letter, one number and one special character',
                );
                $Validator = Validator::make($request->all(), $validationCondition, $validationMessages);

                if ($Validator->fails()) {
                    return Redirect::back()->withErrors($Validator)->withInput();
                } else {
                    if($request->password === $request->confirm_password){     
                        $newSubAdmin                 = new User;
                        $newSubAdmin->full_name      = isset($request->full_name) ? $request->full_name : NULL;
                        $newSubAdmin->email          = isset($request->email) ? $request->email : NULL; 
                        $newSubAdmin->phone_no       = isset($request->phone_no) ? $request->phone_no : NULL;
                        $newSubAdmin->password       = isset($request->password) ? Hash::make($request->password) : NULL;
                        $newSubAdmin->role_id        = isset($request->role_id) ? $request->role_id : NULL;
                        $newSubAdmin->status         = '1';
                        $saveUser = $newSubAdmin->save();
                        
                        if ($saveUser) {

                            // Send Mail to Sub Admin User with Username, Password
                            \Mail::send('email_templates.subAdmin_credential',
                            [
                                'user' => $newSubAdmin,
                                'app_config' => [
                                    'appname'       => $siteSetting->website_title,
                                    'Password'      => $request->password,  
                                    'appLink'       => Helper::getBaseUrl(),
                                    'controllerName'=> 'user',
                                ],
                            ], function ($m) use ($newSubAdmin) {
                                $m->to($newSubAdmin->email, $newSubAdmin->full_name)->subject('Sub Admin Credential');
                            }); 

                            return Redirect::route('admin.user.subAdmin.list')->with('alert-success', 'Thank you!! Sub Admin added successfully');
                            
                        } else {
                            return Redirect::back()->with('alert-danger', 'Sorry!!, Error to update in Database');
                        }
                    } else {
                        return Redirect::back()->with('alert-danger', 'Sorry!!, Password & Confirm Password not match');
                    }
                }
            } else {
                return view('admin.user.sub-admin.list')->with([
                    'data' => $data, 
                    'adminName' => $adminName  
                ]);
            }
        
        } catch (Exception $e) {
            return redirect()->route('admin.user.subAdmin.list')->with('error', $e->getMessage());
        }

    }

    /*****************************************************/
    # UsersController
    # Function name : subAdminStatus
    # Author        :
    # Created Date  : 12-07-2020
    # Purpose       : Alter the status for the user active / inactive.
    # Params        : Request $request
    /*****************************************************/
    public function subAdminStatus(Request $request, $id){
       
        try
        {   
            $userDetails = new User;

            if($id){
                $checkStatus = User::select('status')->where('id', $id)->first();
            
                if ($checkStatus->status !== NULL) {
                    
                    if($checkStatus->status == '1'){
                        $updateUser = User::where('id', $id)->update(['status' => '0']);
    
                        if($updateUser) {
                            return Redirect::back()->with('alert-success', 'Status update Successfully');
                        } else {
                            return Redirect::back()->with('alert-danger', 'Something want wrong, status not update'); 
                        }
    
                    } else {
                        $updateUser = User::where('id', $id)->update(['status' => '1']);
    
                        if($updateUser){
                            return Redirect::back()->with('alert-success', 'Status update Successfully');
                        } else{
                            return Redirect::back()->with('alert-danger', 'Something want wrong, status not update'); 
                        }
                    }
    
                } else {
                    return Redirect::back()->with('alert-danger', 'User & Status not found');
                }   
            
            } else {
                return Redirect::back()->with('alert-danger', 'Id not found');
            }
        }catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /*****************************************************/
    # UsersController
    # Function name : subAdminEdit
    # Author        :
    # Created Date  : 12-07-2020
    # Purpose       : Show edit details
    # Params        : Request $request
    /*****************************************************/
    public function subAdminEdit(Request $request, $id){
        try
        {   
            //=======================Page Data==========================================            
            $data['page_title']  = 'Edit Sub Admins';
            $data['panel_title'] = 'Sub-Admins Details';
            $loggedUser    = AdminHelper::loggedUser();
            $adminName     = $loggedUser->full_name;
            $siteSetting   = Helper::getSiteSettings();
            $noImageThumb  = AdminHelper::NO_IMAGE;
            //=======================Page Data==========================================
            
            if($id){
                $userDetails = new User;    
                $userDetails = $userDetails->where('id', $id)->first(); 
                if($userDetails != NULL) {
                    return view('admin.sub_admin.edit')->with([
                        'data'              => $data,
                        'userDetails'       => $userDetails,
                        'adminName'         => $adminName ,
                        'noImageThumb'      => $noImageThumb 
                    ]);
                } else {
                    return Redirect::back()->with('alert-danger', 'No record Found');
                }
            } else {
                return Redirect::back()->with('alert-danger', 'Something went wrong, Id not found');
            }
        }catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

    }

    /*****************************************************/
    # UsersController
    # Function name : editSubAdmin
    # Author        :
    # Created Date  : 12-07-2020
    # Purpose       : Show edit details
    # Params        : Request $request
    /*****************************************************/
    public function editSubAdmin(Request $request) {

        try
        {
            //=======================Page Data==========================================            
            $data['page_title']  = 'Sub Admins';
            $data['panel_title'] = 'List Of Sub-Admins';
            $loggedUser = AdminHelper::loggedUser();
            $adminName  = $loggedUser->full_name;
            $siteSetting = Helper::getSiteSettings();
            //=======================Page Data==========================================
            
            if ($request->isMethod('POST'))
            {
                // Checking validation
                $validationCondition = array(
                    'full_name'   => 'required|min:3|max:30',
                    'email'       => 'required|regex:/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/',
                    'phone_no'    => 'required|regex:/^[0-9]\d*(\.\d+)?$/',
                ); 
                $validationMessages = array(
                    'full_name.required'   => 'Sorry!! Full name is required',
                    'full_name.min'        => 'Sorry!! Full name should have at list 3 Chat',
                    'full_name.max'        => 'Sorry!! Full name not more then 30 Char',
                    'email.required'       => 'Sorry!! Email is required',
                    'email.regex'          => 'Sorry!! Please send a valid email',
                    'phone_no.required'    => 'Sorry!! Phone number is required',
                    'phone_no.regex'       => 'Sorry!! Only number required',
                );
                $Validator = Validator::make($request->all(), $validationCondition, $validationMessages);

                if ($Validator->fails()) {
                    return Redirect::back()->withErrors($Validator)->withInput();
                } else {
                    $newSubAdmin                 = User::where('id','=',$request->user_id)->first();
                    if($newSubAdmin != Null) {
  
                        $newSubAdmin->full_name      = isset($request->full_name) ? $request->full_name : NULL;
                        $newSubAdmin->email          = isset($request->email) ? $request->email : NULL; 
                        $newSubAdmin->phone_no       = isset($request->phone_no) ? $request->phone_no : NULL;
                        $saveUser = $newSubAdmin->save();
                        
                        if ($saveUser) {
                            return Redirect::route('admin.user.subAdmin.list')->with('alert-success', 'Thank you!! Sub Admin update successfully');
                            
                        } else {
                            return Redirect::back()->with('alert-danger', 'Sorry!!, Error to update in Database');
                        }
                    } else {
                        return Redirect::back()->with('alert-danger', 'Sorry!!, User not found');
                    }
                }
            } else {
                return view('admin.user.subAdmin.list')->with([
                    'data' => $data, 
                    'adminName' => $adminName  
                ]);
            }
        
        } catch (Exception $e) {
            return redirect()->route('admin.user.subAdmin.list')->with('error', $e->getMessage());
        }        

    }

    /*****************************************************/
    # UsersController
    # Function name : subAdminDelete
    # Author        :
    # Created Date  : 13-07-2020
    # Purpose       : Soft delete and restore the same record.
    # Params        : Request $request
    /*****************************************************/
    public function subAdminDelete(Request $request, $id){
      
        try
        {   
            $subAdminDelete = new User;

            if($id){
                $subAdminDelete = $subAdminDelete->where('id', $id)->first();
               
                if($subAdminDelete) {
                    if($subAdminDelete->deleted_at == Null){
                        $changeStatusTo = now();  //if status is 0 then change to  1
                        $massage        = 'Soft delete update Successfully';    
                    } 
                    
                    $subAdminDelete->deleted_at = $changeStatusTo;
                    $deleteStat = $subAdminDelete->save();

                    if($deleteStat){
                        return Redirect::back()->with('alert-success', $massage);
                    } else {
                        return Redirect::back()->with('alert-danger', 'Sorry!! Something want wrong not update');
                    }
                } else {
                    return Redirect::back()->with('alert-danger', trans('Sorry!! No record found'));
                }
  
            } else {
                return Redirect::back()->with('alert-danger', 'Sorry!! Id is not found');
            }
   
        }catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

    }

    
    
    // ------------------------------------For Members---------------------------------------------//

    /*****************************************************/
    # UserController
    # Function name : memberList
    # Author        :
    # Created Date  : 10-07-2020
    # Purpose       : Showing memberList of users
    # Params        : Request $request
    /*****************************************************/
    public function memberList(Request $request){
        try
        {   
            //=======================Page Data==========================================            
            $data['page_title']  = 'Member User';
            $data['panel_title'] = 'List Of Members';
            $loggedUser = AdminHelper::loggedUser();
            $adminName  = $loggedUser->full_name;
            //=======================Page Data==========================================
            $allRoles = Role::all();
            
            $data['searchText'] = $key = isset($request->searchText) ? $request->searchText : '';
            $data['audience_type'] = $key1 = isset($request->audience_type) ? $request->audience_type : '';
            $data['dateDuration']  = $dateDuration  = isset($request->date_duration) ? $request->date_duration :'';
            // dd($request);
            $filter = new User;
           
            if ($key) {
                $filter = $filter->where(function ($q) use ($key) {
                    $q->where('phone_no', '=', $key)
                        ->orWhere('full_name', 'LIKE', '%'.$key.'%')
                        ->orWhere('email', '=', $key);
                });
                
               
            } 
                if ($key1) {
                    $filter = $filter->where(function ($q1) use ($key1) {
                        $q1->where('audience_type', $key1);
                            
                    });
                }
         
            else {
                if ($dateDuration != '') {
                    if (strpos($dateDuration, ' - ') !== false) {
                        $filterDateDuration = explode(" - ",$dateDuration);
                        //dd($filterDateDuration);
                        $filter = $filter->whereDate('created_at', '>=', date('Y-m-d', strtotime($filterDateDuration[0])))
                                        ->whereDate('created_at', '<=', date('Y-m-d', strtotime($filterDateDuration[1])));                    
                    }
                }
            }     
            $filter = $filter->where('user_type','=','M')
                        ->orderBy(AdminHelper::ADMIN_ORDER_BY, AdminHelper::ADMIN_ORDER)
                        ->paginate(AdminHelper::ADMIN_USER_LIMIT);
                    // dd($filter);
           
            //=================Return render the List Page================================
            if($filter != null) {
                return view('admin.user_member.list')->with([
                    'data'      => $data, 
                    'allUser'   => $filter,
                    'adminName' => $adminName,
                    'allRoles'  => $allRoles  
                ]);
            } else {
                return Redirect::back()->with('alert-danger', trans('admin.no_record'));
            }
            //=================Return render the List Page================================v    

        }catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

    }

    /*****************************************************/
    # UsersController
    # Function name : memberEdit
    # Author        :
    # Created Date  : 12-07-2020
    # Purpose       : Show edit details
    # Params        : Request $request
    /*****************************************************/
    public function memberEdit(Request $request, $id){
        try
        {   
            //=======================Page Data==========================================            
            $data['page_title']  = 'Edit Member';
            $data['panel_title'] = 'Edit Member Details';
            $loggedUser    = AdminHelper::loggedUser();
            $adminName     = $loggedUser->full_name;
            $siteSetting   = Helper::getSiteSettings();
            $noImageThumb  = AdminHelper::NO_IMAGE;
            //=======================Page Data==========================================
            
            if($id){
                $userDetails = new User;
                $userDetails = $userDetails->where('id', $id)->first();
                $stateList = State::select('id','name')->get();
                $cityList = Cities::select('id','name')->get(); 
                
                $userDetailsData = new UserDetails;
                $userDetailsData = $userDetailsData->where('user_id', $id)->first();  
                if($userDetails != NULL && $userDetailsData != NULL) {
                    return view('admin.user_member.edit')->with([
                        'data'              => $data,
                        'userDetails'       => $userDetails,
                        'userDetailsData'   =>$userDetailsData,
                        'adminName'         => $adminName ,
                        'noImageThumb'      => $noImageThumb,
                        'stateList'         => $stateList,
                         'cityList'         => $cityList,
                    ]);
                } else {
                    return Redirect::back()->with('alert-danger', 'No record Found');
                }
            } else {
                return Redirect::back()->with('alert-danger', 'Something went wrong, Id not found');
            }
        }catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

    }

    /*****************************************************/
    # UsersController
    # Function name : editMember
    # Author        :
    # Created Date  : 12-07-2020
    # Purpose       : Show edit details
    # Params        : Request $request
    /*****************************************************/
    public function editMember(Request $request) {

        try
        {
            //=======================Page Data==========================================            
            $data['page_title']  = 'Member User';
            $data['panel_title'] = 'List Of Member User';
            $loggedUser = AdminHelper::loggedUser();
            $adminName  = $loggedUser->full_name;
            $siteSetting = Helper::getSiteSettings();
            
            //=======================Page Data==========================================
            
            if ($request->isMethod('POST'))
            {
                // Checking validation
                $validationCondition = array(
                    'full_name'   => 'required|min:3|max:30',
                    'email'       => 'required|regex:/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/',
                    'phone_no'    => 'required|regex:/^[0-9]\d*(\.\d+)?$/',
                    
                ); 
                $validationMessages = array(
                    'full_name.required'   => 'Sorry!! Full name is required',
                    'full_name.min'        => 'Sorry!! Full name should have at list 3 Char',
                    'full_name.max'        => 'Sorry!! Full name not more then 30 Char',
                    'email.required'       => 'Sorry!! Email is required',
                    'email.regex'          => 'Sorry!! Please send a valid email',
                    'phone_no.required'    => 'Sorry!! Phone number is required',
                    'phone_no.regex'       => 'Sorry!! Only number required',
                );
                $Validator = Validator::make($request->all(), $validationCondition, $validationMessages);

                if ($Validator->fails()) {
                    return Redirect::back()->withErrors($Validator)->withInput();
                } else {
                    $newSubAdmin                 = User::where('id','=',$request->user_id)->first();
                    if($newSubAdmin != Null) {
  
                        $newSubAdmin->full_name      = isset($request->full_name) ? $request->full_name : NULL;
                        $newSubAdmin->email          = isset($request->email) ? $request->email : NULL; 
                        $newSubAdmin->phone_no       = isset($request->phone_no) ? $request->phone_no : NULL;
                        $newSubAdmin->state_id       = isset($request->state_id) ? $request->state_id : NULL;
                        $newSubAdmin->city_id       = isset($request->city_id) ? $request->city_id : NULL;
                        $saveUser = $newSubAdmin->save();
                        
                        if ($saveUser) {
                            
                            $image = $request->file('profile_image');
                            if ($image != '') {
                            $originalFileNameCat = $image->getClientOriginalName();
                            $extension = pathinfo($originalFileNameCat, PATHINFO_EXTENSION);
                            $filename = 'member_'.strtotime(date('Y-m-d H:i:s')).'.'.$extension;

                            $image_resize = Image::make($image->getRealPath());
                            $image_resize->save(public_path('uploads/member/' . $filename));
                            $image_resize->save(public_path('uploads/member/thumbs/' . $filename));
                            $updateuserData = array(
                                'profile_image' => $filename,
        
                            );
                            
                       
                        }
                            $updateuserData['organisation']=trim($request->organisation, ' ');
                            $updateuserData['participant']=trim($request->participant, ' ');
                            $updateuserData['office_address']=trim($request->office_address, ' ');
                            $updateuserData['residence_address']=trim($request->residence_address, ' ');
                            $updateuserData['alternate_number']=trim($request->alternate_number, ' ');
                            $updateuserData['dob']=trim($request->dob, ' ');
                            $updateuserData['spouse_name']=trim($request->spouse_name, ' ');
                            $updateuserData['spouse_dob']=trim($request->spouse_dob, ' ');
                            $updateuserData['date_of_anniversary']=trim($request->date_of_anniversary, ' ');
                            $updateuserData['child_name']=trim($request->child_name, ' ');
                            $updateuserData['child_dob']=trim($request->child_dob, ' '); 

                            $saveUserDetails  = UserDetails::where('user_id','=',$request->user_id)->update($updateuserData);

                            return Redirect::route('admin.user.member.list')->with('alert-success', 'Thank you!! Member details update successfully');
                            
                        } else {
                            return Redirect::back()->with('alert-danger', 'Sorry!!, Error to update in Database');
                        }
                    } else {
                        return Redirect::back()->with('alert-danger', 'Sorry!!, User not found');
                    }
                }
            } else {
                return view('admin.user.member.list')->with([
                    'data' => $data, 
                    'adminName' => $adminName  
                ]);
            }
        
        } catch (Exception $e) {
            return redirect()->route('admin.user.member.list')->with('error', $e->getMessage());
        }        

    }

    /*****************************************************/
    # UsersController
    # Function name : memberDelete
    # Author        :
    # Created Date  : 13-07-2020
    # Purpose       : Soft delete and restore the same record.
    # Params        : Request $request
    /*****************************************************/
    public function memberDelete(Request $request, $id){
      
        try
        {   
            $subAdminDelete = new User;

            if($id){
                $subAdminDelete = $subAdminDelete->where('id', $id)->first();
               
                if($subAdminDelete) {
                    if($subAdminDelete->deleted_at == Null){
                        $changeStatusTo = now();  //if status is 0 then change to  1
                        $massage        = 'Soft delete update Successfully';    
                    } 
                    
                    $subAdminDelete->deleted_at = $changeStatusTo;
                    $deleteStat = $subAdminDelete->save();

                    if($deleteStat){
                        return Redirect::back()->with('alert-success', $massage);
                    } else {
                        return Redirect::back()->with('alert-danger', 'Sorry!! Something want wrong not update');
                    }
                } else {
                    return Redirect::back()->with('alert-danger', trans('Sorry!! No record found'));
                }
  
            } else {
                return Redirect::back()->with('alert-danger', 'Sorry!! Id is not found');
            }
   
        }catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

    }

    /*****************************************************/
    # UsersController
    # Function name : memberStatus
    # Author        :
    # Created Date  : 12-07-2020
    # Purpose       : Alter the status for the user active / inactive.
    # Params        : Request $request
    /*****************************************************/
    public function memberStatus(Request $request, $id){
       
        try
        {   
            $userDetails = new User;

            if($id){
                $checkStatus = User::select('status')->where('id', $id)->first();
            
                if ($checkStatus->status !== NULL) {
                    
                    if($checkStatus->status == '1'){
                        $updateUser = User::where('id', $id)->update(['status' => '0']);
    
                        if($updateUser) {
                            return Redirect::back()->with('alert-success', 'Status update Successfully');
                        } else {
                            return Redirect::back()->with('alert-danger', 'Something want wrong, status not update'); 
                        }
    
                    } else {
                        $updateUser = User::where('id', $id)->update(['status' => '1']);
    
                        if($updateUser){
                            return Redirect::back()->with('alert-success', 'Status update Successfully');
                        } else{
                            return Redirect::back()->with('alert-danger', 'Something want wrong, status not update'); 
                        }
                    }
    
                } else {
                    return Redirect::back()->with('alert-danger', 'User & Status not found');
                }   
            
            } else {
                return Redirect::back()->with('alert-danger', 'Id not found');
            }
        }catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    
    // ------------------------------------For Members---------------------------------------------//

    // ------------------------------------For Guest-----------------------------------------------//

    /*****************************************************/
    # UserController
    # Function name : guestList
    # Author        :
    # Created Date  : 10-07-2020
    # Purpose       : Showing guestList of users
    # Params        : Request $request
    /*****************************************************/
    public function guestList(Request $request){
        try
        {   
            //=======================Page Data==========================================            
            $data['page_title']  = 'Guest User';
            $data['panel_title'] = 'List Of Guests';
            $loggedUser = AdminHelper::loggedUser();
            $adminName  = $loggedUser->full_name;
            //=======================Page Data==========================================
            $allRoles = Role::all();
            
            $data['searchText'] = $key = isset($request->searchText) ? $request->searchText : '';
            $data['audience_type'] = $key1 = isset($request->audience_type) ? $request->audience_type : '';
            $data['dateDuration']  = $dateDuration  = isset($request->date_duration) ? $request->date_duration :'';
            
            $filter = new User;
           
            if ($key) {
                $filter = $filter->where(function ($q) use ($key) {
                    $q->where('phone_no', '=', $key)
                        ->orWhere('full_name', 'LIKE', '%'.$key.'%')
                        ->orWhere('email', '=', $key);
                });
            } 
            if ($key1) {
                $filter = $filter->where(function ($q1) use ($key1) {
                                       $q1->where('audience_type', $key1);
                                           
                                   });
                               }
            else {
                if ($dateDuration != '') {
                    if (strpos($dateDuration, ' - ') !== false) {
                        $filterDateDuration = explode(" - ",$dateDuration);
                        //dd($filterDateDuration);
                        $filter = $filter->whereDate('created_at', '>=', date('Y-m-d', strtotime($filterDateDuration[0])))
                                        ->whereDate('created_at', '<=', date('Y-m-d', strtotime($filterDateDuration[1])));                    
                    }
                }
            }     
            $filter = $filter->where('user_type','=','G')
                        ->orderBy(AdminHelper::ADMIN_ORDER_BY, AdminHelper::ADMIN_ORDER)
                        ->paginate(AdminHelper::ADMIN_USER_LIMIT);
           
            //=================Return render the List Page================================
            if($filter != null) {
                return view('admin.user_guest.list')->with([
                    'data'      => $data, 
                    'allUser'   => $filter,
                    'adminName' => $adminName,
                    'allRoles'  => $allRoles  
                ]);
            } else {
                return Redirect::back()->with('alert-danger', trans('admin.no_record'));
            }
            //=================Return render the List Page================================v    

        }catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

    }

    /*****************************************************/
    # UsersController
    # Function name : guestEdit
    # Author        :
    # Created Date  : 12-07-2020
    # Purpose       : Show edit details
    # Params        : Request $request
    /*****************************************************/
    public function guestEdit(Request $request, $id){
        try
        {   
            //=======================Page Data==========================================            
            $data['page_title']  = 'Edit Guest';
            $data['panel_title'] = 'Edit Guest Details';
            $loggedUser    = AdminHelper::loggedUser();
            $adminName     = $loggedUser->full_name;
            $siteSetting   = Helper::getSiteSettings();
            $noImageThumb  = AdminHelper::NO_IMAGE;
            //=======================Page Data==========================================
            
            if($id){
                $userDetails = new User;    
                $userDetails = $userDetails->where('id', $id)->first();
                $stateList = State::select('id','name')->get();
                $cityList = Cities::select('id','name')->get(); 
                if($userDetails != NULL) {
                    return view('admin.user_guest.edit')->with([
                        'data'              => $data,
                        'userDetails'       => $userDetails,
                        'adminName'         => $adminName ,
                        'noImageThumb'      => $noImageThumb,
                        'stateList'         => $stateList,
                        'cityList'          => $cityList, 
                    ]);
                } else {
                    return Redirect::back()->with('alert-danger', 'No record Found');
                }
            } else {
                return Redirect::back()->with('alert-danger', 'Something went wrong, Id not found');
            }
        }catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

    }

    /*****************************************************/
    # UsersController
    # Function name : editGuest
    # Author        :
    # Created Date  : 12-07-2020
    # Purpose       : Show edit details
    # Params        : Request $request
    /*****************************************************/
    public function editGuest(Request $request) {

        try
        {
            //=======================Page Data==========================================            
            $data['page_title']  = 'Member User';
            $data['panel_title'] = 'List Of Member User';
            $loggedUser = AdminHelper::loggedUser();
            $adminName  = $loggedUser->full_name;
            $siteSetting = Helper::getSiteSettings();
            //=======================Page Data==========================================
            
            if ($request->isMethod('POST'))
            {
                // Checking validation
                $validationCondition = array(
                    'full_name'   => 'required|min:3|max:30',
                    'email'       => 'required|regex:/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/',
                    'phone_no'    => 'required|regex:/^[0-9]\d*(\.\d+)?$/',
                ); 
                $validationMessages = array(
                    'full_name.required'   => 'Sorry!! Full name is required',
                    'full_name.min'        => 'Sorry!! Full name should have at list 3 Chat',
                    'full_name.max'        => 'Sorry!! Full name not more then 30 Char',
                    'email.required'       => 'Sorry!! Email is required',
                    'email.regex'          => 'Sorry!! Please send a valid email',
                    'phone_no.required'    => 'Sorry!! Phone number is required',
                    'phone_no.regex'       => 'Sorry!! Only number required',
                );
                $Validator = Validator::make($request->all(), $validationCondition, $validationMessages);

                if ($Validator->fails()) {
                    return Redirect::back()->withErrors($Validator)->withInput();
                } else {
                    $newSubAdmin                 = User::where('id','=',$request->user_id)->first();
                    if($newSubAdmin != Null) {
  
                        $newSubAdmin->full_name      = isset($request->full_name) ? $request->full_name : NULL;
                        $newSubAdmin->email          = isset($request->email) ? $request->email : NULL; 
                        $newSubAdmin->phone_no       = isset($request->phone_no) ? $request->phone_no : NULL;
                        $newSubAdmin->state_id       = isset($request->state_id) ? $request->state_id : NULL;
                        $newSubAdmin->city_id       = isset($request->city_id) ? $request->city_id : NULL;
                        $saveUser = $newSubAdmin->save();
                        
                        if ($saveUser) {
                            return Redirect::route('admin.user.guest.list')->with('alert-success', 'Thank you!! Guest details update successfully');
                            
                        } else {
                            return Redirect::back()->with('alert-danger', 'Sorry!!, Error to update in Database');
                        }
                    } else {
                        return Redirect::back()->with('alert-danger', 'Sorry!!, User not found');
                    }
                }
            } else {
                return view('admin.user.guest.list')->with([
                    'data' => $data, 
                    'adminName' => $adminName  
                ]);
            }
        
        } catch (Exception $e) {
            return redirect()->route('admin.user.guest.list')->with('error', $e->getMessage());
        }        

    }

    /*****************************************************/
    # UsersController
    # Function name : guestDelete
    # Author        :
    # Created Date  : 13-07-2020
    # Purpose       : Soft delete and restore the same record.
    # Params        : Request $request
    /*****************************************************/
    public function guestDelete(Request $request, $id){
      
        try
        {   
            $subAdminDelete = new User;

            if($id){
                $subAdminDelete = $subAdminDelete->where('id', $id)->first();
               
                if($subAdminDelete) {
                    if($subAdminDelete->deleted_at == Null){
                        $changeStatusTo = now();  //if status is 0 then change to  1
                        $massage        = 'Soft delete update Successfully';    
                    } 
                    
                    $subAdminDelete->deleted_at = $changeStatusTo;
                    $deleteStat = $subAdminDelete->save();

                    if($deleteStat){
                        return Redirect::back()->with('alert-success', $massage);
                    } else {
                        return Redirect::back()->with('alert-danger', 'Sorry!! Something want wrong not update');
                    }
                } else {
                    return Redirect::back()->with('alert-danger', trans('Sorry!! No record found'));
                }
  
            } else {
                return Redirect::back()->with('alert-danger', 'Sorry!! Id is not found');
            }
   
        }catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

    }

    /*****************************************************/
    # UsersController
    # Function name : guestStatus
    # Author        :
    # Created Date  : 12-07-2020
    # Purpose       : Alter the status for the user active / inactive.
    # Params        : Request $request
    /*****************************************************/
    public function guestStatus(Request $request, $id){
       
        try
        {   
            $userDetails = new User;

            if($id){
                $checkStatus = User::select('status')->where('id', $id)->first();
            
                if ($checkStatus->status !== NULL) {
                    
                    if($checkStatus->status == '1'){
                        $updateUser = User::where('id', $id)->update(['status' => '0']);
    
                        if($updateUser) {
                            return Redirect::back()->with('alert-success', 'Status update Successfully');
                        } else {
                            return Redirect::back()->with('alert-danger', 'Something want wrong, status not update'); 
                        }
    
                    } else {
                        $updateUser = User::where('id', $id)->update(['status' => '1']);
    
                        if($updateUser){
                            return Redirect::back()->with('alert-success', 'Status update Successfully');
                        } else{
                            return Redirect::back()->with('alert-danger', 'Something want wrong, status not update'); 
                        }
                    }
    
                } else {
                    return Redirect::back()->with('alert-danger', 'User & Status not found');
                }   
            
            } else {
                return Redirect::back()->with('alert-danger', 'Id not found');
            }
        }catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    // ------------------------------------For Guest---------------------------------------------//
    /*****************************************************/
    # UserController
    # Function name : pointHistory
    # Author        :
    # Created Date  : 30-07-2020
    # Purpose       : Showing memberList of users
    # Params        : Request $request
    /*****************************************************/
    public function pointHistory(Request $request, $id){
        try
        {   
            //=======================Page Data==========================================            
            $data['page_title']  = 'Point History';
            $data['panel_title'] = 'Point History';
            //=======================Page Data==========================================
            
            
            $filter = new User;     
            $filter = $filter->with('pointHistory')->where('audience_type', 'D')->get();
                    
            $user = User::where('user_type', 'M')->where('audience_type', 'D')->first();
            
           
            //=================Return render the List Page================================
            if($filter != null) {
                return view('admin.user_member.history')->with([
                    'data'      => $data, 
                    'allUser'   => $filter,
                    'user'      => $user,  
                ]);
            } else {
                return Redirect::back()->with('alert-danger', trans('admin.no_record'));
            }
            //=================Return render the List Page================================v    

        }catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

    }

    // ------------------------------------For Guest---------------------------------------------//
    /*****************************************************/
    # UserController
    # Function name : ChangeUser
    # Author        :
    # Created Date  : 14-08-2020
    # Purpose       : Showing memberList of users
    # Params        : Request $request
    /*****************************************************/

    public function guestChangeUser(Request $request, $id = null) {
        $data['page_title']  = 'Change User Type';
        $data['panel_title'] = 'Change User Type';

        try
        {           
            
            $details = User::find($id);
            $data['id'] = $id;

            if ($request->isMethod('POST')) {
                if ($id == null) {
                    return redirect()->route('admin.user.guest.list');
                }
                $validationCondition = array(
                    'user_type'         => 'required',
                    'audience_type'     => 'required',
                );
                $validationMessages = array(
                    'user_type.required'    => 'Please enter User type',
                    'audience_type.required' => 'State is Audience type',
                );
                
                $Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
                if ($Validator->fails()) {
                    return redirect()->back()->withErrors($Validator)->withInput();
                } else {
                    $details->user_type        = trim($request->user_type, ' ');
                    $details->audience_type     = $request->audience_type;
                    $save = $details->save();                        
                    if ($save) {
                        $request->session()->flash('alert-success', 'Change user has been updated successfully');
                        return redirect()->route('admin.user.guest.list');
                    } else {
                        $request->session()->flash('alert-danger', 'An error occurred while updating the change user');
                        return redirect()->back();
                    }
                }
            }
            
            
            return view('admin.user_guest.change-user')->with(['details' => $details, 'data' => $data]);

        } catch (Exception $e) {
            return redirect()->route('admin.user.guest.list')->with('error', $e->getMessage());
        }
    }
}


