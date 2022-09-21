<?php
/*****************************************************/
# UserController
# Page/Class name   : UserController
# Author            :
# Created Date      : 20-05-2019
# Functionality     : userAuthentication, logOut, forgetPassword, resetPassword, userDetails, editProfile
# Purpose           : All User function of the API
/*****************************************************/
namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

//includes
use Auth, Response, Validator, Hash, ApiHelper, AdminHelper, Helper, Mail, Image;

// DB Models
use \App\User;
use \App\UserDetails;
use \App\Token;

class UserController extends Controller
{
    
    /*****************************************************/
    # UserController
    # Function name : userAuthentication
    # Author        :
    # Created Date  : 21-05-2020
    # Purpose       : To login a user
    # Params        : Request $request
    /*****************************************************/
    public function userAuthentication(Request $request)
    {
        try{
            $siteSetting = Helper::getSiteSettings();
            $headers = $request->header();
            
            if(isset($headers['x-access-token'])) {
                $access_token = $headers['x-access-token'][0];    
            } else {
                return Response::json(ApiHelper::generateResponseBody('AD-TE-0001#User_Login', 'Access Token is not provided', false, 100));
            }

            if(isset($headers['x-device-token'])) {
                $device_token = $headers['x-device-token'][0];
            } else {
                return Response::json(ApiHelper::generateResponseBody('AD-TE-0002#User_Login', 'Device token is not provided', false, 101));
            }
                        
            $emailOrPhone = $request->credential;
            $password = $request->password;
            
            if ($emailOrPhone && $password) {
                $credentialField = ['email' => $emailOrPhone];
                $is_email = true;
                if (is_numeric($emailOrPhone)) {
                    $is_email = false;
                    $credentialField = ['mobile_number' => $emailOrPhone];
                }
                $credentialField['password'] = $password;
                if (Auth::guard('web')->attempt($credentialField)) {
                    
                    $userData = Auth::user();
                    //=============== If not Email Id==================
                    if($is_email === false){
                        return Response::json(ApiHelper::generateResponseBody('AD-UAE-0003#User_Login',
                        [ 'massage' => 'Sorry!! Only email required'],false,204));
                    }

                    // If Email is set for the login
                    // if($is_email === true && $userData->is_email_verified != 1){
                    //     $encryptedUserData = Helper::customEncryptionDecryption($userData->id, 'encrypt');
                        
                    //     \Mail::send('email_templates.verification',
                    //     [
                    //         'user' => $userData,
                    //         'app_config' => [
                    //             'appname'       => $siteSetting->website_title,
                    //             'encryptedId'   => $encryptedUserData, 
                    //             'appLink'       => Helper::getBaseUrl(),
                    //             'controllerName'=> 'user',
                    //         ],
                    //     ], function ($m) use ($userData) {
                    //         $m->to($userData->email, $userData->full_name)->subject('Verification');
                    //     });
                        
                    //     return Response::json(ApiHelper::generateResponseBody('BB-LVE-0003#Authentication',
                    //     [   'massage'           => trans('custom.email_not_verified'),
                    //         'email_verified'    => trans('custom.email_flag'),  
                    //         'encryptedUserData' => $encryptedUserData 
                    //     ],false,204));
                    // }

                    // If mobile number is set for login
                    // if($is_email === false && $userData->is_mobile_verified != 1){
                    //     $encryptedUserData = Helper::customEncryptionDecryption($userData->id, 'encrypt');
                        
                    //     \Mail::send('email_templates.verification',
                    //     [
                    //         'user' => $userData,
                    //         'app_config' => [
                    //             'appname'       => $siteSetting->website_title,
                    //             'encryptedId'   => $encryptedUserData, 
                    //             'appLink'       => Helper::getBaseUrl(),
                    //             'controllerName'=> 'user',
                    //         ],
                    //     ], function ($m) use ($userData) {
                    //         $m->to($userData->email, $userData->full_name)->subject('Verification');
                    //     });
                    //     return Response::json(ApiHelper::generateResponseBody('BB-LVE-0003#Authentication',
                    //     [   'massage'           => trans('custom.mobile_not_verified'), 
                    //         'encryptedUserData' => $encryptedUserData 
                    //     ],false,204));
                    // } else {
                    //     return Response::json(ApiHelper::generateResponseBody('BB-LVE-0003#Authentication',
                    //     [ 'massage' => trans('custom.only_email_allow')],false,204));
                    // }
 
                    // If Status is inactive
                    if ($userData->status == '0') {
                        return Response::json(ApiHelper::generateResponseBody('AD-UAE-0003#User_Login', 'Sorry!! Inactive user, please contact with Admin', false, 204));
                    } else {
                        if($userData->temp_log_time != Null){

                            // if(($userData->temp_log_time)+900 <=now()->timestamp){
                            //     $updateUserData = User::where('auth_token', $userData->auth_token)
                            //     ->update([
                            //         'auth_token'        => NULL,
                            //         'device_token'      => NULL,
                            //         'temp_log_time'     => NULL
                            //     ]);
                            //     if($updateUserData) {
                            //         $token = \Hash::make(env('APP_KEY')).Helper::customEncryptionDecryption('_auth_token', 'encrypt');
                            //         $tokenData = new Token;
                            //         $tokenData->token = $token;
                            //         $tokenData->token_time = now()->timestamp;
                            //         $dbSuccess = $tokenData->save();
                            //         if($dbSuccess) {
                            //             return Response::json(ApiHelper::generateResponseBody('BB-LE-0001#Authentication',["massage"=>trans('custom.session_expired'), "_auth_token" => $token], true, 200));
                            //         } else {
                            //             return Response::json(ApiHelper::generateResponseBody('BB-DBE-0002#Authentication', trans('custom.gen_db_errors'), false, 204));
                            //         }
                            //     } else {
                            //         return Response::json(ApiHelper::generateResponseBody('BB-DBE-0002#Authentication', trans('custom.gen_db_errors'), false, 204));
                            //     }
                            // } else {
                                
                            // }
                            
                            //====================== Check New Devices OR Not ==========================
                            if($userData->auth_token != $access_token && $userData->device_token != $device_token) {
                                $userData->temp_log_time  = strtotime(date('Y-m-d H:i:s'));
                                $userData->auth_token     = $access_token;
                                $userData->device_token   = $device_token;
                                $dataSuccess = $userData->save();
                            }
                            //====================== Check New Devices OR Not ==========================

                            $userDetails                = [];
                            $userDetails['id']          = Helper::customEncryptionDecryption($userData->id, 'encrypt');
                            $userDetails['full_name']   = isset($userData->full_name) ? $userData->full_name : NULL;
                            $userDetails['email']       = isset($userData->email) ? $userData->email : NULL;
                            // $userDetails['is_email_verified']   = $userData->is_email_verified != null ? 'Y' : '';
                            $userDetails['phone_no'] = isset($userData->phone_no) ? $userData->phone_no : NULL;

                            if(isset($userData->user_type)) {
                                switch ($userData->user_type) {
                                    case 'G':
                                        $userDetails['user_type'] = 'Guest';
                                        break;
                                    case 'M':
                                        $userDetails['user_type'] = 'Member';
                                        break;
                                }
                            }
                            // $userDetails['is_mobile_verified']  = $userData->is_mobile_verified != null ? $userData->is_mobile_verified : '';
                            $userDetails['temp_log_time'] = isset($userData->temp_log_time) ? date("Y-m-d H:i:s", $userData->temp_log_time) : NULL;
                            $userDetails['profile_image'] = isset($userData->userProfile->profile_image) ? $userData->userProfile->profile_image : NULL;
                            $userDetails['profile_completed'] = isset($userData->userProfile->profile_completed) ? $userData->userProfile->profile_completed : NULL;
                            
                            //================ Show member points ========================
                            if(isset($userData->pointDetails->total_points)) {
                                if($userData->pointDetails->total_points != NULL && $userData->pointDetails->total_points != '') {
                                    $userDetails['total_point'] = $userData->pointDetails->total_points;  
                                } else {
                                    $userDetails['total_point'] = 0;      
                                }
                            } else {
                                $userDetails['total_point'] = 0;
                            }
                            //================ Show member points ========================
                            
                            $auth_token         = isset($userData->auth_token) ? $userData->auth_token : NULL;
                            $device_token       = isset($userData->device_token) ? $userData->device_token : NULL;

                            $appDetails['appLink']                   = Helper::getBaseUrl();
                            $appDetails['public_path_profile']       = public_path('/uploads/member/');
                            $appDetails['public_path_profile_thumb'] = public_path('/uploads/member/thumbs/');
                            $appDetails['asset_url_profile']         = url('/uploads/member/').'/';
                            $appDetails['asset_url_profile_thumb']   = url('/uploads/member/thumbs/').'/';

                            return Response::json(ApiHelper::generateResponseBody('AD-LS-0001#User_Login', [
                                'message' => 'Hello User, you are already logged in',
                                'user'          => $userDetails,
                                '_auth_token'   => $auth_token,
                                '_device_token' => $device_token,
                                'appDetails'     =>  $appDetails
                            ],true,200));

                        } else {

                            $findToken = User::where('auth_token','=',$access_token)->orWhere('device_token','=',$device_token)->first();

                            if($findToken !== NULL) {
                                return Response::json(ApiHelper::generateResponseBody('AD-DBE-0002#User_Login', 'Sorry!! One user already Logged with that Access Token or Device Token, Please generate a New Token or Device Token', false, 204));
                            } else {
                                $userData->temp_log_time        = strtotime(date('Y-m-d H:i:s'));
                                $userData->auth_token           = $access_token;
                                $userData->device_token         = $device_token;
                                $dataSuccess = $userData->save();
                                if($dataSuccess) {
                                    $userDetails         = [];
                                    $userDetails['id']   = Helper::customEncryptionDecryption($userData->id, 'encrypt');
                                    $userDetails['full_name']  = isset($userData->full_name) ? $userData->full_name : NULL;
                                    $userDetails['email'] = isset($userData->email) ? $userData->email : NULL;
                                    // $userDetails['is_email_verified']   = $userData->is_email_verified != null ? 'Y' : '';
                                    $userDetails['phone_no'] = isset($userData->phone_no) ? $userData->phone_no : NULL;
                                    // $userDetails['is_mobile_verified']  = $userData->is_mobile_verified != null ? $userData->is_mobile_verified : '';

                                    if(isset($userData->user_type)) {
                                        switch ($userData->user_type) {
                                            case 'G':
                                                $userDetails['user_type'] = 'Guest';
                                                break;
                                            case 'M':
                                                $userDetails['user_type'] = 'Member';
                                                break;
                                        }
                                    }

                                    $userDetails['temp_log_time'] = isset($userData->temp_log_time) ? date("Y-m-d H:i:s", $userData->temp_log_time) : NULL;
                                    $userDetails['profile_image'] = isset($userData->userProfile->profile_image) ? $userData->userProfile->profile_image : NULL;
                                    $userDetails['profile_completed'] = isset($userData->userProfile->profile_completed) ? $userData->userProfile->profile_completed : NULL;
                                    
                                    //================ Show member points ========================
                                    if(isset($userData->pointDetails->total_points)) {
                                        if($userData->pointDetails->total_points != NULL && $userData->pointDetails->total_points != '') {
                                            $userDetails['total_point'] = $userData->pointDetails->total_points;  
                                        } else {
                                            $userDetails['total_point'] = 0;      
                                        }
                                    } else {
                                        $userDetails['total_point'] = 0;
                                    }
                                    //================ Show member points ========================
                                    
                                    $auth_token      = isset($userData->auth_token) ? $userData->auth_token : NULL;
                                    $device_token    = isset($userData->device_token) ? $userData->device_token : NULL;
                                    
                                    $appDetails['appLink']                   = Helper::getBaseUrl();
                                    $appDetails['public_path_profile']       = public_path('/uploads/member/');
                                    $appDetails['public_path_profile_thumb'] = public_path('/uploads/member/thumbs/');
                                    $appDetails['asset_url_profile']         = url('/uploads/member/').'/';
                                    $appDetails['asset_url_profile_thumb']   = url('/uploads/member/thumbs/').'/';

                                    return Response::json(ApiHelper::generateResponseBody('AD-LS-0001#User_Login', [
                                        'message'       => 'Great!!, your are successfully logged in',
                                        'user'          => $userDetails,
                                        '_auth_token'   => $auth_token,
                                        '_device_token' => $device_token,
                                        'appDetails'    =>  $appDetails
                                    ], true, 200));
    
                                } else {
                                    return Response::json(ApiHelper::generateResponseBody('AD-DBE-0002#User_Login', 'Sorry!! something wrong going on check Database', false, 204));
                                } 
                            }
        
                        }
                    }
                } else {
                    return Response::json(ApiHelper::generateResponseBody('AD-LE-0001#User_Login', 'Sorry!! Credential mismatch', false, 204));
                }
            } else {
                return Response::json(ApiHelper::generateResponseBody('AD-LE-0001#User_Login', 'Sorry!! please provide login credential', false, 300));
            }
        }catch (Exception $errors) {
            return Response::json(ApiHelper::generateResponseBody('AD-GE-0001#General_Error',  ["errors" => $errors],false, 400));
        }
    }
    
    /*****************************************************/
    # UserController
    # Function name : logOut
    # Author        :
    # Created Date  : 21-05-2020
    # Purpose       : Logout the user
    # Params        : Request $request
    /*****************************************************/
    public function logOut(Request $request)
    {
        try{
            $userData = ApiHelper::getUserFromHeader($request);
            
            if ($userData) {
                $lastLogTime    =   $userData->temp_log_time;
                $updateUserData =   User::where('auth_token', $userData->auth_token)
                ->update([
                    'auth_token'        => NULL,
                    'device_token'      => NULL,
                    'temp_log_time'     => NULL,
                    'lastlogintime'     => $lastLogTime
                ]);
                if($updateUserData) {
                    return Response::json(ApiHelper::generateResponseBody('AD-LO-0001#Logout',["massage"=>'Logout Successfully!!'], true, 200));
                    
                } else {
                    return Response::json(ApiHelper::generateResponseBody('AD-DBE-0002#Logout', trans('custom.gen_db_errors'), false, 204));
                }
            } else {
                return Response::json(ApiHelper::generateResponseBody('AD-USE-0001#Logout', 'User not found', false, 300));
            }
        }catch (Exception $errors) {
            return Response::json(ApiHelper::generateResponseBody('AD-GE-0001#General_Error',  ["errors" => $errors],false, 400));
        }
        
    }

    /*****************************************************/
    # UserController
    # Function name : forgetPassword
    # Author        :
    # Created Date  : 21-05-2020
    # Purpose       : If request for forget password
    # Params        : Request $request
    /*****************************************************/
    public function forgetPassword(Request $request){

        try{
            $siteSetting = Helper::getSiteSettings();

            $validator = Validator::make($request->all(),
                        [
                            'email' => 'required',
                        ],
                        [
                            'email.required'    => 'Sorry!! email is required',
                        ]
                    );
            $errors = $validator->errors()->all();
            if ($errors) {
                return Response::json(ApiHelper::generateResponseBody('AD-FPWE-0001#Forget_Password', ["errors" => $errors], false, 204));
            } else {
                $is_email = User::where('email', $request->email)->first();
                if($is_email == NULL) {
                    return Response::json(ApiHelper::generateResponseBody('AD-FPWE-0001#Forget_Password', 'Sorry!! Email is not found', false, 204));
                } else {
                //    if($is_email->is_email_verified == '0'){
                //     return Response::json(ApiHelper::generateResponseBody('AD-FPWE-0001#Forget_Password', trans('custom.email_not_verified'), false, 204));
                //    } else {
                        if($is_email->status == '0'){
                            return Response::json(ApiHelper::generateResponseBody('AD-FPWE-0001#Forget_Password', 'Inactive User, please contact with admin', false, 204));
                        } else {
                            $userData       =   User::where('email', $request->email)->first();
                            $OTP            =   Helper::generateOtp();
                            $userData->otp  =   $OTP;
                            $userData->save();
                            
                            $encryptedUserData = Helper::customEncryptionDecryption($userData->id, 'encrypt');
                                    
                            \Mail::send('email_templates.reset_password',
                            [
                                'user' => $userData,
                                'app_config' => [
                                    'appname'       => $siteSetting->website_title,
                                    'encryptedId'   => $encryptedUserData,
                                    'OTP'           => $OTP,  
                                    'appLink'       => Helper::getBaseUrl(),
                                    'controllerName'=> 'user',
                                ],
                            ], function ($m) use ($userData) {
                                $m->to($userData->email, $userData->full_name)->subject('Reset Password');
                            });
                            return Response::json(ApiHelper::generateResponseBody('AD-FPWE-0001#Forget_Password',
                            [
                                'message'      => 'An OTP has been send to your register mail, please update your new password with OTP',
                                'OTP'          => $OTP,
                                'encryptedId'  => $encryptedUserData  
                            ], true, 200));
                        }
                    //}
                }
            }

        } catch (Exception $errors) {
            return Response::json(ApiHelper::generateResponseBody('BB-GE-0001#General_Error',["errors" => $errors],false, 400));
        }

    }

    /*****************************************************/
    # UserController
    # Function name : resetPassword
    # Author        :
    # Created Date  : 22-05-2020
    # Purpose       : Reset the password of the User
    # Params        : Request $request
    /*****************************************************/
    public function resetPassword(Request $request){

        try{
            $siteSetting = Helper::getSiteSettings();

            $validator = Validator::make($request->all(),
                        [
                            'password'      => 'required|regex:/^(?=.*?[a-z])(?=.*?[A-Z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/',
                            'confirm_password'  => 'required|regex:/^(?=.*?[a-z])(?=.*?[A-Z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/',
                            'encryptionId' => 'required',
                            'OTP'          => 'required',
                        ],
                        [
                            'password.required'           => 'Sorry!! Password is required',
                            'password.regex'              => 'Sorry!! Password need at list 1 Cap, 1 Number, 1 Special char and should be 8 char long',
                            'confirm_password.required'   => 'Sorry!! Confirm is required',
                            'confirm_password.regex'      => 'Sorry!! Password need at list 1 Cap, 1 Number, 1 Special char and should be 8 char long',
                            'encryptionId.required'    => 'Sorry!! encryption id is required',
                            'OTP.required'             => 'Sorry!! OTP is required',
                        ]
                    );
            $errors = $validator->errors()->all();
            if ($errors) {
                return Response::json(ApiHelper::generateResponseBody('AD-RPE-0001#Reset_Password', ["errors" => $errors], false, 204));
            } else {
                $token  =   $request->encryptionId;
                $formed_id  = Helper::customEncryptionDecryption($token, 'decrypt');
                $data       = explode("~",$formed_id);
                $userData   = User::where("id", "=", $data[0])->first();

                if($userData){
                    if($request->OTP == $userData->otp) {
                        if($request->password === $request->confirm_password){

                            $userData->password         = Hash::make($request->password);
                            $userData->temp_log_time    = Null;
                            $userData->auth_token       = Null;
                            $userData->device_token     = Null;
                            $userData->otp              = Null; 
                            $dataSuccess = $userData->save(); 

                            if($dataSuccess) {
                                return Response::json(ApiHelper::generateResponseBody('AD-RPS-0002#Reset_Password','Great!! Password reset successfully please Login',true, 200));

                            } else {
                                return Response::json(ApiHelper::generateResponseBody('AD-DBE-0003#Reset_Password','Sorry!! Something wrong going on database error',false, 204));
                            }

                        } else {
                            return Response::json(ApiHelper::generateResponseBody('AD-PMM-0004#Reset_Password','Sorry!! Password not match with Confirm password',false, 204));
                        }
                    } else {
                        return Response::json(ApiHelper::generateResponseBody('AD-OTPMM-0005#Reset_Password','Sorry!! OTP not match, please check your email onces',false, 204));
                    }
                    
                } else {
                    return Response::json(ApiHelper::generateResponseBody('AD-GE-0006#Reset_Password','Sorry!! User not found',false, 300));
                }
            }

        } catch (Exception $errors) {
            return Response::json(ApiHelper::generateResponseBody('BB-GE-0007#General_Error',["errors" => $errors],false, 400));
        }

    }
    
    /*****************************************************/
    # UserController
    # Function name : showMemberProfile
    # Author        :
    # Created Date  : 28-05-2020
    # Purpose       : User registration
    # Params        : Request $request
    /*****************************************************/
    public function showMemberProfile(Request $request){
        
        try{
            if($request->all()) {
                $validator = Validator::make($request->all(),
                    [
                        'encryptionId'       => 'required',
                    ],
                    [
                        'encryptionId.required'   => 'Encryption id is required, please check login responses',
                    ]
                );
                $errors = $validator->errors()->all();
                if ($errors) {
                    return Response::json(ApiHelper::generateResponseBody('BB-UPE-0001#Show_Member_Profile', ["errors" => $errors], false, 204));
                } else {
                    $userData = ApiHelper::getUserFromHeader($request);
                    
                    // IF user for found with header value
                    if($userData == NULL) {
                        return Response::json(ApiHelper::generateResponseBody('BB-GE-0001#Show_Member_Profile','Sorry no user found, check logged access_token & device_token',false, 204));
                    }
                    // IF user for found with header value

                    $id       = Helper::customEncryptionDecryption($request->encryptionId, 'decrypt');
                    
                    if($id == $userData->id) {
                        if($userData->status == '1') {
                            // if(($userData->temp_log_time)+900 <=now()->timestamp){
                            //     $lastLogTime    =   $userData->temp_log_time;
                            //     $updateUserData = User::where('auth_token', $userData->auth_token)
                            //     ->update([
                            //         'auth_token'        => NULL,
                            //         'device_token'      => NULL,
                            //         'temp_log_time'     => NULL,
                            //         'last_login_time'   => $lastLogTime
                            //     ]);
                            //     if($updateUserData) {
                            //         $token = \Hash::make(env('APP_KEY')).Helper::customEncryptionDecryption('_auth_token', 'encrypt');
                            //         $tokenData = new Token;
                            //         $tokenData->token = $token;
                            //         $tokenData->token_time = now()->timestamp;
                            //         $dbSuccess = $tokenData->save();
                            //         if($dbSuccess) {
                            //             return Response::json(ApiHelper::generateResponseBody('BB-UPE-0001#User_Profile',["massage"=>trans('custom.session_expired'), "_auth_token" => $token], true, 200));
                            //         } else {
                            //             return Response::json(ApiHelper::generateResponseBody('BB-DBE-0002#User_Profile', trans('custom.gen_db_errors'), false, 204));
                            //         }
                            //     } else {
                            //         return Response::json(ApiHelper::generateResponseBody('BB-DBE-0002#User_Profile', trans('custom.gen_db_errors'), false, 204));
                            //     }
                            // } else {
                                
                            // }
                            if(isset($userData->userProfile)) {
                                $userDetails = [];
                                $userDetails['full_name']     =   isset($userData->full_name)? $userData->full_name : NULL;
                                $userDetails['email_id']        =   isset($userData->email)? $userData->email : NULL;
                                $userDetails['phone_no']        =   isset($userData->phone_no)? $userData->phone_no : NULL;
                                
                                if(isset($userData->audience_type)) {
                                    switch ($userData->audience_type) {
                                        case 'A':
                                            $userDetails['audience_type'] = 'Architect';
                                            break;
                                        case 'F':
                                            $userDetails['audience_type'] = 'Fabricator';
                                            break;
                                        case 'S':
                                            $userDetails['audience_type'] = 'Student';
                                            break;
                                        case 'O':
                                            $userDetails['audience_type'] = 'Others';
                                            break;
                                        case 'D':
                                            $userDetails['audience_type'] = 'Dealer';
                                            break;
                                        case 'B':
                                            $userDetails['audience_type'] = 'Builder';
                                            break;
                                        case 'I':
                                            $userDetails['audience_type'] = 'Interior Designer';
                                            break;
                                        case 'FC':
                                            $userDetails['audience_type'] = 'Facade Consultant';
                                            break;
                                        case 'E':
                                            $userDetails['audience_type'] = 'Engineer';
                                            break;
                                        case 'CC':
                                            $userDetails['audience_type'] = 'Corporate Converter';
                                            break;
                                        case 'DS':
                                            $userDetails['audience_type'] = 'Design Star';
                                            break;
                                        case 'FS':
                                            $userDetails['audience_type'] = 'Fab Star';
                                            break;
                                        case 'SS':
                                            $userDetails['audience_type'] = 'Sign Star';
                                            break;
                                    }
                                }
                                $userDetails['profile_image']   =   isset($userData->userProfile->profile_image)? $userData->userProfile->profile_image:NULL;
                                
                                $userDetails['organisation']    =   isset($userData->userProfile->organisation)? $userData->userProfile->organisation:NULL;
                                
                                $userDetails['participant']     =   isset($userData->userProfile->participant)? $userData->userProfile->participant:NULL;
                                                                
                                $userDetails['office_address']     =   isset($userData->userProfile->office_address)? $userData->userProfile->office_address:NULL;
                                                                
                                $userDetails['residence_address']  =   isset($userData->userProfile->residence_address) ? $userData->userProfile->residence_address : NULL;
                                
                                $userDetails['alternate_number']   =   isset($userData->userProfile->alternate_number)? $userData->userProfile->alternate_number:NULL;
                                                                
                                $userDetails['dob']            =   isset($userData->userProfile->dob)? $userData->userProfile->dob:NULL;
                                                                
                                $userDetails['spouse_name']    =   isset($userData->userProfile->spouse_name)? $userData->userProfile->spouse_name:NULL;
                                                                
                                $userDetails['spouse_dob']     =   isset($userData->userProfile->spouse_dob)? $userData->userProfile->spouse_dob:NULL;
                                                                
                                $userDetails['date_of_anniversary']    =   isset($userData->userProfile->date_of_anniversary)? $userData->userProfile->date_of_anniversary:NULL;
                                
                                //============= Child name and DOB ====================
                                if(isset($userData->userProfile->child_name) && isset($userData->userProfile->child_dob)) {
                                    $child_name =  explode (",", $userData->userProfile->child_name);
                                    $child_dob  = explode (",", $userData->userProfile->child_dob);
                                    $child_details = [];
                                    $child_temp = [];
                                    for($i = 0; $i < count($child_name); $i++) {
                                        $child_temp['name'] = $child_name[$i]; 
                                        $child_temp['dob']  = $child_dob [$i];
                                        $child_details[$i]  =  $child_temp;
                                    }
                                    $userDetails['child_details'] =  $child_details;   
                                } else {

                                    $userDetails['child_details'] = 'No Child Found';
                                }
                                //============= Child name and DOB ====================
                                if(isset($userData->registration_channel)) {
                                    switch ($userData->registration_channel) {
                                        case '0':
                                            $userDetails['registration_channel'] = 'Android';
                                            break;
                                        case '1':
                                            $userDetails['registration_channel'] = 'IOS';
                                            break;
                                    }
                                }
                                //================ Show member points ========================
                                if(isset($userData->pointDetails->total_points)) {
                                    if($userData->pointDetails->total_points != NULL && $userData->pointDetails->total_points != '') {
                                        $userDetails['total_point'] = $userData->pointDetails->total_points;  
                                    } else {
                                        $userDetails['total_point'] = 0;      
                                    }
                                } else {
                                    $userDetails['total_point'] = 0;
                                }
                                //================ Show member points ========================
                                $userDetails['profile_completed'] = isset($userData->userProfile->profile_completed) ? $userData->userProfile->profile_completed : NULL;

                                $userDetails['created_at']  =    isset($userData->userProfile->created_at) ? $userData->userProfile->created_at : NULL;
                                
                                $appDetails['appLink']                   = Helper::getBaseUrl();
                                $appDetails['public_path_profile']       = public_path('/uploads/member/');
                                $appDetails['public_path_profile_thumb'] = public_path('/uploads/member/thumbs/');
                                $appDetails['asset_url_profile']         = url('/uploads/member/').'/';
                                $appDetails['asset_url_profile_thumb']   = url('/uploads/member/thumbs/').'/';
                               
                                return Response::json(ApiHelper::generateResponseBody('AD-UPS-0001#Show_Member_Profile', [
                                    'message'        =>  'Show the member profile successfully',
                                    'userProfile'    =>  $userDetails,
                                    'appDetails'     =>  $appDetails,
                                ],true,200));
                            } else {
                                return Response::json(ApiHelper::generateResponseBody('AD-UPS-0001#Show_Member_Profile', [
                                    'message' => 'Member Profile is not updated'],true,200));
                            }
                            
                        } else {
                            return Response::json(ApiHelper::generateResponseBody('BB-GE-0001#Show_Member_Profile','Sorry!! Inactive user',false, 300));
                        }
                    } else {
                        return Response::json(ApiHelper::generateResponseBody('BB-GE-0001#Show_Member_Profile','User not found',false, 300));
                    }
                }
            } else {
                return Response::json(ApiHelper::generateResponseBody('AD-GE-0001#Show_Member_Profile', 'Request data is empty',false, 400));
            }
        }catch (Exception $errors) {
            return Response::json(ApiHelper::generateResponseBody('BB-GE-0001#General_Error',  ["errors" => $errors],false, 400));
        }
    }

    /*****************************************************/
    # UserController
    # Function name : editMemberProfile
    # Author        :
    # Created Date  : 28-05-2020
    # Purpose       : User registration
    # Params        : Request $request
    /*****************************************************/
    public function editMemberProfile(Request $request){
        
        try{
            if($request->all()) {
                $validator = Validator::make($request->all(),
                    [
                        'encryptionId'       => 'required',
                    ],
                    [
                        'encryptionId.required'   => trans('custom.encryptionId_required'),
                    ]
                );
                $errors = $validator->errors()->all();
                if ($errors) {
                    return Response::json(ApiHelper::generateResponseBody('BB-EPE-0001#Edit_Member_Profile', ["errors" => $errors], false, 204));
                } else {
                    $userData = ApiHelper::getUserFromHeader($request);
                    
                    // IF user for found with header value
                    if($userData == NULL) {
                        return Response::json(ApiHelper::generateResponseBody('BB-GE-0001#Show_Member_Profile','Sorry no user found, check logged access_token & device_token',false, 204));
                    }
                    // IF user for found with header value

                    $id       = Helper::customEncryptionDecryption($request->encryptionId, 'decrypt');
                    if($id == $userData->id) {
                        if($userData->status == '1') {
                            // if(($userData->temp_log_time)+900 <=now()->timestamp){
                            //     $lastLogTime    =   $userData->temp_log_time;
                            //     $updateUserData = User::where('auth_token', $userData->auth_token)
                            //     ->update([
                            //         'auth_token'        => NULL,
                            //         'device_token'      => NULL,
                            //         'temp_log_time'     => NULL,
                            //         'last_login_time'   => $lastLogTime
                            //     ]);
                            //     if($updateUserData) {
                            //         $token = \Hash::make(env('APP_KEY')).Helper::customEncryptionDecryption('_auth_token', 'encrypt');
                            //         $tokenData = new Token;
                            //         $tokenData->token = $token;
                            //         $tokenData->token_time = now()->timestamp;
                            //         $dbSuccess = $tokenData->save();
                            //         if($dbSuccess) {
                            //             return Response::json(ApiHelper::generateResponseBody('BB-EPE-0001#Edit_Profile',["massage"=>trans('custom.session_expired'), "_auth_token" => $token], true, 200));
                            //         } else {
                            //             return Response::json(ApiHelper::generateResponseBody('BB-DBE-0002#Edit_Profile', trans('custom.gen_db_errors'), false, 204));
                            //         }
                            //     } else {
                            //         return Response::json(ApiHelper::generateResponseBody('BB-DBE-0002#Edit_Profile', trans('custom.gen_db_errors'), false, 204));
                            //     }
                            // } else {
                                
                            // }
                            $userProfile    =  UserDetails::where('user_id','=',$userData->id)->first();
                            
                            if($userProfile != NULL) {
                                $flag   =   0;
                                // If organisation have data
                                if ($request->organisation != null) {
                                    $validatorOrgan = Validator::make($request->all(),
                                                    [
                                                        'organisation' => 'min:3|max:255'
                                                    ],
                                                    [
                                                        'organisation.min' => 'Sorry!! Min 3 Char required',
                                                        'organisation.max' => 'Sorry!! Maximum 255 char allowed',
                                                    ]
                                                );
                                    $errorsOrgan = $validatorOrgan->errors()->all();
                                    if ($errorsOrgan) {
                                        return Response::json(ApiHelper::generateResponseBody('AD-EPE-0001#Edit_Member_Profile', ["errors" => $errorsOrgan], false, 204));
                                    } else {
                                        $userProfile->organisation = $request->organisation;
                                        $dbCheck = $userProfile->save();
                                        if($dbCheck) {
                                            $flag   =   1;
                                        } else {
                                            return Response::json(ApiHelper::generateResponseBody('AD-DBE-0002#Edit_Member_Profile', 'Something going wrong, DB error', false, 204));
                                        } 
                                    }
                                }
                                // If participant have data
                                if ($request->participant != null) {
                                    $validatorParticipant = Validator::make($request->all(),
                                                    [
                                                        'participant' => 'min:3|max:255'
                                                    ],
                                                    [
                                                        'participant.min' => 'Sorry!! Min 3 Char required',
                                                        'participant.max' => 'Sorry!! Maximum 255 char allowed',
                                                    ]
                                                );
                                    $errorsParticipant = $validatorParticipant->errors()->all();
                                    if ($errorsParticipant) {
                                        return Response::json(ApiHelper::generateResponseBody('AD-EPE-0002#Edit_Profile', ["errors" => $errorsParticipant], false, 204));
                                    } else {
                                        $userProfile->participant = $request->participant;
                                        $dbCheck = $userProfile->save();
                                        if($dbCheck) {
                                            $flag   =   1;
                                        } else {
                                            return Response::json(ApiHelper::generateResponseBody('AD-DBE-0002#Edit_Member_Profile', 'Something going wrong, DB error', false, 204));
                                        } 
                                    }
                                }
                                // If Phone number have data
                                if ($request->phone_no != null) {
                                    $validatorPhoneNo = Validator::make($request->all(),
                                        [
                                            'phone_no' => 'regex:/^[0-9]\d*(\.\d+)?$/|min:10|max:10|unique:'.(new User)->getTable().',phone_no',
                                        ],
                                        [
                                            'phone_no.unique'      => 'Sorry!! Phone number should be unique',
                                            'phone_no.min'         => 'Sorry!! Minimum 10 number',
                                            'phone_no.max'         => 'Sorry!! Maximum 10 number',
                                            'phone_no.regex'       => 'Sorry!! only number allowed',
                                        ]
                                    );
                                    $errorsPhoneNo = $validatorPhoneNo->errors()->all();
                                    if ($errorsPhoneNo) {
                                        return Response::json(ApiHelper::generateResponseBody('BB-EPE-0003#Edit_Member_Profile', ["errors" => $errorsPhoneNo], false, 204));
                                    } else {
                                        $userData->phone_no = $request->phone_no;
                                        $dbCheck = $userData->save();
                                        if($dbCheck) {
                                            $flag   =   1;
                                        } else {
                                            return Response::json(ApiHelper::generateResponseBody('BB-DBE-0003#Edit_Member_Profile', 'Sorry!! General Database error', false, 204));
                                        } 
                                    }
                                }
                                // If Phone number have data
                                if ($request->email != null) {
                                    $validatorEmail = Validator::make($request->all(),
                                        [
                                            'email' => 'required|regex:/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/|unique:'.(new User)->getTable().',email',
                                        ],
                                        [
                                            'email.required'           => 'Sorry Email is required',
                                            'email.unique'             => 'Sorry!! This email is already register',
                                            'email.regex'              => 'Sorry!! Should be a valid email',
                                        ]
                                    );
                                    $errorsEmail = $validatorEmail->errors()->all();
                                    if ($errorsEmail) {
                                        return Response::json(ApiHelper::generateResponseBody('BB-EPE-0004#Edit_Member_Profile', ["errors" => $errorsEmail], false, 204));
                                    } else {
                                        $userData->email = $request->email;
                                        $dbCheck = $userData->save();
                                        if($dbCheck) {
                                            $flag   =   1;
                                        } else {
                                            return Response::json(ApiHelper::generateResponseBody('BB-DBE-0004#Edit_Member_Profile', 'Sorry!! General Database error', false, 204));
                                        } 
                                    }
                                }
                                // If office_address have data
                                if ($request->office_address != null) {
                                    $validatorOfficeAddress = Validator::make($request->all(),
                                                    [
                                                        'office_address' => 'min:3|max:255'
                                                    ],
                                                    [
                                                        'office_address.min' => 'Sorry!! Min 3 Char required',
                                                        'office_address.max' => 'Sorry!! Maximum 255 char allowed',
                                                    ]
                                                );
                                    $errorsOfficeAddress = $validatorOfficeAddress->errors()->all();
                                    if ($errorsOfficeAddress) {
                                        return Response::json(ApiHelper::generateResponseBody('AD-EPE-0005#Edit_Member_Profile', ["errors" => $errorsOfficeAddress], false, 204));
                                    } else {
                                        $userProfile->office_address = $request->office_address;
                                        $dbCheck = $userProfile->save();
                                        if($dbCheck) {
                                            $flag   =   1;
                                        } else {
                                            return Response::json(ApiHelper::generateResponseBody('AD-DBE-0005#Edit_Member_Profile', 'Something going wrong, DB error', false, 204));
                                        } 
                                    }
                                }
                                // If office_address have data
                                if ($request->residence_address != null) {
                                    $validatorResidenceAddress = Validator::make($request->all(),
                                                    [
                                                        'residence_address' => 'min:3|max:255'
                                                    ],
                                                    [
                                                        'residence_address.min' => 'Sorry!! Min 3 Char required',
                                                        'residence_address.max' => 'Sorry!! Maximum 255 char allowed',
                                                    ]
                                                );
                                    $errorsResidenceAddress = $validatorResidenceAddress->errors()->all();
                                    if ($errorsResidenceAddress) {
                                        return Response::json(ApiHelper::generateResponseBody('AD-EPE-0006#Edit_Member_Profile', ["errors" => $errorsResidenceAddress], false, 204));
                                    } else {
                                        $userProfile->residence_address = $request->residence_address;
                                        $dbCheck = $userProfile->save();
                                        if($dbCheck) {
                                            $flag   =   1;
                                        } else {
                                            return Response::json(ApiHelper::generateResponseBody('AD-DBE-0006#Edit_Member_Profile', 'Something going wrong, DB error', false, 204));
                                        } 
                                    }
                                }
                                // If Alternate Number have data
                                if ($request->alternate_number != null) {
                                    $validatorAlternateNumber = Validator::make($request->all(),
                                        [
                                            'alternate_number' => 'regex:/^[0-9]\d*(\.\d+)?$/|min:10|max:10',
                                        ],
                                        [
                                            'alternate_number.min'         => 'Sorry!! Minimum 10 number',
                                            'alternate_number.max'         => 'Sorry!! Maximum 10 number',
                                            'alternate_number.regex'       => 'Sorry!! only number allowed',
                                        ]
                                    );
                                    $errorsAlternateNumber = $validatorAlternateNumber->errors()->all();
                                    if ($errorsAlternateNumber) {
                                        return Response::json(ApiHelper::generateResponseBody('BB-EPE-0007#Edit_Member_Profile', ["errors" => $errorsAlternateNumber], false, 204));
                                    } else {
                                        $userProfile->alternate_number = $request->alternate_number;
                                        $dbCheck = $userProfile->save();
                                        if($dbCheck) {
                                            $flag   =   1;
                                        } else {
                                            return Response::json(ApiHelper::generateResponseBody('BB-DBE-0007#Edit_Member_Profile', 'Something going wrong, DB error', false, 204));
                                        } 
                                    }
                                }
                                // If DOB have data
                                if ($request->dob != null) {
                                    $validatorDob = Validator::make($request->all(),
                                                    [
                                                        'dob' => 'min:10|max:15'
                                                    ],
                                                    [
                                                        'dob.min' => 'Sorry!! Min 10 Char required',
                                                        'dob.max' => 'Sorry!! Maximum 15 char allowed',
                                                    ]
                                                );
                                    $errorsDob = $validatorDob->errors()->all();
                                    if ($errorsDob) {
                                        return Response::json(ApiHelper::generateResponseBody('AD-EPE-0008#Edit_Member_Profile', ["errors" => $errorsDob], false, 204));
                                    } else {
                                        $userProfile->dob = $request->dob;
                                        $dbCheck = $userProfile->save();
                                        if($dbCheck) {
                                            $flag   =   1;
                                        } else {
                                            return Response::json(ApiHelper::generateResponseBody('AD-DBE-0008#Edit_Member_Profile', 'Something going wrong, DB error', false, 204));
                                        } 
                                    }
                                }
                                // If spouse_name have data
                                if ($request->spouse_name != null) {
                                    $validatorSpouseName = Validator::make($request->all(),
                                                    [
                                                        'spouse_name' => 'min:3|max:255'
                                                    ],
                                                    [
                                                        'spouse_name.min' => 'Sorry!! Min 3 Char required',
                                                        'spouse_name.max' => 'Sorry!! Maximum 255 char allowed',
                                                    ]
                                                );
                                    $errorsSpouseName = $validatorSpouseName->errors()->all();
                                    if ($errorsSpouseName) {
                                        return Response::json(ApiHelper::generateResponseBody('AD-EPE-0009#Edit_Member_Profile', ["errors" => $errorsSpouseName], false, 204));
                                    } else {
                                        $userProfile->spouse_name = $request->spouse_name;
                                       $dbCheck = $userProfile->save();
                                        if($dbCheck) {
                                            $flag   =   1;
                                        } else {
                                            return Response::json(ApiHelper::generateResponseBody('AD-DBE-0009#Edit_Member_Profile', 'Something going wrong, DB error', false, 204));
                                        } 
                                    }
                                }
                                // If spouse_dob have data
                                if ($request->spouse_dob != null) {
                                    $validatorSpouseDob = Validator::make($request->all(),
                                                    [
                                                        'spouse_dob' => 'min:10|max:15'
                                                    ],
                                                    [
                                                        'spouse_dob.min' => 'Sorry!! Min 10 Char required',
                                                        'spouse_dob.max' => 'Sorry!! Maximum 15 char allowed',
                                                    ]
                                                );
                                    $errorsSpouseDob = $validatorSpouseDob->errors()->all();
                                    if ($errorsSpouseDob) {
                                        return Response::json(ApiHelper::generateResponseBody('AD-EPE-00010#Edit_Member_Profile', ["errors" => $errorsSpouseDob], false, 204));
                                    } else {
                                        $userProfile->spouse_dob = $request->spouse_dob;
                                       $dbCheck = $userProfile->save();
                                        if($dbCheck) {
                                            $flag   =   1;
                                        } else {
                                            return Response::json(ApiHelper::generateResponseBody('AD-DBE-0010#Edit_Member_Profile', 'Something going wrong, DB error', false, 204));
                                        } 
                                    }
                                }
                                // If Alternate Number have data
                                if ($request->spouse_mobile != null) {
                                    $validatorSpouseMobile = Validator::make($request->all(),
                                        [
                                            'spouse_mobile' => 'regex:/^[0-9]\d*(\.\d+)?$/|min:10|max:10',
                                        ],
                                        [
                                            'spouse_mobile.min'         => 'Sorry!! Minimum 10 number',
                                            'spouse_mobile.max'         => 'Sorry!! Maximum 10 number',
                                            'spouse_mobile.regex'       => 'Sorry!! only number allowed',
                                        ]
                                    );
                                    $errorsSpouseMobile = $validatorSpouseMobile->errors()->all();
                                    if ($errorsSpouseMobile) {
                                        return Response::json(ApiHelper::generateResponseBody('BB-EPE-0007#Edit_Member_Profile', ["errors" => $errorsSpouseMobile], false, 204));
                                    } else {
                                        $userProfile->spouse_mobile = $request->spouse_mobile;
                                        $dbCheck = $userProfile->save();
                                        if($dbCheck) {
                                            $flag   =   1;
                                        } else {
                                            return Response::json(ApiHelper::generateResponseBody('AD-DBE-0007#Edit_Member_Profile', 'Something going wrong, DB error', false, 204));
                                        } 
                                    }
                                }
                                // If date_of_anniversary have data
                                if ($request->date_of_anniversary != null) {
                                    $validatorDoa = Validator::make($request->all(),
                                                    [
                                                        'date_of_anniversary' => 'min:10|max:15'
                                                    ],
                                                    [
                                                        'date_of_anniversary.min' => 'Sorry!! Min 10 Char required',
                                                        'date_of_anniversary.max' => 'Sorry!! Maximum 15 char allowed',
                                                    ]
                                                );
                                    $errorsDoa = $validatorDoa->errors()->all();
                                    if ($errorsDoa) {
                                        return Response::json(ApiHelper::generateResponseBody('AD-EPE-0011#Edit_Member_Profile', ["errors" => $errorsDoa], false, 204));
                                    } else {
                                        $userProfile->date_of_anniversary = $request->date_of_anniversary;
                                        $dbCheck = $userProfile->save();
                                        if($dbCheck) {
                                            $flag   =   1;
                                        } else {
                                            return Response::json(ApiHelper::generateResponseBody('AD-DBE-0011#Edit_Member_Profile', 'Something going wrong, DB error', false, 204));
                                        } 
                                    }
                                }
                                // If child_name have data
                                if ($request->child_name != null) {
                                    $validatorChildName = Validator::make($request->all(),
                                                    [
                                                        'child_name' => 'min:3|max:500'
                                                    ],
                                                    [
                                                        'child_name.min' => 'Sorry!! Min 11 Char required',
                                                        'child_name.max' => 'Sorry!! Maximum 500 char allowed',
                                                    ]
                                                );
                                    $errorsChildName = $validatorChildName->errors()->all();
                                    if ($errorsChildName) {
                                        return Response::json(ApiHelper::generateResponseBody('AD-EPE-0012#Edit_Member_Profile', ["errors" => $errorsChildName], false, 204));
                                    } else {
                                        $userProfile->child_name = $request->child_name;
                                        $dbCheck = $userProfile->save();
                                        if($dbCheck) {
                                            $flag   =   1;
                                        } else {
                                            return Response::json(ApiHelper::generateResponseBody('AD-DBE-0012#Edit_Member_Profile', 'Something going wrong, DB error', false, 204));
                                        } 
                                    }
                                }
                                // If child_dob have data
                                if ($request->child_dob != null) {
                                    $validatorChildDob = Validator::make($request->all(),
                                                    [
                                                        'child_dob' => 'min:10|max:500'
                                                    ],
                                                    [
                                                        'child_dob.min' => 'Sorry!! Min 11 Char required',
                                                        'child_dob.max' => 'Sorry!! Maximum 500 char allowed',
                                                    ]
                                                );
                                    $errorsChildDob = $validatorChildDob->errors()->all();
                                    if ($errorsChildDob) {
                                        return Response::json(ApiHelper::generateResponseBody('AD-EPE-0013#Edit_Member_Profile', ["errors" => $errorsChildDob], false, 204));
                                    } else {
                                        $userProfile->child_dob = $request->child_dob;
                                        $dbCheck = $userProfile->save();
                                        if($dbCheck) {
                                            $flag   =   1;
                                        } else {
                                            return Response::json(ApiHelper::generateResponseBody('AD-DBE-0013#Edit_Member_Profile', 'Something going wrong, DB error', false, 204));
                                        } 
                                    }
                                }

                                $showCompleted = Helper::complete_percentage('UserDetails','alu_user_details', $userData->id);
                                // Check Completed Profile
                                if($showCompleted > 100) {
                                    $userProfile->profile_completed = 100;
                                    $dbCheck = $userProfile->save();
                                    if($dbCheck) {
                                        $flag   =   1;
                                    } else {
                                        return Response::json(ApiHelper::generateResponseBody('AD-DBE-0013#Edit_Member_Profile', 'Something going wrong, DB error', false, 204));
                                    }
                                } else {
                                    $userProfile->profile_completed = $showCompleted;
                                    $dbCheck = $userProfile->save();
                                    if($dbCheck) {
                                        $flag   =   1;
                                    } else {
                                        return Response::json(ApiHelper::generateResponseBody('AD-DBE-0013#Edit_Member_Profile', 'Something going wrong, DB error', false, 204));
                                    }
                                }
                                
                                if($flag == 1) {
                                    return Response::json(ApiHelper::generateResponseBody('AD-DBE-0015#Edit_Member_Profile', 'Great!! Member Profile update successfully', true, 200));
                                } else {
                                    return Response::json(ApiHelper::generateResponseBody('AD-DBE-0016#Edit_Member_Profile', 'Nothing Update in profile', false, 200));
                                }

                            } else {
                                return Response::json(ApiHelper::generateResponseBody('AD-GE-0017#Edit_Member_Profile','Member profile not found',false, 300));          
                            }
                        } else {
                            return Response::json(ApiHelper::generateResponseBody('AD-GE-0018#Edit_Member_Profile','Inactive User, please contact with Admin',false, 300));
                        }
                    } else {
                        return Response::json(ApiHelper::generateResponseBody('AD-GE-0019#Edit_Member_Profile','Sorry!! User not found',false, 300));
                    }              
                }
            } else {
                return Response::json(ApiHelper::generateResponseBody('AD-GE-0020#Edit_Member_Profile', 'Sorry!! No Request found',false, 400));
            }
        }catch (Exception $errors) {
            return Response::json(ApiHelper::generateResponseBody('AD-GE-0021#General_Error',  ["errors" => $errors],false, 400));
        }
    }

    /*****************************************************/
    # UserController
    # Function name : updateProfileImage
    # Author        :
    # Created Date  : 22-07-2020
    # Purpose       : User registration
    # Params        : Request $request
    /*****************************************************/
    public function updateProfileImage(Request $request){
        
        try{
            if($request->all()) {
                $validator = Validator::make($request->all(),
                    [
                        'encryptionId'       => 'required',
                    ],
                    [
                        'encryptionId.required'   => trans('custom.encryptionId_required'),
                    ]
                );
                $errors = $validator->errors()->all();
                if ($errors) {
                    return Response::json(ApiHelper::generateResponseBody('BB-EPE-0001#Edit_Member_Profile', ["errors" => $errors], false, 204));
                } else {
                    $userData = ApiHelper::getUserFromHeader($request);
                    
                    // IF user for found with header value
                    if($userData == NULL) {
                        return Response::json(ApiHelper::generateResponseBody('BB-GE-0001#Edit_Member_Profile','Sorry no user found, check logged access_token & device_token',false, 204));
                    }
                    // IF user for found with header value
                    $id = Helper::customEncryptionDecryption($request->encryptionId, 'decrypt');
                    if($id == $userData->id) {
                        if($userData->status == '1') {
                            // if(($userData->temp_log_time)+900 <=now()->timestamp){
                            //     $lastLogTime    =   $userData->temp_log_time;
                            //     $updateUserData = User::where('auth_token', $userData->auth_token)
                            //     ->update([
                            //         'auth_token'        => NULL,
                            //         'device_token'      => NULL,
                            //         'temp_log_time'     => NULL,
                            //         'last_login_time'   => $lastLogTime
                            //     ]);
                            //     if($updateUserData) {
                            //         $token = \Hash::make(env('APP_KEY')).Helper::customEncryptionDecryption('_auth_token', 'encrypt');
                            //         $tokenData = new Token;
                            //         $tokenData->token = $token;
                            //         $tokenData->token_time = now()->timestamp;
                            //         $dbSuccess = $tokenData->save();
                            //         if($dbSuccess) {
                            //             return Response::json(ApiHelper::generateResponseBody('BB-EPE-0001#Edit_Profile',["massage"=>trans('custom.session_expired'), "_auth_token" => $token], true, 200));
                            //         } else {
                            //             return Response::json(ApiHelper::generateResponseBody('BB-DBE-0002#Edit_Profile', trans('custom.gen_db_errors'), false, 204));
                            //         }
                            //     } else {
                            //         return Response::json(ApiHelper::generateResponseBody('BB-DBE-0002#Edit_Profile', trans('custom.gen_db_errors'), false, 204));
                            //     }
                            // } else {
                                
                            // }
                            $userProfile    =  UserDetails::where('user_id','=',$userData->id)->first();
                            
                            if($userProfile != NULL) {
                                
                                $validationCondition = array(
                                    'image'         => 'mimes:jpeg,jpg,png,svg|max:'.AdminHelper::IMAGE_MAX_UPLOAD_SIZE,
                                ); 
                                $validationMessages = array(
                                    'image.mimes'   => 'Sorry!! Image type is not match with upload file',
                                    'image.max'     => 'Sorry!! File size should be under 2mb',
                                );
                                $Validator = Validator::make($request->all(), $validationCondition, $validationMessages);
                                if ($Validator->fails()) {
                                    return Response::json(ApiHelper::generateResponseBody('AD-DBE-0014#Edit_Member_Profile', 'Sorry!! Wrong file type or File size, File type should be .jpg, .png and size up tp 2mb', false, 204));
                                } else {
                                    
                                    $image = $request->file('image');
                                    $profile_completed = 0;
                                    if ($image != '') {
                                        if($userProfile->profile_image != NULL || $userProfile->profile_image !='') {
                                            
                                            $oldImageBig = public_path().'/uploads/member/'.$userProfile->profile_image;
                                            
                                            if(file_exists($oldImageBig)) {
                                                @unlink($oldImageBig);
                                            }
                                            $oldImageThumb = public_path().'/uploads/member/thumbs/'.$userProfile->profile_image;
                                            if(file_exists($oldImageThumb)) {
                                                @unlink($oldImageThumb);
                                            }
                                        }
                                       
                                        $originalFileNameCat =  $image->getClientOriginalName();
                                        $extension = pathinfo($originalFileNameCat, PATHINFO_EXTENSION);
                                        $filename = $userProfile->user_id.'_'.strtotime(date('Y-m-d H:i:s')).'.'.$extension;
                                       
                                        $image_resize = Image::make($image->getRealPath());
                                        $image_resize->save(public_path('uploads/member/' . $filename));
                                        $image_resize->resize(AdminHelper::ADMIN_CATEGORY_THUMB_IMAGE_WIDTH, AdminHelper::ADMIN_CATEGORY_THUMB_IMAGE_HEIGHT, function ($constraint) {
                                            $constraint->aspectRatio();
                                        });
                                        $image_resize->save(public_path('uploads/member/thumbs/' . $filename));

                                        $userProfile->profile_image = $filename;
                                        $dbCheck = $userProfile->save();
                                        if($dbCheck) {
                                            $flag   =   1;
                                            $profile_completed = $userProfile->profile_completed;
                                        } else {
                                            return Response::json(ApiHelper::generateResponseBody('AD-DBE-0014#Edit_Member_Profile', 'Something wrong going on, General DB Error', false, 204));
                                        } 
                                    }
                                }
                                // Check Completed Profile
                                $showCompleted = Helper::complete_percentage('UserDetails','alu_user_details', $userData->id);
                                // Check Completed Profile
                                if($showCompleted > 100) {
                                    $userProfile->profile_completed = 100;
                                    $dbCheck = $userProfile->save();
                                    if($dbCheck) {
                                        $flag   =   1;
                                        $profile_completed = $userProfile->profile_completed;
                                    } else {
                                        return Response::json(ApiHelper::generateResponseBody('AD-DBE-0013#Edit_Member_Profile', 'Something going wrong, DB error', false, 204));
                                    }
                                } else {
                                    $userProfile->profile_completed = $showCompleted;
                                    $dbCheck = $userProfile->save();
                                    if($dbCheck) {
                                        $flag   =   1;
                                        $profile_completed = $userProfile->profile_completed;
                                    } else {
                                        return Response::json(ApiHelper::generateResponseBody('AD-DBE-0013#Edit_Member_Profile', 'Something going wrong, DB error', false, 204));
                                    }
                                }

                                if($flag == 1) {
                                    //================ User details ====================
                                    $userDetails    =   [];
                                    $userDetails['id']  = $request->encryptionId;
                                    $userDetails['full_name']   = isset($userData->full_name) ? $userData->full_name : NULL;
                                    $userDetails['email']  = isset($userData->email) ? $userData->email : NULL;
                                    $userDetails['phone_no'] = isset($userData->phone_no) ? $userData->phone_no : NULL;
                                    $userDetails['temp_log_time'] = isset($userData->temp_log_time) ? date("Y-m-d H:i:s", $userData->temp_log_time) : NULL;
                                    $userDetails['profile_image'] = $userProfile->profile_image;
                                    $userDetails['profile_completed'] = $profile_completed;
                                    //================ User details ====================

                                    //================ App details ====================
                                    $appDetails['appLink']                   = Helper::getBaseUrl();
                                    $appDetails['public_path_profile']       = public_path('/uploads/member/');
                                    $appDetails['public_path_profile_thumb'] = public_path('/uploads/member/thumbs/');
                                    $appDetails['asset_url_profile']         = url('/uploads/member/').'/';
                                    $appDetails['asset_url_profile_thumb']   = url('/uploads/member/thumbs/').'/';
                                    //================ App details ====================

                                    return Response::json(ApiHelper::generateResponseBody('AD-DBE-0015#Edit_Member_Profile', [
                                        'message'    => 'Great!! Member Profile image update successfully',
                                        'user'       => $userDetails,
                                        'appDetails' => $appDetails
                                    ], true, 200));
                                } else {
                                    return Response::json(ApiHelper::generateResponseBody('AD-DBE-0016#Edit_Member_Profile', 'Nothing Update in profile', false, 200));
                                }

                            } else {
                                return Response::json(ApiHelper::generateResponseBody('AD-GE-0017#Edit_Member_Profile','Member profile not found',false, 300));          
                            }
                        } else {
                            return Response::json(ApiHelper::generateResponseBody('AD-GE-0018#Edit_Member_Profile','Inactive User, please contact with Admin',false, 300));
                        }
                    } else {
                        return Response::json(ApiHelper::generateResponseBody('AD-GE-0019#Edit_Member_Profile','Sorry!! User not found',false, 300));
                    }              
                }
            } else {
                return Response::json(ApiHelper::generateResponseBody('AD-GE-0020#Edit_Member_Profile', 'Sorry!! No Request found',false, 400));
            }
        }catch (Exception $errors) {
            return Response::json(ApiHelper::generateResponseBody('AD-GE-0021#General_Error',  ["errors" => $errors],false, 400));
        }
    }
    
    /*****************************************************/
    # UserController
    # Function name : changePassword
    # Author        :
    # Created Date  : 22-07-2020
    # Purpose       : Reset the password of the User
    # Params        : Request $request
    /*****************************************************/
    public function changePassword(Request $request){

        try{
            $siteSetting = Helper::getSiteSettings();

            $validator = Validator::make($request->all(),
                        [
                            'old_password'      => 'required|regex:/^(?=.*?[a-z])(?=.*?[A-Z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/',
                            'password'          => 'required|regex:/^(?=.*?[a-z])(?=.*?[A-Z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/',
                            'confirm_password'  => 'required|regex:/^(?=.*?[a-z])(?=.*?[A-Z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/',
                            'encryptionId'      => 'required',
                        ],
                        [
                            'old_password.required'       => 'Sorry!! Old password is required',
                            'old_password.regex'          => 'Sorry!! send a valid old password',
                            'password.required'           => 'Sorry!! Password is required',
                            'password.regex'              => 'Sorry!! send a valid password',
                            'confirm_password.required'   => 'Sorry!! Confirm password is required',
                            'confirm_password.regex'      => 'Sorry!! send a valid Confirm password',
                            'encryptionId.required'       => 'Sorry!! EncryptionId is required',
                        ]
                    );
            $errors = $validator->errors()->all();
            if ($errors) {
                return Response::json(ApiHelper::generateResponseBody('AD-CPE-0001#Change_Password', ["errors" => $errors], false, 204));
            } else {
                // $token  =   $request->encryptionId;
                // $formed_id  = Helper::customEncryptionDecryption($token, 'decrypt');
                // $data       = explode("~",$formed_id);
                // $userData   = User::where("id", "=", $data[0])->first();
                $userData = ApiHelper::getUserFromHeader($request);
                $id       = Helper::customEncryptionDecryption($request->encryptionId, 'decrypt');
                if($id == $userData->id) {
                    if($userData->status == '1'){
                        if(Hash::check($request->old_password,$userData->password)) {
                            if($request->password === $request->confirm_password){
                                if($request->password === $request->old_password) {
                                    return Response::json(ApiHelper::generateResponseBody('AD-CPE-0002#Change_Password','Sorry!! Reset password should not be the same as Old password',false, 204));
                                } else {
                                    $userData->password     = Hash::make($request->password);
                                    // $userData->auth_token   = Null;
                                    // $userData->device_token = Null;
                                    // $userData->temp_log_time    = NULL;    
                                    $dataSuccess = $userData->save(); 

                                    if($dataSuccess) {
                                        return Response::json(ApiHelper::generateResponseBody('AD-RPS-0001#Change_Password','Great!! The password has been reset',true, 200));

                                    } else {
                                        return Response::json(ApiHelper::generateResponseBody('AD-DBE-0001#Change_Password','Sorry!! General Database error',false, 204));
                                    }
                                }
                            } else {
                                return Response::json(ApiHelper::generateResponseBody('AD-CPE-0002#Change_Password','Sorry!! Confirm password is not match with Password',false, 204));
                            }
                        } else {
                            return Response::json(ApiHelper::generateResponseBody('AD-CPE-0003#Change_Password','Sorry!! Old password is not match, please check it once',false, 204));
                        }
                    } else {
                        return Response::json(ApiHelper::generateResponseBody('AD-GE-0001#General_Error','Sorry!! Inactive User, please contact with admin',false, 300));
                    }
                } else {
                    return Response::json(ApiHelper::generateResponseBody('AD-GE-0001#General_Error','Sorry!! User not found, please check',false, 300));
                }
            }

        } catch (Exception $errors) {
            return Response::json(ApiHelper::generateResponseBody('AD-GE-0001#General_Error',["errors" => $errors],false, 400));
        }

    }
    
    
    /*****************************************************/
    # UserController
    # Function name : contactAdmin
    # Author        :
    # Created Date  : 26-06-2020
    # Purpose       : Reset the password of the User
    # Params        : Request $request
    /*****************************************************/
    public function contactAdmin(Request $request){

        try{
            $siteSetting = Helper::getSiteSettings();

            $validator = Validator::make($request->all(),
                        [
                            'subject'           => 'required',
                            'body'              => 'required',
                            'encryptionId'      => 'required',
                        ],
                        [
                            'subject.required'       => 'Sorry!! Subject is required',
                            'body.required'          => 'Sorry!! Body is required',
                            'encryptionId.required'  => 'Sorry!! Encryption Id is required',
                        ]
                    );
            $errors = $validator->errors()->all();
            if ($errors) {
                return Response::json(ApiHelper::generateResponseBody('BB-CPE-0001#Contact_Us', ["errors" => $errors], false, 204));
            } else {
                // $token  =   $request->encryptionId;
                // $formed_id  = Helper::customEncryptionDecryption($token, 'decrypt');
                // $data       = explode("~",$formed_id);
                // $userData   = User::where("id", "=", $data[0])->first();
                $userData = ApiHelper::getUserFromHeader($request);
                $id       = Helper::customEncryptionDecryption($request->encryptionId, 'decrypt');
                if($id == $userData->id) {
                    if($userData->status == '1'){
                        
                        \Mail::send('email_templates.contact_us',
                            [
                                'user' => $userData,
                                'app_config' => [
                                    'appname'       => $siteSetting->website_title,
                                    'mail_body'     => $request->body, 
                                    'appLink'       => Helper::getBaseUrl(),
                                    'controllerName'=> 'user',
                                ],
                            ], function ($m) use ($userData, $request) {
                                $m->to('bendermaster@yopmail.com', 'Admin')->subject($request->subject);
                            });

                            return Response::json(ApiHelper::generateResponseBody('BB-FPWE-0001#Contact_Admin',
                            [
                                'message'  =>  'Your requirement has been send to the Admin. Action will be taken with in 48 hours',
                            ], true, 200));

                    } else {
                        return Response::json(ApiHelper::generateResponseBody('BB-GE-0001#General_Error',trans('custom.inactive_user'),false, 300));
                    }
                } else {
                    return Response::json(ApiHelper::generateResponseBody('BB-GE-0001#General_Error',trans('custom.user_not_found'),false, 300));
                }
            }

        } catch (Exception $errors) {
            return Response::json(ApiHelper::generateResponseBody('BB-GE-0001#General_Error',["errors" => $errors],false, 400));
        }

    }

    /*****************************************************/
    # UserController
    # Function name : pushNotificationData
    # Author        :
    # Created Date  : 26-06-2020
    # Purpose       : Reset the password of the User
    # Params        : Request $request
    /*****************************************************/
    public function pushNotificationData(Request $request){

        try{
            $siteSetting = Helper::getSiteSettings();

            $validator = Validator::make($request->all(),
                        [
                            'encryptionId'      => 'required',
                        ],
                        [
                            'encryptionId.required'  => 'Sorry!! Encryption Id is required',
                        ]
                    );
            $errors = $validator->errors()->all();
            if ($errors) {
                return Response::json(ApiHelper::generateResponseBody('BB-CPE-0001#Contact_Us', ["errors" => $errors], false, 204));
            } else {
                // $token  =   $request->encryptionId;
                // $formed_id  = Helper::customEncryptionDecryption($token, 'decrypt');
                // $data       = explode("~",$formed_id);
                // $userData   = User::where("id", "=", $data[0])->first();
                $userData = ApiHelper::getUserFromHeader($request);
                $id       = Helper::customEncryptionDecryption($request->encryptionId, 'decrypt');
                if($id == $userData->id) {
                    if($userData->status == '1'){
                        
                        $getNotification = PushNotification::select('id','user_id','notification','created_at')->where('user_id','=',$userData->id)->get();

                        return Response::json(ApiHelper::generateResponseBody('BB-PNS-0001#Push_Notification',
                        [
                            'notification'  =>  $getNotification,
                        ], true, 200));

                    } else {
                        return Response::json(ApiHelper::generateResponseBody('BB-GE-0001#General_Error',trans('custom.inactive_user'),false, 300));
                    }
                } else {
                    return Response::json(ApiHelper::generateResponseBody('BB-GE-0001#General_Error',trans('custom.user_not_found'),false, 300));
                }
            }

        } catch (Exception $errors) {
            return Response::json(ApiHelper::generateResponseBody('BB-GE-0001#General_Error',["errors" => $errors],false, 400));
        }

    }

    
}