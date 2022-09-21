<?php
/*****************************************************/
# Globals
# Page/Class name   : Globals
# Author            :
# Created Date      : 10-07-2020
# Functionality     : Common values access throughout 
#                     the project
# Purpose           : for global purpose
/*****************************************************/

namespace App\Http\Helpers;

use Carbon\Carbon;
use Auth;
// DB Includes
use \App\SiteSetting;
use \App\User;


class AdminHelper
{
   public const ADMIN_USER_LIMIT     = 15;   // pagination limit for user list in admin panel
   public const ADMIN_ROLE_LIMIT     = 10;   // pagination limit for role list in admin panel   
   public const ADMIN_CMS_LIMIT      = 10;   // pagination limit for cms list in admin panel
   public const ADMIN_LIST_LIMIT     = 10;
 
   public const ADMIN_ORDER_BY         = 'created_at';
   public const ADMIN_ORDER            = 'desc';
   public const NO_IMAGE               = 'no_image_thumb.jpg';
   public const ADMIN_CATEGORY_THUMB_IMAGE_WIDTH  =  100;
   public const ADMIN_CATEGORY_THUMB_IMAGE_HEIGHT =  100;
   public const IMAGE_MAX_UPLOAD_SIZE     = 5120; // profile image upload max size (5mb);
    
   public const ADMIN_LANGUAGES      = ['EN'];// Admin language array

   /*****************************************************/
   # AdminHelper
   # Function name : paginationMessage
   # Author        : 
   # Created Date  : 10-07-2020
   # Purpose       : Get the start date of the Website admin
   #                 
   # Params        : 
   /*****************************************************/
   public static function paginationMessage($data = null)
   {
      return 'Records '.$data->firstItem().' - '.$data->lastItem().' of '.$data->total();
      //(for page {{ $allUser->currentPage() }} )
   }

   /*****************************************************/
   # AdminHelper
   # Function name : loggedUser
   # Author        : 
   # Created Date  : 10-07-2020
   # Purpose       : Get the logged user data
   #
   # Params        : 
   /*****************************************************/
   public static function loggedUser() {
      return $adminUser = Auth::guard('admin')->user();
   }

   
}