<?php
/*****************************************************/
# PageController
# Page/Class name : ApiTokenMiddleware
# Author :
# Created Date : 20-05-2019
# Functionality : handle
# Purpose : to manage api access token
/*****************************************************/
namespace App\Http\Middleware;
use App\Http\Helpers\ApiHelper;
use Closure;

class ApiBasicMiddleware
{
    /*****************************************************/
    # HomeController
    # Function name : handle
    # Author :  
    # Created Date : 12-05-2019
    # Purpose :  to manage access token and session access token
    # Params : 
    /*****************************************************/
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $headers = $request->header();
        
        if (array_key_exists('x-access-token', $headers) ) {
            $token  = $headers['x-access-token'][0];
            $checkToken = \App\Token::where('token', $token)->first();
            
            if($checkToken) {
                // if(($checkToken->token_time)+900 <= now()->timestamp) {
                //     $checkToken->delete();
                //     return \Response::json(ApiHelper::generateResponseBody('BB-M-0003#auth_token_mismatch', trans('custom.token_expired'), false, 300));
                // } else {
                    
                // }
                if(array_key_exists('x-device-token', $headers)) {
                    return $next($request);
                } else {
                    return \Response::json(ApiHelper::generateResponseBody('AD-TE-0001#Device_token_error', 'Device token not provided', false, 204));
                }   
            } else {
                return \Response::json(ApiHelper::generateResponseBody('AD-TE-0002#Auth_token_mismatch', 'Sorry!! Auth Token mismatch, please generate a new token', false, 204));
            }
        } else {
            return \Response::json(ApiHelper::generateResponseBody('AD-TE-0003#Auth_token_error', 'Sorry!! Auth token not found, please provide a auth token', false, 204));
        }
    }
}