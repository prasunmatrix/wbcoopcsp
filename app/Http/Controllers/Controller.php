<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use \Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use \Auth;
use \Helper;
use \Illuminate\Support\Facades\Session;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    private $currentLang;
    private $setLang;

    public function __construct(Request $request)
    {
        $segmentValue = $request->segment(1);
        if ($segmentValue) {
            if (in_array($segmentValue, Helper::WEBITE_LANGUAGES)) {
                Session::put('websiteLang', '');
                Session::put('websiteLang', $segmentValue);
                \App::setLocale($segmentValue);
            } else {
                Session::put('websiteLang', '');
                Session::put('websiteLang', \App::getLocale());
                \App::setLocale(\App::getLocale());
            }
        }
    }    
}
