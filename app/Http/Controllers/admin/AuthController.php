<?php

namespace App\Http\Controllers\admin;

use App;
use App\Http\Controllers\Controller;
use App\{User, PasswordReset};
use Auth;
use Helper, Hash;
use Illuminate\Http\Request;
use Redirect;
use Validator;
use View;
use Illuminate\Support\Str;

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
    $userStatus = $user->status;
    if ($userStatus == 0) {
      $resetEmailErr = array();
      $resetEmailErr['resetemailerror'] = 'Please confirm your email first';
      return Redirect::back()
        ->withErrors($resetEmailErr)
        ->withInput();
    } else {
      $token = Str::random(250);
      //dd($token);
      $passwordResetExists = PasswordReset::where('email', $userEmail)->first();
      //dd($passwordResetExists);
      if ($passwordResetExists == null) {
        PasswordReset::create([
          'email'      => $userEmail,
          'token'      => $token
        ]);
      } else {
        PasswordReset::where('email', $userEmail)->update([
          'token' => $token
        ]);
      }
      \Mail::send(
        'email_templates.password_reset',
        [
          'user' => $userEmail,
          'app_config' => [
            'token'      => $token,
            'appLink'       => Helper::getBaseUrl(),
            'controllerName' => 'user',
            'subject'       => 'A password reset request was made.',
          ],
        ],
        function ($m) use ($userEmail) {
          $m->to($userEmail)->subject('Password Reset');
        }
      );
      $request->session()->flash('alert-success', 'A password reset link has been sent to your email');
      return redirect()->route('admin.forget-password');
    }
  }
  public function getResetPassword($token)
  {
    if (is_null($token)) {
      return Redirect::to('/');
    } else {
      $token = trim($token);
      $tokenExists = PasswordReset::where('token', $token)->first();
      //dd($tokenExists);
      if ($tokenExists == null) {
        return Redirect::to('/');
      } else {
        $data['page_title'] = 'Recover Password';
        $data['panel_title'] = 'Recover Password';
        $data['tok3n'] = $token;
        return view('admin.auth.resetpassword', $data);
      }
    }
  }
  public function postResetPassword(Request $request)
  {
    $validator = Validator::make(
      $request->all(),
      [
        'password'              => 'required|confirmed|min:8',
        'tok3n'                 => 'required',
      ],
      [
        'password.min'          => 'Password must be :min chars long',
        'password.confirmed'    => 'Password & Confirm Password must be same',
        'tok3n.required'        => 'Token Missing',
      ]
    );
    if ($validator->fails()) {
      return Redirect::back()
        ->withErrors($validator)
        ->withInput();
    } else {
      $resetToken = trim($request->get('tok3n'));
      //dd($resetToken);
      $newPassword = trim($request->get('password'));
      $passwordResetExists = PasswordReset::where('token', $resetToken)->first();
      if ($passwordResetExists == null) {
        $resetPasswordErr = array();
        $resetPasswordErr['reseterror'] = 'Invalid Token';
        return Redirect::back()
          ->withErrors($resetPasswordErr)
          ->withInput();
      } else {
        $resetEmail = $passwordResetExists->email;
        //dd($resetEmail);
        $user = User::where('email', $resetEmail)->first();
        if ($user == null) {
          $resetPasswordErr = array();
          $resetPasswordErr['reseterror'] = 'You are not a registered user';
          return Redirect::back()
            ->withErrors($resetPasswordErr)
            ->withInput();
        } else {
          // $user->update([
          //   'password' => $newPassword,
          // ]);
          // $user->save();
          $newPasswordHash=Hash::make($newPassword);
          //dd($newPasswordHash);
          $userPass=User::where('email',$resetEmail)->update(['password'=>$newPasswordHash]);
          //dd($userPass);
          PasswordReset::where('email', $resetEmail)->delete();
          $request->session()->flash('alert-success', 'New Password has been set successfully');
          return redirect()->route('admin.login');
        }
      }
    }
  }
}
