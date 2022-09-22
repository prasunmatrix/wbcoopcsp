<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::middleware('api')->namespace('api')->prefix("v1")->group(function () {
    
    //Route without login and API Token   
    Route::get('/', 'HomeController@index')->name('api-index');
    Route::get('/generate-token', 'HomeController@generateToken')->name('api-generate-token');
        
    //Route without login and with API and device Token
    Route::middleware('api.basic')->group(function() {
        
        //==============================Without Login Route===============================// 
            Route::post('/member-signup', 'HomeController@memberSignup')->name('api-member-signup');
            Route::post('/guest-signup', 'HomeController@guestSignup')->name('api-guest-signup');
            Route::post('/cms-pages', 'HomeController@cmsPages')->name('api-cms-pages');
            Route::post('/site-settings', 'HomeController@siteSettings')->name('api-site-settings');
            Route::post('/all-cities', 'HomeController@allCities')->name('api-all-cities');
            Route::post('/all-project-type', 'HomeController@allProjectType')->name('api-all-project-type');
            Route::post('/all-application-type', 'HomeController@allApplicationType')->name('api-all-application-type');
            Route::post('/all-states', 'HomeController@allStates')->name('api-all-states');

            Route::post('/user-authentication', 'UserController@userAuthentication')->name('api-user-authentication');
            Route::post('/forget-password', 'UserController@forgetPassword')->name('api-forget-password');
            Route::post('/reset-password', 'UserController@resetPassword')->name('api-reset-password');

            Route::post('/all-events', 'EventController@allEvents')->name('api-all-events');
            Route::post('/all-upcoming-events', 'EventController@allUpcomingEvents')->name('api-all-upcoming-events');
            Route::post('/all-closed-events', 'EventController@allClosedEvents')->name('api-all-closed-events');
            Route::post('/event-details', 'EventController@eventDetails')->name('api-event-details');
            
            Route::post('/enquiry_update', 'EnquiryController@enquiryUpdate')->name('api-enquiry-update');

            Route::post('/add-idea', 'IdeaController@addShare')->name('api-add-idea');

            Route::post('/all-faq-questions', 'FaqController@allFaqQuestion')->name('api-all-faq-questions');
            Route::post('/all-faq-search', 'FaqController@allFaqSearch')->name('api-all-faq-search');
        //==============================Without Login Route===============================//

        //==============================With Login Route===============================//
            Route::post('/logout', 'UserController@logOut')->name('api-logout');
            Route::post('/show-member-profile', 'UserController@showMemberProfile')->name('api-show-member-profile');
            Route::post('/edit-member-profile', 'UserController@editMemberProfile')->name('api-edit-member-profile');
            Route::post('/update-profile-image', 'UserController@updateProfileImage')->name('api-update-profile-image');
            Route::post('/change-password', 'UserController@changePassword')->name('api-change-password');
            
            Route::post('/update-sale-info', 'PointController@updateSaleInfo')->name('api-update-sale-info');
            Route::post('/sale-history', 'PointController@saleHistory')->name('api-sale-history');
            Route::post('/sale-history-details-all', 'PointController@saleHistoryDetailsAll')->name('api-sale-history-details-all');
            Route::post('/sale-history-details-accept', 'PointController@saleHistoryDetailsAccept')->name('api-sale-history-details-accept');
            Route::post('/sale-history-details-pending', 'PointController@saleHistoryDetailsPending')->name('api-sale-history-details-pending');
            Route::post('/point-all-parents', 'PointController@allParents')->name('api-point-all-parents');
            Route::post('/point-all-child', 'PointController@allChild')->name('api-point-all-child');
            Route::post('/point-all-shades', 'PointController@allShades')->name('api-point-all-shades');
            Route::post('/point-all-materials', 'PointController@allMaterials')->name('api-point-all-materials');

            Route::post('/add-project', 'ProjectController@addProject')->name('api-add-project');
            Route::post('/user-project-list', 'ProjectController@userProjectList')->name('api-user-project-list');
            Route::post('/user-project-details', 'ProjectController@userProjectDetails')->name('api-user-project-details');

            Route::post('/add-social-post', 'SocialController@addPost')->name('api-add-social-post');
            Route::post('/social-post-list-all', 'SocialController@postListAll')->name('api-social-post-list-all');
            Route::post('/social-post-list-self', 'SocialController@postListSelf')->name('api-social-post-list-self');
            Route::post('/social-post-details', 'SocialController@postDetails')->name('api-social-post-details');
            Route::post('/social-post-add-comment', 'SocialController@addComment')->name('api-social-post-add-comment');
            Route::post('/social-post-add-like', 'SocialController@addPostLike')->name('api-social-post-add-like');
            Route::post('/social-comment-add-like', 'SocialController@addCommentLike')->name('api-social-comment-add-like');

            Route::post('/event-like-user', 'EventController@eventLikeUser')->name('api-event-like-user');

            Route::post('/add-design', 'DesignController@addDesign')->name('api-add-design');

            Route::post('/feb-arc-list', 'FabArcEnqController@fabArcList')->name('api-feb-arc-list');
            Route::post('/feb-arc-details', 'FabArcEnqController@fabArcDetails')->name('api-feb-arc-details');
            Route::post('/feb-arc-enquiry', 'FabArcEnqController@fabArcEnquiry')->name('api-feb-arc-enquiry');

        //==============================With Login Route===============================//

    });
        
        

}); 