<?php
/*****************************************************/
# CmsController
# Page/Class name   : CmsController
# Author            :
# Created Date      : 10-07-2020
# Functionality     : add,edit,delete and listing  
#                     
# Purpose           : Account Management
/*****************************************************/
namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// Use Extended 
use Helper, AdminHelper, Image, Auth, Hash, Redirect, Validator, View;

use \App\Cms;


class CmsController extends Controller
{
    /*****************************************************/
    # CmsController
    # Function name : showCmsPages
    # Author        :
    # Created Date  : 15-07-2020
    # Purpose       : Showing subAdminList of users
    # Params        : Request $request
    /*****************************************************/

    function showCmsPages(Request $request, $slug_name) {
        $data['page_title'] = 'CMS List';
        $data['panel_title']= 'CMS List';

        $data['showData']   = Cms::orderBy('id','asc');
        $key = $slug_name;
        if ($key) {
           
            $data['showData']->where(function ($q) use ($key) {
                $q->where('title', 'LIKE', '%' . $key . '%');
            });

        }
        $data['showData'] = $data['showData']->paginate(AdminHelper::ADMIN_CMS_LIMIT);
        //dd($data['showData']);
        return view('web.cms.view', $data);
    }
}