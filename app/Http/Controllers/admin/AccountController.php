<?php


/*****************************************************/
# AccountController
# Page/Class name   : AccountController
# Author            :
# Created Date      : 09-07-2020
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
use App\UserDetails;
use App\District;
use App\Block;
use App\Software;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExportUser;
use App\Exports\UsersCustomExport;



class AccountController extends Controller
{
  /*****************************************************/
  # AccountController
  # Function name : dashboard
  # Author        :
  # Created Date  : 09-07-2020
  # Purpose       : Showing all items of dashboard
  # Params        : 
  /*****************************************************/
  public function dashboard()
  {
    $data['page_title'] = 'Dashboard';
    $data['panel_title'] = 'Dashboard';

    // $data['total_guest'] =  User::where('user_type','=','G')->get()->count(); 
    // $data['total_member'] = User::where('user_type','=','M')->get()->count();
    //$data['siteSettings'] = '1',
    //dd($data);
    $loginUser = Auth::guard('admin')->user();



    if ($loginUser->user_type == 0) {

      // new query by PK for same count datatable and provider  date:21feb 2023//  
      $query = UserDetails::whereNotNull('range_id')->where('software_using', '!=', 'NULL');
      //$query = UserDetails::where('software_using','!=','NULL');
      // new query by PK for same count datatable and provider  date:21feb 2023//

      //\DB::enableQueryLog();
      // $softwareDetails = UserDetails::with(['userSoftware'])->select('*', \DB::raw('count(*) as total'))
      // ->where('software_using','!=','NULL')
      // ->groupBy('software_using')
      // ->get();

      // new query by PK for same count datatable and provider  date:21feb 2023//
      $softwareDetails = UserDetails::whereNotNull('range_id')->select('*', \DB::raw('count(*) as total'))
        ->where('software_using', '!=', 'NULL')
        ->groupBy('software_using')
        ->get();
      // new query by PK for same count datatable and provider  date:21feb 2023//
      //dd(\DB::getQueryLog());

      //$test = UserDetails::select('software_using')->where('software_using','!=','NULL')->get()->count();

      // new query by PK for same count datatable and provider  date:21feb 2023//
      $test = UserDetails::whereNotNull('range_id')->where('software_using', '!=', 'NULL')->get()->count();
      // new query by PK for same count datatable and provider  date:21feb 2023//

    } elseif ($loginUser->user_type == 1) {
      $query = UserDetails::whereBankId($loginUser->id)->whereNotNull('range_id');
      $softwareDetails = UserDetails::with(['userSoftware'])->select('*', \DB::raw('count(*) as total'))
        ->where('software_using', '!=', 'NULL')
        ->where('bank_id', $loginUser->id)
        ->groupBy('software_using')
        ->get();

      $test = UserDetails::select('software_using')->where('software_using', '!=', 'NULL')->where('bank_id', $loginUser->id)->get()->count();
    } elseif ($loginUser->user_type == 2) {
      $query = UserDetails::whereZoneId($loginUser->id)->whereNotNull('range_id');
      $softwareDetails = UserDetails::with(['userSoftware'])->select('*', \DB::raw('count(*) as total'))
        ->where('software_using', '!=', 'NULL')
        ->where('zone_id', $loginUser->id)
        ->groupBy('software_using')

        ->get();

      $test = UserDetails::select('software_using')->where('software_using', '!=', 'NULL')->where('zone_id', $loginUser->id)->get()->count();
    } elseif ($loginUser->user_type == 3) {
      $query = UserDetails::whereRangeId($loginUser->id)->whereNotNull('range_id');
      $softwareDetails = UserDetails::with(['userSoftware'])->select('*', \DB::raw('count(*) as total'))
        ->where('software_using', '!=', 'NULL')
        ->where('range_id', $loginUser->id)
        ->groupBy('software_using')

        ->get();
      $test = UserDetails::select('software_using')->where('software_using', '!=', 'NULL')->where('range_id', $loginUser->id)->get()->count();
    } elseif ($loginUser->user_type == 4) {
      $query = UserDetails::whereUserId($loginUser->id)->whereNotNull('range_id');
      $softwareDetails = '';
      $test = '';
    }
    $exists = $query->count();
    if ($exists > 0) {
      //\DB::enableQueryLog();
      $list = $query->sortable()->paginate(AdminHelper::ADMIN_LIST_LIMIT);
      //dd(\DB::getQueryLog());
      $data['list'] = $list;
    } else {
      $data['list'] = array();
    }

    $data['softwareDetails'] = $softwareDetails;
    $data['test'] = $test;



    $user = Auth::guard('admin')->user()->userProfile;
    // $UserDetails = UserDetails::whereUserId($user)->first();
    //exit();

    // dd($user);
    $usePasswordModified = Auth::guard('admin')->user()->password_updated;
    if ($usePasswordModified == 1) {
      if (Auth::guard('admin')->user()->id == 1) {
        $data['confirm_user'] = 1;
      } else {
        $data['confirm_user'] = 0;
      }

      if ((Auth::guard('admin')->user()->user_type == 4) and ($user->information_correct_verified == 0 || $user->unique_id_noted == 0 || $user->pacs_using_software == 0 || $user->pacs_uploaded_format == 0)) {

        return redirect()->route('admin.pacs.pacsAcknowledement');
      } else {
        return view('admin.account.dashboard', $data);
      }
    } else {
      \Session::put('password_changed', 'No');
      return redirect()->route('admin.change-password');
    }
  }

  /*****************************************************/
  # AccountController
  # Function name : Dashboard Destroy
  # Author        :
  # Created Date  : 29-09-2020
  # Purpose       : Destroy Popup
  # Params        : 
  /*****************************************************/

  public function dashboardDestroy()
  {
    $data['page_title'] = 'Dashboard';
    $data['panel_title'] = 'Dashboard';

    session()->forget('clicked');

    return redirect()->route('admin.dashboard');
  }

  /*****************************************************/
  # AccountController
  # Function name : editProfile
  # Author        :
  # Created Date  : 09-07-2020
  # Purpose       : Edit profile
  # Params        : 
  /*****************************************************/
  public function editProfile(Request $request)
  {
    $data['page_title'] = 'Edit Profile';
    $data['panel_title'] = 'Edit Profile';

    try {
      $adminDetail = Auth::guard('admin')->user();
      $data['details'] = $adminDetail;
      if ($request->isMethod('POST')) {

        // Checking validation
        $validationCondition = array(
          'full_name' => 'required|min:2|max:255',
          'phone_no' => 'required|regex:/^(?:[+]9)?[0-9]+$/|unique:' . (new User)->getTable() . ',phone_no,' . $adminDetail->id,
        ); // validation condition
        $validationMessages = array(
          'full_name.required' => 'Please enter first name',
          'full_name.min' => 'First name should be should be at least 2 characters',
          'full_name.max' => 'First name should not be more than 255 characters',
          'phone_no.required' => 'Please enter valid phone number',
        );
        $Validator = Validator::make($request->all(), $validationCondition, $validationMessages);

        if ($Validator->fails()) {
          return redirect()->route('admin.edit-profile')->withErrors($Validator);
        } else {
          $updateAdminData = array(
            'full_name' => $request->full_name,
            'phone_no' => $request->phone_no,
          );
          $saveAdminData = User::where('id', $adminDetail->id)->update($updateAdminData);
          if ($saveAdminData) {
            $request->session()->flash('alert-success', 'Profile data has been updated successfully');
            return redirect()->back();
          } else {
            $request->session()->flash('alert-danger', 'An error took place while updating the profile');
            return redirect()->back();
          }
        }
      }

      return view('admin.account.edit_profile', $data);
    } catch (Exception $e) {
      return redirect()->route('admin.edit-profile')->with('error', $e->getMessage());
    }
  }

  /*****************************************************/
  # AccountController
  # Function name : changePassword
  # Author        :
  # Created Date  : 09-07-2020
  # Purpose       : Edit profile
  # Params        : 
  /*****************************************************/
  public function changePassword(Request $request)
  {
    $data['page_title'] = 'Change password';
    $data['panel_title'] = 'Change password';

    try {
      if ($request->isMethod('POST')) {
        $validationCondition = array(
          'current_password' => 'required|min:8',
          'password' => 'required|regex:/^(?=.*?[a-z])(?=.*?[A-Z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/',
          'confirm_password' => 'required|regex:/^(?=.*?[a-z])(?=.*?[A-Z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/|same:password',
        );
        $validationMessages = array(
          'current_password.required' => 'Please enter current password',
          'password.required' => 'Please enter password',
          'password.regex' => 'Password should be minimum 8 characters, at least one lowercase letter, at least one uppercase letter, one number and one special character',
          'confirm_password.required' => 'Please enter confirm password',
          'confirm_password.regex' => 'Confirm password should be minimum 8 characters, at least one lowercase letter, at least one uppercase letter, one number and one special character',
          'confirm_password.same' => 'Confirm password should be same as new password',
        );
        $Validator = Validator::make($request->all(), $validationCondition, $validationMessages);
        if ($Validator->fails()) {
          return redirect()->route('admin.change-password')->withErrors($Validator);
        } else {
          $adminDetail = Auth::guard('admin')->user();
          $user_id = Auth::guard('admin')->user()->id;
          $hashed_password = $adminDetail->password;

          // check if current password matches with the saved password
          if (Hash::check($request->current_password, $hashed_password)) {
            $adminDetail->password          = $request->password;
            $adminDetail->password_updated  = '1';
            $updatePassword = $adminDetail->save();

            session()->forget('password_changed');

            if ($updatePassword) {
              $request->session()->flash('alert-success', 'Password has been updated successfully');
              return redirect()->back();
            } else {
              $request->session()->flash('alert-danger', 'An error occurred while updating the password');
              return redirect()->back();
            }
          } else {
            $request->session()->flash('alert-danger', 'Current password does not match');
            return redirect()->back();
          }
        }
      }
      return view('admin.account.change_password', $data);
    } catch (Exception $e) {
      return Redirect::Route('change_password')->with('error', $e->getMessage());
    }
  }

  /*****************************************************/
  # AccountController
  # Function name : siteSettings
  # Author        :
  # Created Date  : 09-07-2020
  # Purpose       : Edit profile
  # Params        : 
  /*****************************************************/
  public function siteSettings(Request $request)
  {
    try {
      if ($request->isMethod('POST')) {

        $validationCondition = array(
          'from_email'    => 'required|regex:/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/',
          'to_email'      => 'required|regex:/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/'
        );
        $validationMessages = array(
          'from_email.required'    => 'Please enter from email',
          'from_email.regex'       => 'Please enter a valid email',
          'to_email.required'      => 'Please enter to email',
          'to_email.regex'         => 'Please enter a valid email',
        );
        $Validator = Validator::make($request->all(), $validationCondition, $validationMessages);

        if ($Validator->fails()) {
          return redirect()->route('admin.site-settings')->withErrors($Validator);
        } else {

          $siteSettings = SiteSetting::first();
          $updateData = array(
            'from_email'               => $request->from_email,
            'to_email'                 => $request->to_email,
            'default_meta_title'       => Null,
            'default_meta_keywords'    => Null,
            'default_meta_description' => $request->default_meta_description,
            'address'                  => $request->address,
            'website_title'            => $request->website_title,
          );
          $save = SiteSetting::where('id', $siteSettings->id)->update($updateData);
          $request->session()->flash('alert-success', 'Site settings has been updated successfully');
          return redirect()->back();
        }
      }
      $data['page_title'] = 'Site Settings';
      $data['panel_title'] = 'Site Settings';

      $siteSettings = SiteSetting::first();
      if ($siteSettings != null) {
        $data['from_email']               = $siteSettings->from_email;
        $data['to_email']                 = $siteSettings->to_email;
        $data['default_meta_title']       = Null;
        $data['default_meta_keywords']    = Null;
        $data['default_meta_description'] = $siteSettings->default_meta_description;
        $data['address']                  = $siteSettings->address;
        $data['website_title']                  = $siteSettings->website_title;
      }
      return view('admin.account.site_settings')->with(['siteSettings' => $siteSettings, 'data' => $data]);
    } catch (Exception $e) {
      return redirect()->route('admin.site-settings')->with('error', $e->getMessage());
    }
  }
  public function exportUser() 
  {
    return Excel::download(new ExportUser, 'users.xlsx');
  }
  // public function exportUserCustom()
  // {
  //   //$user = UserDetails::whereNotNull('range_id')->where('software_using', '!=', 'NULL')->sortable()->get();
  //   $user=User::select('users.full_name','user_details.district_id','user_details.block','user_details.socity_type','user_details.unique_id','user_details.pacs_using_software','user_details.software_using')
  //        ->join('user_details', 'users.id', '=', 'user_details.user_id')
  //        ->whereNotNull('user_details.range_id')->where('user_details.software_using', '!=', 'NULL')->get();
  //   return Excel::download(new UsersCustomExport($user), 'users.csv');
  // }
}
