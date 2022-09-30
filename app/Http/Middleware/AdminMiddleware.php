<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Redirect;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!\Auth::guard('admin')->check()){
            return Redirect::route('admin.login');
        } else {
            $user = \Auth::guard('admin')->user();
            $roleId = $user->id;
            if($roleId && $roleId != 1) {
               $currentRouteName =  \Route::currentRouteName();
                //$currentPage = \App\RolePage::where('routeName', $currentRouteName)->first();
                $pageName = ucwords(str_replace(['admin.','.','-'], ['',' ',' '], $currentRouteName));
                if($user->user_type==4)
                {
                    $rolePermissionRoures = ['admin.dashboard', 'admin.dashboard-destroy', 'admin.edit-profile', 'admin.change-password', 'admin.logout', 'admin.complain.list', 'admin.complain.complain-destroy', 'admin.complain.status', 'admin.complain.add','admin.complain.edit','admin.complain.editSubmit','admin.complain.view', 'admin.complain.addSubmit', 'admin.pacs.pacsAcknowledement', 'admin.pacs.pacsAcknowledementSubmit', 'admin.pacs.editSubmit', 'admin.pacs.getPacsZone', 'admin.pacs.getPacsRange', 'admin.pacs.getPacsBlock','admin.complain.alllreply','admin.complain.viewreply','admin.complain.changestatus'];

                }
                else
                {
                    $rolePermissionRoures = ['admin.dashboard', 'admin.dashboard-destroy', 'admin.edit-profile', 'admin.change-password', 'admin.logout', 'admin.complain.list', 'admin.complain.complain-destroy', 'admin.complain.status', 'admin.complain.add', 'admin.complain.addSubmit','admin.complain.edit','admin.complain.editSubmit','admin.complain.view','admin.complain.alllreply','admin.complain.viewreply','admin.complain.changestatus','admin.range.pacs','admin.range.pacsadd','admin.range.pacsedit','admin.range.pacseditSubmit','admin.range.pacsaddSubmit','admin.range.pacsstatus','admin.range.getRangeZone','admin.range.getRangeRange','admin.range.getRangeBlock','admin.bank.pacslist','admin.bank.pacsadd','admin.bank.pacsaddSubmit','admin.bank.pacsstatus','admin.bank.pacsedit','admin.bank.pacseditSubmit'];

                }
                
                if(in_array($currentRouteName, $rolePermissionRoures)) 
                    {
                        return $next($request);    
                    } 
                else 
                    {
                        return \Redirect::route('admin.dashboard')->with('alert-danger', 'You do not have any permission to access "'.$pageName.'". Contact with super admin');
                    }
                } else {
                    return $next($request);        
                }
            } 
       
        //dd(Auth::guard('admin')->user());
        return $next($request);
    }
}