<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', function () {
    return redirect('/securepanel');
});

// CMS page view 
Route::group(['namespace' => 'web', 'prefix' => 'webview', 'as' => 'web.'], function () {
    Route::any('/show-cms-page/{slug_name}', 'CmsController@showCmsPages')->name('show-cms-page');
});

Route::group(['namespace' => 'admin', 'prefix' => 'securepanel', 'as' => 'admin.'], function () {

    Route::any('/', 'AuthController@login')->name('login');
    // Route::any('/forget-password', 'AuthController@forgetPassword')->name('forget-password');

    Route::group(['middleware' => 'admin'], function () {
        Route::any('/dashboard', 'AccountController@dashboard')->name('dashboard');
        Route::any('/dashboard-destroy', 'AccountController@dashboardDestroy')->name('dashboard-destroy');
        
        Route::any('/edit-profile', 'AccountController@editProfile')->name('edit-profile');
        Route::any('/change-password', 'AccountController@changePassword')->name('change-password');
        Route::any('/site-settings', 'AccountController@siteSettings')->name('site-settings');
        
        Route::any('/logout', 'AuthController@logout')->name('logout');

        // export user date:22/02/2023
        Route::get('/export-user', 'AccountController@exportUser')->name('export-user');
        //Route::get('/export-user-custom', 'AccountController@exportUserCustom')->name('export-user-custom');
        // export user date:22/02/2023
       
        Route::group(['prefix' => 'cms', 'as' => 'CMS.'], function () {
			Route::get('/', 'CmsController@list')->name('list');
            Route::get('/edit/{id}', 'CmsController@edit')->name('edit')->where('id','[0-9]+');
            Route::post('/edit-submit/{id}', 'CmsController@edit')->name('editsubmit')->where('id','[0-9]+');
        });       

        Route::group(['prefix' => 'role', 'as' => 'role.'], function () {
            Route::any('/', 'RoleController@list')->name('list');
            Route::post('/add-edit-action', 'RoleController@addEdit')->name('add-edit');
            
            Route::any('/permission/{roleType}', 'RoleController@permission')->name('permission');
            Route::post('/submit/{roleType}', 'RoleController@submitRolePermission')->name('submitpermission');
          
        });

        Route::group(['prefix' => 'user', 'as' => 'user.'], function () {
            Route::group(['prefix' => 'subAdmin', 'as' => 'subAdmin.'], function () {
                Route::get('/', 'UserController@subAdminList')->name('list');
                Route::get('/edit/{id}', 'UserController@subAdminEdit')->name('edit')->where('id','[0-9]+');
                Route::get('/status/{id}', 'UserController@subAdminStatus')->name('status')->where('id','[0-9]+');
                Route::get('/delete/{id}', 'UserController@subAdminDelete')->name('delete')->where('id','[0-9]+');
                Route::post('/add','UserController@addSubAdmin')->name('add');
                Route::post('/edit-submit','UserController@editSubAdmin')->name('edit-submit');
            });

            Route::group(['prefix' => 'member', 'as' => 'member.'], function () {
                Route::get('/', 'UserController@memberList')->name('list');
                Route::get('/edit/{id}', 'UserController@memberEdit')->name('edit')->where('id','[0-9]+');
                Route::get('/status/{id}', 'UserController@memberStatus')->name('status')->where('id','[0-9]+');
                Route::get('/delete/{id}', 'UserController@memberDelete')->name('delete')->where('id','[0-9]+');
                Route::post('/edit-submit','UserController@editMember')->name('edit-submit');
                Route::get('/history/{id}', 'UserController@pointHistory')->name('history')->where('id','[0-9]+');
            });

            Route::group(['prefix' => 'guest', 'as' => 'guest.'], function () {
                Route::get('/', 'UserController@guestList')->name('list');
                Route::get('/edit/{id}', 'UserController@guestEdit')->name('edit')->where('id','[0-9]+');
                Route::get('/change-user/{id}', 'UserController@guestChangeUser')->name('change-user')->where('id','[0-9]+');
                Route::post('/change-user-submit/{id}', 'UserController@guestChangeUser')->name('change-user-submit')->where('id','[0-9]+');
                Route::get('/status/{id}', 'UserController@guestStatus')->name('status')->where('id','[0-9]+');
                Route::get('/delete/{id}', 'UserController@guestDelete')->name('delete')->where('id','[0-9]+');
                Route::post('/edit-submit','UserController@editGuest')->name('edit-submit');
            });
        });

        Route::group(['prefix' => 'district', 'as' => 'district.'], function () {
            Route::get('/', 'DistrictController@list')->name('list');
            Route::get('/status/{id}', 'DistrictController@status')->name('status')->where('id','[0-9]+');
            
            Route::get('/edit/{id}', 'DistrictController@edit')->name('edit')->where('id','[0-9]+');
            Route::post('/edit-submit/{id}', 'DistrictController@edit')->name('editSubmit')->where('id','[0-9]+');
            Route::get('/add','DistrictController@add')->name('add');
            Route::post('/add-submit', 'DistrictController@add')->name('addSubmit');
        });

        Route::group(['prefix' => 'block', 'as' => 'block.'], function () {
            Route::get('/', 'BlockController@list')->name('list');
            Route::get('/status/{id}', 'BlockController@status')->name('status')->where('id','[0-9]+');
            
            Route::get('/edit/{id}', 'BlockController@edit')->name('edit')->where('id','[0-9]+');
            Route::post('/edit-submit/{id}', 'BlockController@edit')->name('editSubmit')->where('id','[0-9]+');
            Route::get('/add','BlockController@add')->name('add');
            Route::post('/add-submit', 'BlockController@add')->name('addSubmit');
        });

        Route::group(['prefix' => 'serviceprovider', 'as' => 'serviceprovider.'], function () {
            Route::get('/', 'SoftwareController@list')->name('list');
            Route::get('/status/{id}', 'SoftwareController@status')->name('status')->where('id','[0-9]+');
            
            Route::get('/edit/{id}', 'SoftwareController@edit')->name('edit')->where('id','[0-9]+');
            Route::post('/edit-submit/{id}', 'SoftwareController@edit')->name('editSubmit')->where('id','[0-9]+');
            Route::get('/add','SoftwareController@add')->name('add');
            Route::post('/add-submit', 'SoftwareController@add')->name('addSubmit');
        });


        Route::group(['prefix' => 'bank', 'as' => 'bank.'], function () {
            Route::get('/', 'BankController@list')->name('list');
            Route::get('/status/{id}', 'BankController@status')->name('status')->where('id','[0-9]+');
            
            Route::get('/edit/{id}', 'BankController@edit')->name('edit')->where('id','[0-9]+');
            Route::post('/edit-submit', 'BankController@edit')->name('editSubmit');
            Route::get('/add','BankController@add')->name('add');
            Route::post('/bank-submit', 'BankController@add')->name('bankSubmit');

            // pacs route by PK date:28/sep/2022
            Route::get('/pacs', 'BankController@pacslist')->name('pacslist');
            Route::get('/pacsadd','BankController@pacsadd')->name('pacsadd');
            Route::post('/pacsadd-submit', 'BankController@pacsadd')->name('pacsaddSubmit');
            Route::get('/pacsstatus/{id}', 'BankController@pacsstatus')->name('pacsstatus')->where('id','[0-9]+');
            Route::get('/pacsedit/{id}', 'BankController@pacsedit')->name('pacsedit')->where('id','[0-9]+');
            Route::post('/pacsedit-submit/{id}', 'BankController@pacsedit')->name('pacseditSubmit')->where('id','[0-9]+');
        });
        
        Route::group(['prefix' => 'zone', 'as' => 'zone.'], function () {
            Route::get('/', 'ZoneController@list')->name('list');
            Route::get('/status/{id}', 'ZoneController@status')->name('status')->where('id','[0-9]+');
            
            Route::get('/edit/{id}', 'ZoneController@edit')->name('edit')->where('id','[0-9]+');
            Route::post('/edit-submit', 'ZoneController@edit')->name('editSubmit');
            Route::get('/add','ZoneController@add')->name('add');
            Route::post('/zone-submit', 'ZoneController@add')->name('zoneSubmit');
        });

        Route::group(['prefix' => 'range', 'as' => 'range.'], function () {
            Route::get('/', 'RangeController@list')->name('list');
            Route::get('/status/{id}', 'RangeController@status')->name('status')->where('id','[0-9]+');
            
            Route::get('/edit/{id}', 'RangeController@edit')->name('edit')->where('id','[0-9]+');
            Route::post('/edit-submit', 'RangeController@edit')->name('editSubmit');
            Route::get('/add','RangeController@add')->name('add');
            Route::post('/range-submit', 'RangeController@add')->name('rangeSubmit');
            Route::get('/get-zone', 'RangeController@getZone')->name('getZone');
            Route::get('/pacs', 'RangeController@pacslist')->name('pacs');
            Route::get('/pacsstatus/{id}', 'RangeController@pacsstatus')->name('pacsstatus')->where('id','[0-9]+');
            
            Route::get('/pacsedit/{id}', 'RangeController@pacsedit')->name('pacsedit')->where('id','[0-9]+');
            Route::post('/pacsedit-submit/{id}', 'RangeController@pacsedit')->name('pacseditSubmit')->where('id','[0-9]+');
            Route::get('/pacsadd','RangeController@pacsadd')->name('pacsadd');
            Route::post('/pacsadd-submit', 'RangeController@pacsadd')->name('pacsaddSubmit');
            Route::get('/get-range-zone', 'RangeController@getRangeZone')->name('getRangeZone');
            Route::get('/get-range-range', 'RangeController@getRangeRange')->name('getRangeRange');
            Route::get('/get-range-block', 'RangeController@getRangeBlock')->name('getRangeBlock');
        });

        Route::group(['prefix' => 'pacs', 'as' => 'pacs.'], function () {
            Route::get('/', 'PacsController@list')->name('list');
            Route::get('/status/{id}', 'PacsController@status')->name('status')->where('id','[0-9]+');
            
            Route::get('/edit/{id}', 'PacsController@edit')->name('edit')->where('id','[0-9]+');
            Route::post('/edit-submit/{id}', 'PacsController@edit')->name('editSubmit')->where('id','[0-9]+');
            Route::get('/add','PacsController@add')->name('add');
            Route::any('/pacs-acknowledement', 'PacsController@pacsAcknowledement')->name('pacsAcknowledement')->where('id','[0-9]+');
            Route::any('/pacs-acknowledement-details', 'PacsController@pacsAcknowledementDetails')->name('pacsAcknowledementDetails')->where('id','[0-9]+');
            Route::post('/add-submit', 'PacsController@add')->name('addSubmit');
            Route::get('/get-pacs-zone', 'PacsController@getPacsZone')->name('getPacsZone');
            Route::get('/get-pacs-range', 'PacsController@getPacsRange')->name('getPacsRange');
            Route::get('/get-pacs-block', 'PacsController@getPacsBlock')->name('getPacsBlock');
        });

        Route::group(['prefix' => 'complain', 'as' => 'complain.'], function () {
            Route::get('/', 'ComplainController@list')->name('list');
            Route::get('/alllist', 'ComplainController@allList')->name('alllist');
            Route::any('/complain-destroy', 'ComplainController@complainDestroy')->name('complain-destroy');
            Route::get('/status/{id}', 'ComplainController@status')->name('status')->where('id','[0-9]+');
            Route::get('/edit/{id}', 'ComplainController@edit')->name('edit')->where('id','[0-9]+');
            Route::post('/edit-submit/{id}', 'ComplainController@edit')->name('editSubmit')->where('id','[0-9]+');
            Route::get('/add','ComplainController@add')->name('add');
            Route::post('/add-submit', 'ComplainController@add')->name('addSubmit');
            Route::get('/view/{id}', 'ComplainController@view')->name('view')->where('id','[0-9]+');
            Route::get('/alllreply', 'ComplainController@allReply')->name('alllreply');
            Route::get('/viewreply/{id}', 'ComplainController@viewReply')->name('viewreply')->where('id','[0-9]+');
            Route::get('/allview/{id}', 'ComplainController@allview')->name('allview')->where('id','[0-9]+');

            Route::get('/changestatus/{id}', 'ComplainController@statusChange')->name('changestatus');
        });

        Route::group(['prefix' => 'societie', 'as' => 'societie.'], function () {
            Route::get('/', 'SocietieController@list')->name('list');
            Route::get('/status/{id}', 'SocietieController@status')->name('status')->where('id','[0-9]+');
            
            Route::get('/edit/{id}', 'SocietieController@edit')->name('edit')->where('id','[0-9]+');
            Route::post('/edit-submit/{id}', 'SocietieController@edit')->name('editSubmit')->where('id','[0-9]+');
            Route::get('/add','SocietieController@add')->name('add');
            Route::post('/add-submit', 'SocietieController@add')->name('addSubmit');
        });
        

		
    });
    
});
