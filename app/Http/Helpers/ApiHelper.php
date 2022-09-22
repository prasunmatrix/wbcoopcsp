<?php
/*****************************************************/
# ApiHelper
# Page/Class name : ApiHelper
# Author :
# Created Date : 20-07-2020
# Functionality : generateResponseBody, getUserFromHeader
# Purpose : all general function for api
/*****************************************************/
namespace App\Http\Helpers;
use Request;


class ApiHelper
{
    
    public const API_PROJECT_LIMIT  = 5;   // pagination limit for cms list in admin panel
    public const API_SOCIAL_LIMIT  = 5;   // pagination limit for cms list in admin panel
    public const API_EVENT_LIMIT  = 5;   // pagination limit for cms list in admin panel

    /*****************************************************/
    # ApiHelper
    # Function name : generateResponseBody
    # Author        :
    # Created Date  : 20-07-2020
    # Purpose       : To generate api response body
    # Params        : $code, $data, $success = true, $errorCode = null
    /*****************************************************/
    public static function generateResponseBody($code, $data, $success = true, $errorCode = null)
    {
        $result         = [];
        $collectedData  = [];
        $finalCode      = $code;

        $functionName   = null;
        
        if (strpos($code, '#') !== false) {
            $explodedCode   = explode('#',$code);
            $finalCode      = $explodedCode[0];
            $functionName   = $explodedCode[1];
        }

        $collectedData['code'] = $finalCode;
        if ($success) {
            $collectedData['status'] = '200';     //for success
        } else {
            $collectedData['status'] = '204';     //for error
            if ($errorCode) {
                $collectedData['error_code'] = $errorCode;
            }
        }
        
        if (gettype($data) === 'string') {
            $collectedData['message'] = $data;
        } else if(gettype($data) === 'array' && array_key_exists('errors',$data)){
            $collectedData['message'] = implode(",",$data['errors']);
        }else {
            $collectedData['message'] = "";
            $collectedData['details'] = $data;
        }

        if ($functionName != null) {
            $result[$functionName] = $collectedData;
        } else {
            $result = $collectedData;
        }       

        return $result;
    }

    /*****************************************************/
    # ApiHelper
    # Function name : getUserFromHeader
    # Author :
    # Created Date : 20-07-2020
    # Purpose :  to get header user
    # Params : $request
    /*****************************************************/
    public static function getUserFromHeader($request)
    {   
        
        $headers = $request->header();
        $token = $headers['x-access-token'][0];
        $userData = \App\User::where('auth_token', $token)->with('userProfile')->with('pointHistory')->first();
        return $userData;
    }

    
}