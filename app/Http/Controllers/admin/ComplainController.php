<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Helper;
use Image, Auth, Hash, Redirect, Validator, View;
use AdminHelper;
use App\User;
use App\UserDetails;
use App\Software;
use App\Bank;
use App\Complain;

class ComplainController extends Controller
{
    /*****************************************************/
    # ComplainController
    # Function name : List
    # Author        :
    # Created Date  : 24-09-2020
    # Purpose       : Showing List of Range
    # Params        : Request $request
    /*****************************************************/
    public function list(Request $request) {
        $data['page_title'] = 'Complain Raised';
        $data['panel_title']= 'Complain Raised';
        
        try
        {
            $logginUser = AdminHelper::loggedUser();
            $pageNo = $request->input('page');
            Session::put('pageNo',$pageNo);

            $data['order_by']   = 'created_at';
            $data['order']      = 'desc';

            $query =  $allComplain = Complain::where('reported_by', $logginUser->id);
            // dd($query);
            
            $data['searchText'] = $key = $request->searchText;

            if ($key) {
                // if the search key is provided, proceed to build query for search
                $query->where(function ($q) use ($key) {
                    $q->where('ticket_no', '=',$key);
                    
                    
                });
            }
            $exists = $query->count();
            if ($exists > 0) {
                $list = $query->orderBy($data['order_by'], $data['order'])->paginate(AdminHelper::ADMIN_LIST_LIMIT);
                $data['list'] = $list;
            } else {
                $data['list'] = array();
            }  
            
            
            // dd($list); 

            return view('admin.complain.list',$data);

        } catch (Exception $e) {
            return redirect()->route('admin.complain.list')->with('error', $e->getMessage());
        }
    }

   /*****************************************************/
    # ComplainController
    # Function name : Add
    # Author        :
    # Created Date  : 24-09-2020
    # Purpose       : Add new Range user
    # Params        : Request $request
    /*****************************************************/
    public function add(Request $request) {
        $data['page_title']     = 'Raise a Complain';
        $data['panel_title']    = 'Raise a Complain';
    
        try
        {
            $logginUser = AdminHelper::loggedUser();
            // echo($logginUser->user_parrent_id);
            
                $bankList = User::with(['UserProfile'])->whereUserType('1')->where('id','!=',$logginUser->id)->where('status','1')->orderBy('full_name', 'asc')->get();
                $data['bankList'] = $bankList;
                // dd($data['bankList']);
                $zoneList = User::whereUserType('2')->where('id','!=',$logginUser->id)->where('status','1')->orderBy('full_name', 'asc')->get();
                $data['zoneList'] = $zoneList;
                $rangeList = User::whereUserType('3')->where('id','!=',$logginUser->id)->where('status','1')->orderBy('full_name', 'asc')->get();
                $data['rangeList'] = $rangeList;
                $paceList = User::whereUserType('4')->where('id','!=',$logginUser->id)->where('status','1')->orderBy('full_name', 'asc')->get();
                $data['paceList'] = $paceList;

                $serviceProviderList = Software::where('status','1')->orderBy('full_name', 'asc')->get();
                $data['serviceProviderList'] = $serviceProviderList;
                // dd($serviceProviderList);
            
            
            
            
        	if ($request->isMethod('POST'))
        	{
                // dd($request);
				$validationCondition = array(
                    'report_details'     => 'required|min:2|max:255',
				);
				$validationMessages = array(
					'report_details.required'        => 'Please enter reporting details',
					'report_details.min'             => 'Report Details should be at least 2 characters',
                    'report_details.max'             => 'Report Details should not be more than 255 characters',
				);

				$Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
				if ($Validator->fails()) {
					return redirect()->route('admin.complain.add')->withErrors($Validator)->withInput();
				} else {
                    
                    
                    $generatedTicket = $this->rand_ticket($request->reported_to);
                    $new = new Complain;
                    $new->reported_by         = $logginUser->id;
                    $new->reported_to         = $request->reported_to;
                    $new->user_type           = $request->user_type;
                    $new->report_details      = $request->report_details;
                    $new->ticket_no           = $generatedTicket;
                    if ($logginUser->user_parrent_id == $new->reported_to) {
                        $val = $new->reported_to ;
                    } else {
                        $val = $logginUser->user_parrent_id;
                    }
                    $new->reported_parrent_id = $val;
                    $save                     = $new->save();
                    
					if ($save) {
                         
                        // Send Mail to Bank User with Username, Password
                        if ($new->user_type == 5){
                            $hqEmailDetails = User::where('id','1')->first();
                            // dd($hqEmailDetails);
                            \Mail::send('email_templates.ticket_for_admin',
                            [
                                'user' => $hqEmailDetails,
                                'loggedinUserData' => $logginUser,
                                'newData'       => $new,
                                'app_config' => [
                                      
                                    'appLink'       => Helper::getBaseUrl(),
                                    'controllerName'=> 'user',
                                    'subject'       => 'Ticket create succefully',
                                ],
                            ], function ($m) use ($hqEmailDetails) {
                                $m->to($hqEmailDetails->email,$hqEmailDetails->full_name)->subject('Ticket');
                            });
                            
                        } else {
                            \Mail::send('email_templates.ticket_credential',
                            [
                                'user' => $new,
                                'loggedinUserData' => $logginUser,
                                'app_config' => [
                                      
                                    'appLink'       => Helper::getBaseUrl(),
                                    'controllerName'=> 'user',
                                    'subject'       => 'Ticket create succefully',
                                ],
                            ], function ($m) use ($new) {
                                $m->to($new->userDetails->email, $new->userDetails->full_name)->subject('Ticket');
                            });
                        }


						$request->session()->flash('alert-success', 'Complain has been raised successfully');
						return redirect()->route('admin.complain.list');
					} else {
						$request->session()->flash('alert-danger', 'An error occurred while adding the complain');
						return redirect()->back();
					}
				}
            }
           // dd($data);
			return view('admin.complain.add',$data);
		} catch (Exception $e) {
			return redirect()->route('admin.complain.list')->with('error', $e->getMessage());
		}
    }

    /*****************************************************/
    # ComplainController
    # Function name : Edit
    # Author        :
    # Created Date  : 01-10-2020
    # Purpose       : Showing subAdminList of users
    # Params        : Request $request
    /*****************************************************/
    public function edit(Request $request, $id = null) {
        $data['page_title']  = 'Reply Complain';
        $data['panel_title'] = 'Reply Complain';

        try
        {     
            $logginUser = AdminHelper::loggedUser();      
            $pageNo = Session::get('pageNo') ? Session::get('pageNo') : '';
            $data['pageNo'] = $pageNo;
           
            $details = Complain::find($id);
            $data['id'] = $id;

            if ($request->isMethod('POST')) {
                if ($id == null) {
                    return redirect()->route('admin.complain.list');
                }
                $validationCondition = array(
                    'disposing_note'       => 'required|min:2|max:255',
                );
                $validationMessages = array(
                    'disposing_note.required'          => 'Please enter disposing note',
                    'disposing_note.min'               => 'Disposing Note should be at least 2 characters',
                    'disposing_note.max'               => 'Disposing Note should not be more than 255 characters',
                );
                
                $Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
                if ($Validator->fails()) {
                    return redirect()->back()->withErrors($Validator)->withInput();
                } else {
                    // dd($details);
                    $details->disposing_note        = trim($request->disposing_note, ' ');
                    $details->status                = '1';
                    $details->updated_at            = date('Y-m-d H:i:s');
                    $details->reply_id              = $logginUser->id;
                    $save = $details->save();                        
                    if ($save) {
                        $request->session()->flash('alert-success', 'Complain has been replied successfully');
                        if ($logginUser->user_type == 0){
                            
                        return redirect()->route('admin.complain.alllist', ['page' => $pageNo]);
                        } else {
                            return redirect()->route('admin.complain.alllreply', ['page' => $pageNo]);
                        }
                    } else {
                        $request->session()->flash('alert-danger', 'An error occurred while updating the complain');
                        return redirect()->back();
                    }
                }
            }
            
            
            return view('admin.complain.edit')->with(['details' => $details, 'data' => $data]);

        } catch (Exception $e) {
            return redirect()->route('admin.complain.list')->with('error', $e->getMessage());
        }
    }

    /*****************************************************/
    # ComplainController
    # Function name : Complain Destroy
    # Author        :
    # Created Date  : 29-09-2020
    # Purpose       : Destroy Popup
    # Params        : 
    /*****************************************************/

    public function complainDestroy()
    {
        $data['page_title'] = 'Dashboard';
        $data['panel_title'] = 'Dashboard';

       session()->forget('clicked');    
            
        return redirect()->route('admin.complain.list');       
    }


    /*****************************************************/
    # ComplainController
    # Function name : Status
    # Author        :
    # Created Date  : 24-09-2020
    # Purpose       : Changing Range users status
    # Params        : Request $request
    /*****************************************************/
    public function status(Request $request, $id = null)
    {
        try
        {
            
            if ($id == null) {
                return redirect()->route('admin.complain.list');
            }
            $details = Complain::where('id', $id)->first();
            if ($details != null) {
                if ($details->status == 1) {
                    
                    $details->status = '0';
                    $details->save();
                        
                    $request->session()->flash('alert-success', 'Status updated successfully');                 
                     } else if ($details->status == 0) {
                    $details->status = '1';
                    $details->save();
                    $request->session()->flash('alert-success', 'Status updated successfully');
                   
                } else {
                    $request->session()->flash('alert-danger', 'Something went wrong');
                    
                }
                return redirect()->back();
            } else {
                return redirect()->route('admin.complain.list')->with('error', 'Invalid state');
            }
        } catch (Exception $e) {
            return redirect()->route('admin.complain.list')->with('error', $e->getMessage());
        }
    }

    /*****************************************************/
    # ComplainController
    # Function name : Status
    # Author        :
    # Created Date  : 10-10-2020
    # Purpose       : Changing Range users status
    # Params        : Request $request
    /*****************************************************/
    public function statusChange(Request $request, $id = null)
    {
        try
        {
            
            if ($id == null) {
                return redirect()->route('admin.complain.list');
            }
            $details = Complain::where('id', $id)->first();
            
            if ($details != null) {
                
                    
                    $details->status = '2';
                    
                    $details->save();
                        
                    $request->session()->flash('alert-success', 'Status updated successfully');                 
                     
                return redirect()->back();
            } else {
                return redirect()->back();
            }
        } catch (Exception $e) {
            return redirect()->route('admin.complain.list')->with('error', $e->getMessage());
        }
    }

    
    public function rand_ticket($user_id) {
        return $user_id.date('Ymdhis');
        
    }

    public function view(Request $request, $id)
    {
        
        try
        {
            $data['id'] = $id;
            $data['page_title']  = 'View Complain';
            $data['panel_title'] = 'View Complain';

            $saleData = Complain::find($id);
            $data['saleData'] = $saleData;
            
            if ($data['saleData']) {
                return view('admin.complain.view',$data);
            } else {
                return abort(404);
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        } 
    }

    public function allview(Request $request, $id)
    {
        
        try
        {
            $data['id'] = $id;
            $data['page_title']  = 'View Complain';
            $data['panel_title'] = 'View Complain';

            $saleData = Complain::find($id);
            $data['saleData'] = $saleData;
            
            if ($data['saleData']) {
                return view('admin.complain.allview',$data);
            } else {
                return abort(404);
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        } 
    }

    public function allList(Request $request) {
        $data['page_title'] = 'All Complain';
        $data['panel_title']= 'All Complain';
        
        try
        {
            $logginUser = AdminHelper::loggedUser();
            $pageNo = $request->input('page');
            Session::put('pageNo',$pageNo);

            $data['order_by']   = 'created_at';
            $data['order']      = 'desc';

            $query  = Complain::where('reported_by','!=', $logginUser->id);
            // dd($query);
            
            $data['searchText1'] = $key1 = $request->searchText1;

            if ($key1) {
                // if the search key is provided, proceed to build query for search
                $query->where(function ($q) use ($key1) {
                    $q->where('ticket_no', '=',$key1);
                    
                    
                });
            }
            $exists = $query->count();
            if ($exists > 0) {
                $list = $query->orderBy($data['order_by'], $data['order'])->paginate(AdminHelper::ADMIN_LIST_LIMIT);
                $data['list'] = $list;
            } else {
                $data['list'] = array();
            }  
            
            
            // dd($data); 

            return view('admin.complain.alllist',$data);

        } catch (Exception $e) {
            return redirect()->route('admin.complain.alllist')->with('error', $e->getMessage());
        }
    }

    public function allReply(Request $request) {
        $data['page_title'] = 'Complain Received';
        $data['panel_title']= 'Complain Received';
        
        try
        {
            $logginUser = AdminHelper::loggedUser();
            $pageNo = $request->input('page');
            Session::put('pageNo',$pageNo);

            $data['order_by']   = 'created_at';
            $data['order']      = 'desc';

            $query = Complain::where('reported_to',$logginUser->id);
            // dd($query);
            
            $data['searchText'] = $key = $request->searchText;

            if ($key) {
                // if the search key is provided, proceed to build query for search
                $query->where(function ($q) use ($key) {
                    $q->where('ticket_no', '=',$key);
                    
                    
                });
            }
            $exists = $query->count();
            if ($exists > 0) {
                $list = $query->orderBy($data['order_by'], $data['order'])->paginate(AdminHelper::ADMIN_LIST_LIMIT);
                $data['list'] = $list;
            } else {
                $data['list'] = array();
            }  
            
            
            // dd($data); 

            return view('admin.complain.allreply',$data);

        } catch (Exception $e) {
            return redirect()->route('admin.complain.allreply')->with('error', $e->getMessage());
        }
    }

    public function viewReply(Request $request, $id)
    {
        
        try
        {
            $data['id'] = $id;
            $data['page_title']  = 'View Complain Reply';
            $data['panel_title'] = 'View Complain Reply';

            $replyData = Complain::find($id);
            $data['replyData'] = $replyData;
            
            if ($data['replyData']) {
                return view('admin.complain.viewreply',$data);
            } else {
                return abort(404);
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        } 
    }
    
}
