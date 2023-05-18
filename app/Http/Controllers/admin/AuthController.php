<?php

namespace App\Http\Controllers\admin;

use App;
use App\Http\Controllers\Controller;
use App\User;
use Auth;
use Helper, Hash;
use Illuminate\Http\Request;
use Redirect;
use Validator;
use View;

class AuthController extends Controller
{
  public function login(Request $request)
  {

    $data['page_title'] = 'Login';
    $data['panel_title'] = 'Login';
    if (Auth::guard('admin')->check()) {
      return redirect()->route('admin.dashboard');
    } else {
      try {
        if ($request->isMethod('POST')) {

          $validationCondition = array(
            'email' => 'required|email',
            'password' => 'required',
          );
          $Validator = Validator::make($request->all(), $validationCondition);

          if ($Validator->fails()) {

            return redirect()->route('admin.login')->withErrors($Validator);
          } else {

            if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password, 'status' => '1'])) {
              if (Auth::guard('admin')->user()->id == '') {
                Auth::guard('admin')->logout();
                $request->session()->flash('alert-danger', 'Permission denied');
                return redirect()->back();
              } else {

                $user  = \Auth::guard('admin')->user();
                $user->lastlogintime = strtotime(date('Y-m-d H:i:s'));
                $user->save();
                if ($user->password_updated == 1 and $user->user_type != 0) {
                  if ($user->user_type == 4 and ($user->userProfile->information_correct_verified == 0 || $user->userProfile->unique_id_noted == 0 || $user->userProfile->pacs_using_software == 0 || $user->userProfile->pacs_uploaded_format == 0)) {
                    \Session::put('pacs_acknowledge', 'No');
                    return redirect()->route('admin.pacs.pacsAcknowledement');
                  } else {
                    \Session::put('clicked', 'No');
                  }
                }
                return redirect()->route('admin.dashboard');
              }
            } else {
              $request->session()->flash('alert-danger', 'Invalid credentials or user inactive');
              return redirect()->back()->with($request->only('email'));
            }
          }
        }

        return view('admin.auth.login', $data);
      } catch (Exception $e) {
        $request->session()->flash('alert-danger', 'Invalid Credentials!');
        return redirect()->back();
        //return Redirect::Route('admin.login')->with('error', $e->getMessage());
      }
    }
  }


  public function logout()
  {
    if (Auth::guard('admin')->logout()) {
      return redirect()->route('admin.login');
    } else {
      return redirect()->route('admin.dashboard');
    }
  }
  public function forgetPassword(Request $request)
  {
    $data['page_title'] = 'Recover Password';
    $data['panel_title'] = 'Recover Password';
    return view('admin.auth.forgotpassword', $data);
  }
  public function postForgetPassword(Request $request)
  {
    //dd($request->all());
    $validator = Validator::make(
      $request->all(),
      [
        'resetemail'                 => 'required|email',
      ],
      [
        'resetemail.required'       => 'An email is required',
        'resetemail.email'          => 'This is an invalid email format',
      ]
    );
    if ($validator->fails()) {
      $resetEmailErr = array();
      $resetEmailErr['resetemailerror'] = $validator->errors()->first();
      return Redirect::back()
        ->withErrors($resetEmailErr)
        ->withInput();
    }
    $userEmail = trim($request->get('resetemail'));
    $user = User::where('email', $userEmail)->first();
    if ($user == null) {
      $resetEmailErr = array();
      $resetEmailErr['resetemailerror'] = 'You are not a registered user';
      return Redirect::back()
        ->withErrors($resetEmailErr)
        ->withInput();
    }
  }
}
