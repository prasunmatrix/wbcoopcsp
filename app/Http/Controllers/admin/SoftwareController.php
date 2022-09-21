<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Helper;
use Image, Auth, Hash, Redirect, Validator, View;
use AdminHelper;
use App\Software;

class SoftwareController extends Controller
{
    /*****************************************************/
    # SoftwareController
    # Function name : List
    # Author        :
    # Created Date  : 25-09-2020
    # Purpose       : Showing subAdminList of users
    # Params        : Request $request
    /*****************************************************/
    public function list(Request $request) {
        $data['page_title'] = 'Service Provider List';
        $data['panel_title']= 'Service Provider List';
        
        try
        {
            $pageNo = $request->input('page');
            Session::put('pageNo',$pageNo);

            $data['order_by']   = 'created_at';
            $data['order']      = 'desc';

            $query = Software::whereNull('deleted_at');

            $data['searchText'] = $key = $request->searchText;

            if ($key) {
                // if the search key is provided, proceed to build query for search
                $query->where(function ($q) use ($key) {
                    $q->where('full_name', 'LIKE', '%' . $key . '%');
                    
                
                });
            }
            $exists = $query->count();
            if ($exists > 0) {
                $list = $query->orderBy($data['order_by'], $data['order'])->paginate(AdminHelper::ADMIN_LIST_LIMIT);
                $data['list'] = $list;
            } else {
                $data['list'] = array();
            }       
            return view('admin.software.list', $data);
        } catch (Exception $e) {
            return redirect()->route('admin.serviceprovider.list')->with('error', $e->getMessage());
        }
    }

   /*****************************************************/
    # SoftwareController
    # Function name : Add
    # Author        :
    # Created Date  : 25-08-2020
    # Purpose       : Showing subAdminList of users
    # Params        : Request $request
    /*****************************************************/
    public function add(Request $request) {
        $data['page_title']     = 'Add Service Provider';
        $data['panel_title']    = 'Add Service Provider';
    
        try
        {
        	if ($request->isMethod('POST'))
        	{
				$validationCondition = array(
                    'full_name' => 'required|min:2|max:255|unique:'.(new Software)->getTable().',full_name',
				);
				$validationMessages = array(
                    'full_name.required'    => 'Please enter name',
                    'full_name.unique'      => 'Please enter unique service provider name',
					'full_name.min'         => 'Name should be should be at least 2 characters',
                    'full_name.max'         => 'Name should not be more than 255 characters',
				);

				$Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
				if ($Validator->fails()) {
					return redirect()->route('admin.serviceprovider.add')->withErrors($Validator)->withInput();
				} else {
                    
                    $new = new Software;
                    $new->full_name = trim($request->full_name, ' ');
                    $save = $new->save();
                
					if ($save) {						
						$request->session()->flash('alert-success', 'serviceprovider has been added successfully');
						return redirect()->route('admin.serviceprovider.list');
					} else {
						$request->session()->flash('alert-danger', 'An error occurred while adding the serviceprovider');
						return redirect()->back();
					}
				}
            }
			return view('admin.software.add', $data);
		} catch (Exception $e) {
			return redirect()->route('admin.serviceprovider.list')->with('error', $e->getMessage());
		}
    }

    /*****************************************************/
    # SoftwareController
    # Function name : Edit
    # Author        :
    # Created Date  : 25-09-2020
    # Purpose       : Showing subAdminList of users
    # Params        : Request $request
    /*****************************************************/
    public function edit(Request $request, $id = null) {
        $data['page_title']  = 'Edit Service Provider';
        $data['panel_title'] = 'Edit Service Provider';

        try
        {           
            $pageNo = Session::get('pageNo') ? Session::get('pageNo') : '';
            $data['pageNo'] = $pageNo;
           
            $details = Software::find($id);
            $data['id'] = $id;

            if ($request->isMethod('POST')) {
                if ($id == null) {
                    return redirect()->route('admin.serviceprovider.list');
                }
                $validationCondition = array(
                    'full_name'         => 'required|min:2|max:255|unique:' .(new Software)->getTable().',full_name,'.$id.'',
                );
                $validationMessages = array(
                    'full_name.required'    => 'Please enter name',
                    'full_name.unique'      => 'Please enter unique service provider name',
                    'full_name.min'         => 'Name should be should be at least 2 characters',
                    'full_name.max'         => 'Name should not be more than 255 characters',
                );
                
                $Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
                if ($Validator->fails()) {
                    return redirect()->back()->withErrors($Validator)->withInput();
                } else {
                    $details->full_name        = trim($request->full_name, ' ');
                    $save = $details->save();                        
                    if ($save) {
                        $request->session()->flash('alert-success', 'Service provider has been updated successfully');
                        return redirect()->route('admin.serviceprovider.list', ['page' => $pageNo]);
                    } else {
                        $request->session()->flash('alert-danger', 'An error occurred while updating the service provider');
                        return redirect()->back();
                    }
                }
            }
            
            
            return view('admin.software.edit')->with(['details' => $details, 'data' => $data]);

        } catch (Exception $e) {
            return redirect()->route('admin.serviceprovider.list')->with('error', $e->getMessage());
        }
    }

    /*****************************************************/
    # SoftwareController
    # Function name : Status
    # Author        :
    # Created Date  : 25-09-2020
    # Purpose       : Showing subAdminList of users
    # Params        : Request $request
    /*****************************************************/
    public function status(Request $request, $id = null)
    {
        try
        {
            if ($id == null) {
                return redirect()->route('admin.serviceprovider.list');
            }
            $details = Software::where('id', $id)->first();
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
                return redirect()->route('admin.serviceprovider.list')->with('error', 'Invalid serviceprovider');
            }
        } catch (Exception $e) {
            return redirect()->route('admin.serviceprovider.list')->with('error', $e->getMessage());
        }
    }

    
    
}
