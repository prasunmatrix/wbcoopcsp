<?php
namespace App\Http\Helpers;

use Auth;

use \App\Cms;
use \App\SiteSetting;
use \App\Contract;
use \Illuminate\Support\Facades\Session;
use App\Http\Helpers\NotificationHelper;
use DB;

class Helper
{
    public const NO_IMAGE_USER = 'user_img.jpg'; // Thumb no image user

    public const NO_IMAGE_THUMB = 'no_image_thumb.jpg'; // Thumb no image

    public const NO_IMAGE = 'no-image.png'; // No image

    public const WEBSITE_DEFAULT_LANGUAGE = 'en';

    public const WEBITE_LANGUAGES = ['en']; // Admin language array

    public const UPLOADED_PROFILE_IMAGE_FILE_TYPES = ['jpeg', 'jpg', 'png', 'svg']; //Uploaded image file types

    public const UPLOADED_DOC_FILE_TYPES = ['dwg','doc', 'docx', 'xls', 'xlsx', 'pdf', 'txt', 'ods', 'odp', 'odt','jpeg', 'jpg', 'png', 'svg']; //Uploaded document file types
    public const MAX_UPLOAD_SIZE = 10120; // profile image upload max size (10mb)
    

    /*****************************************************/
    # Helper
    # Function name : getAppName
    # Author        :
    # Created Date  : 23-07-2020
    # Purpose       : Generate random otp
    # Params        :
    /*****************************************************/
    public static function getAppName()
    {
        //$getAppName = env('APP_NAME');
        $siteSettings = self::getSiteSettings();
        $appName = $siteSettings->website_title;
        return $appName;
    }
    
    /*****************************************************/
    # Helper
    # Function name : getAppNameFirstLetters
    # Author        :
    # Created Date  : 23-07-2020
    # Purpose       : Generate random otp
    # Params        :
    /*****************************************************/
    public static function getAppNameFirstLetters()
    {
        //$getAppName = env('APP_NAME');
        $siteSettings = self::getSiteSettings();
        $getAppName = $siteSettings->website_title;
        $explodedAppNamewords = explode(' ', $getAppName);
        $appLetters = '';
        foreach ($explodedAppNamewords as $letter) {
            $appLetters .= $letter[0];
        }
        return $appLetters;
    }

    /*****************************************************/
    # Helper
    # Function name : generateUniqueSlug
    # Author        :
    # Created Date  : 23-07-2020
    # Purpose       : Generate random otp
    # Params        :
    /*****************************************************/
    public static function generateUniqueSlug($model, $slug, $id = null)
    {
        $slug = str_slug($slug);
        $currentSlug = '';
        if ($id) {
            $currentSlug = $model->where('id', '=', $id)->value('slug');
        }

        if ($currentSlug && $currentSlug === $slug) {
            return $slug;
        } else {
            $slugList = $model->where('slug', 'LIKE', $slug . '%')->pluck('slug');
            if ($slugList->count() > 0) {
                $slugList = $slugList->toArray();
                if (!in_array($slug, $slugList)) {
                    return $slug;
                }
                $newSlug = '';
                for ($i = 1; $i <= count($slugList); $i++) {
                    $newSlug = $slug . '-' . $i;
                    if (!in_array($newSlug, $slugList)) {
                        return $newSlug;
                    }
                }
                return $newSlug;
            } else {
                return $slug;
            }
        }
    }

    /*****************************************************/
    # Helper
    # Function name : getSiteSettings
    # Author        :
    # Created Date  : 12-07-2020
    # Purpose       : Generate unique slug for product, category and so on
    # Params        :
    /*****************************************************/
    public static function getSiteSettings()
    {
        $siteSettingData = SiteSetting::first();
        return $siteSettingData;
    }

    /*****************************************************/
    # Helper
    # Function name : generateOtp
    # Author        :
    # Created Date  : 23-07-2020
    # Purpose       : Generate random otp
    # Params        :
    /*****************************************************/
    public static function generateOtp()
    {
        $string = '0123456789';
        $string_shuffled = str_shuffle($string);
        $OTP = substr($string_shuffled, 1, 6);
        return $OTP;
    }

    /*****************************************************/
    # Helper
    # Function name : getBaseUrl
    # Author        :
    # Created Date  : 23-07-2020
    # Purpose       : Generate random otp
    # Params        :
    /*****************************************************/
    public static function getBaseUrl()
    {
        $baseUrl = url('/');
        return $baseUrl;
    }

    /*****************************************************/
    # Helper
    # Function name : getRolePermissionPages
    # Author        :
    # Created Date  : 23-07-2020
    # Purpose       : Generate random otp
    # Params        :
    /*****************************************************/
    public static function getRolePermissionPages()
    {
        $routePermissionArray = [];
        if (Auth::guard('admin')->user()->id != '') {
            if (Auth::guard('admin')->user()->user_type != 0) {
                //$userRolePermission = Auth::guard('admin')->user()->allRolePermissionForUser;
                $userRolePermission =['site-settings'];
               // dd($userRolePermission);

                if (count($userRolePermission) > 0) {
                    foreach ($userRolePermission as $permission) {
                        if (@$permission->page != null) {
                            $routePermissionArray[] = $permission->page->routeName;
                        }
                    }
                }
            }
        }
        return $routePermissionArray;
       
    }

    /*****************************************************/
    # Helper
    # Function name : formattedDate
    # Author        :
    # Created Date  : 23-07-2020
    # Purpose       : Generate random otp
    # Params        :
    /*****************************************************/
    public static function formattedDate($getDate = null)
    {
        $formattedDate = date('dS M, Y');
        if ($getDate != null) {
            $formattedDate = date('dS M, Y', strtotime($getDate));
        }
        return $formattedDate;
    }

    /*****************************************************/
    # Helper
    # Function name : formattedDateTime
    # Author        :
    # Created Date  : 23-07-2020
    # Purpose       : Generate random otp
    # Params        :
    /*****************************************************/
    public static function formattedDateTime($getDateTime = null)
    {
        $formattedDateTime = '';
        if ($getDateTime != null) {
            $formattedDateTime = date('dS M, Y H:i', $getDateTime);
        }
        return $formattedDateTime;
    }

    /*****************************************************/
    # Helper
    # Function name : formattedDatefromTimestamp
    # Author        :
    # Created Date  : 23-07-2020
    # Purpose       : Generate random otp
    # Params        :
    /*****************************************************/
    public static function formattedDatefromTimestamp($getDateTime = null)
    {
        $formattedDateTime = '';
        if ($getDateTime != null) {
            $formattedDateTime = date('dS M, Y', $getDateTime);
        }
        return $formattedDateTime;
    }

    /*****************************************************/
    # Helper
    # Function name : formattedTimestamp
    # Author        :
    # Created Date  : 23-07-2020
    # Purpose       : Generate random otp
    # Params        :
    /*****************************************************/
    public static function formattedTimestamp($getDateTime = null)
    {
        $timestamp = '';
        if ($getDateTime != null) {
            $timestamp = \Carbon\Carbon::createFromFormat('m/d/Y', $getDateTime)->timestamp;
        }
        return $timestamp;
    }
    
    /*****************************************************/
    # Helper
    # Function name : differnceBtnTimestampDateFrmCurrentDateInDays
    # Author        :
    # Created Date  : 23-07-2020
    # Purpose       : Generate random otp
    # Params        :
    /*****************************************************/
    public static function differnceBtnTimestampDateFrmCurrentDateInDays($getDate = null)
    {
        $days = '';
        if ($getDate != null) {
            $currentDate = date('Y-m-d');
            $diff   = abs($getDate - strtotime($currentDate));
            $years  = floor($diff / (365*60*60*24)); 
            $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24)); 
            $days   = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));

            if ($getDate < strtotime($currentDate)) {
                $days = '-'.$days;
            } else {
                $days = '+'.$days;
            }

        }
        return $days;
    }

    /*****************************************************/
    # Helper
    # Function name : getMetaData
    # Author        :
    # Created Date  : 23-07-2020
    # Purpose       : Generate random otp
    # Params        :
    /*****************************************************/
    public static function getMetaData($table = 'SiteSetting', $where = '')
    {
        if ($table == 'cms') {
            $metaData = Cms::select('name', 'meta_keyword', 'meta_description')->where('slug', $where)->first();
            $return['title'] = $metaData['name'];
            $return['keyword'] = $metaData['meta_keyword'];
            $return['description'] = $metaData['meta_description'];
            return $return;
        } else {
            $metaData = SiteSetting::select('default_meta_title', 'default_meta_keywords', 'default_meta_description')->first();
            $return['title'] = $metaData['default_meta_title'];
            $return['keyword'] = $metaData['default_meta_keywords'];
            $return['description'] = $metaData['default_meta_description'];
            return $return;
        }
    }

    /*****************************************************/
    # Helper
    # Function name : customEncryptionDecryption
    # Author        :
    # Created Date  : 23-07-2020
    # Purpose       : Generate random otp
    # Params        :
    /*****************************************************/
    public static function customEncryptionDecryption($string, $action = 'encrypt')
    {
        $secretKey = 'c7tpe291z';
        $secretVal = 'GfY7r512';
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $key = hash('sha256', $secretKey);
        $iv = substr(hash('sha256', $secretVal), 0, 16);

        if ($action == 'encrypt') {
            $output = base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0, $iv));
        } else if ($action == 'decrypt') {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }
        return $output;
    }
    
    /*****************************************************/
    # Helper
    # Function name : cleanString
    # Author        :
    # Created Date  : 23-07-2020
    # Purpose       : Generate random otp
    # Params        :
    /*****************************************************/        
    public static function cleanString($content) {
        $content = preg_replace("/&#?[a-z0-9]+;/i","",$content); 
        $content = preg_replace("/[\n\r]/","",$content);
        $content = strip_tags($content);
        return $content;
    }

    /*****************************************************/
    # Helper
    # Function name : cleanString
    # Author        :
    # Created Date  : 23-07-2020
    # Purpose       : Generate random otp
    # Params        :
    /*****************************************************/ 
    public static function complete_percentage($model, $table_name, $user_id){
        $pos_info =  DB::select(DB::raw('SHOW COLUMNS FROM '.$table_name));
        $base_columns = count($pos_info);
        $not_null = 0;
        foreach ($pos_info as $col){
            $not_null += app('App\\'.$model)::selectRaw('SUM(CASE WHEN '.$col->Field.' IS NOT NULL THEN 1 ELSE 0 END) AS not_null')->where('user_id', '=', $user_id)->first()->not_null;
        }
        $alter = $base_columns - 6;
        $value = $not_null / $alter *100;
        $value = round($value, 2);
        return $value;
    }


    
    /*****************************************************/
    # RangeController
    # Function name : Random String
    # Author        :
    # Created Date  : 24-09-2020
    # Purpose       : Generate Random Password
    # Params        : Request $length
    /*****************************************************/

    public static function rand_string($length) {

        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        return substr(str_shuffle($chars), 0, $length);

    }

}
