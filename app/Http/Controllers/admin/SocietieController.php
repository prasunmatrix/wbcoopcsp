<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Helper;
use Image, Auth, Hash, Redirect, Validator, View;
use AdminHelper;
use App\Societie;

class SocietieController extends Controller
{
    /*****************************************************/
    # SocietieController
    # Function name : List
    # Author        :
    # Created Date  : 09-10-2020
    # Purpose       : Showing subAdminList of users
    # Params        : Request $request
    /*****************************************************/
    public function list(Request $request) {
        $data['page_title'] = 'Society Type List';
        $data['panel_title']= 'Society Type List';
        
        try
        {
            $pageNo = $request->input('page');
            Session::put('pageNo',$pageNo);

            $data['order_by']   = 'created_at';
            $data['order']      = 'desc';

            $query = Societie::whereNull('deleted_at');

            $data['searchText'] = $key = $request->searchText;

            if ($key) {
                // if the search key is provided, proceed to build query for search
                $query->where(function ($q) use ($key) {
                    $q->where('name', 'LIKE', '%' . $key . '%');
                    
                
                });
            }
            $exists = $query->count();
            if ($exists > 0) {
                $list = $query->orderBy($data['order_by'], $data['order'])->paginate(AdminHelper::ADMIN_LIST_LIMIT);
                $data['list'] = $list;
            } else {
                $data['list'] = array();
            }       
            return view('admin.societie.list', $data);
        } catch (Exception $e) {
            return redirect()->route('admin.societie.list')->with('error', $e->getMessage());
        }
    }

   /*****************************************************/
    # SocietieController
    # Function name : Add
    # Author        :
    # Created Date  : 09-10-2020
    # Purpose       : Showing subAdminList of users
    # Params        : Request $request
    /*****************************************************/
    public function add(Request $request) {
        $data['page_title']     = 'Add Society Type';
        $data['panel_title']    = 'Add Society Type';
    
        try
        {
        	if ($request->isMethod('POST'))
        	{
				$validationCondition = array(
                    'name' => 'required|min:2|max:255|unique:'.(new Societie)->getTable().',name',
				);
				$validationMessages = array(
                    'name.required'    => 'Please enter society type name',
                    'name.unique'      => 'Please enter unique society type name',
					'name.min'         => 'Name should be should be at least 2 characters',
                    'name.max'         => 'Name should not be more than 255 characters',
				);

				$Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
				if ($Validator->fails()) {
					return redirect()->route('admin.societie.add')->withErrors($Validator)->withInput();
				} else {
                    
                    $new = new Societie;
                    $new->name = trim($request->name, ' ');
                    $save = $new->save();
                
					if ($save) {						
						$request->session()->flash('alert-success', 'Society type has been added successfully');
						return redirect()->route('admin.societie.list');
					} else {
						$request->session()->flash('alert-danger', 'An error occurred while adding the society');
						return redirect()->back();
					}
				}
            }
			return view('admin.societie.add', $data);
		} catch (Exception $e) {
			return redirect()->route('admin.societie.list')->with('error', $e->getMessage());
		}
    }

    /*****************************************************/
    # SocietieController
    # Function name : Edit
    # Author        :
    # Created Date  : 09-10-2020
    # Purpose       : Showing subAdminList of users
    # Params        : Request $request
    /*****************************************************/
    public function edit(Request $request, $id = null) {
        $data['page_title']  = 'Edit Society type';
        $data['panel_title'] = 'Edit Society type';

        try
        {           
            $pageNo = Session::get('pageNo') ? Session::get('pageNo') : '';
            $data['pageNo'] = $pageNo;
           
            $details = Societie::find($id);
            $data['id'] = $id;

            if ($request->isMethod('POST')) {
                if ($id == null) {
                    return redirect()->route('admin.societie.list');
                }
                $validationCondition = array(
                    'name'         => 'required|min:2|max:255|unique:' .(new Societie)->getTable().',name,'.$id.'',
                );
                $validationMessages = array(
                    'name.required'    => 'Please enter society type name',
                    'name.unique'      => 'Please enter unique society type name',
                    'name.min'         => 'Name should be should be at least 2 characters',
                    'name.max'         => 'Name should not be more than 255 characters',
                );
                
                $Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
                if ($Validator->fails()) {
                    return redirect()->back()->withErrors($Validator)->withInput();
                } else {
                    $details->name        = trim($request->name, ' ');
                    $save = $details->save();                        
                    if ($save) {
                        $request->session()->flash('alert-success', 'Society type has been updated successfully');
                        return redirect()->route('admin.societie.list', ['page' => $pageNo]);
                    } else {
                        $request->session()->flash('alert-danger', 'An error occurred while updating the Society');
                        return redirect()->back();
                    }
                }
            }
            
            
            return view('admin.societie.edit')->with(['details' => $details, 'data' => $data]);

        } catch (Exception $e) {
            return redirect()->route('admin.societie.list')->with('error', $e->getMessage());
        }
    }

    /*****************************************************/
    # SocietieController
    # Function name : Status
    # Author        :
    # Created Date  : 09-10-2020
    # Purpose       : Showing subAdminList of users
    # Params        : Request $request
    /*****************************************************/
    public function status(Request $request, $id = null)
    {
        try
        {
            if ($id == null) {
                return redirect()->route('admin.societie.list');
            }
            $details = Societie::where('id', $id)->first();
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
                return redirect()->route('admin.societie.list')->with('error', 'Invalid society');
            }
        } catch (Exception $e) {
            return redirect()->route('admin.societie.list')->with('error', $e->getMessage());
        }
    }

    
    
}
