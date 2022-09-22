<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Helper;
use Image, Auth, Hash, Redirect, Validator, View;
use AdminHelper;
use App\District;

class DistrictController extends Controller
{
    /*****************************************************/
    # DistrictController
    # Function name : List
    # Author        :
    # Created Date  : 23-09-2020
    # Purpose       : Showing subAdminList of users
    # Params        : Request $request
    /*****************************************************/
    public function list(Request $request) {
        $data['page_title'] = 'District List';
        $data['panel_title']= 'District List';
        
        try
        {
            $pageNo = $request->input('page');
            Session::put('pageNo',$pageNo);

            $data['order_by']   = 'created_at';
            $data['order']      = 'desc';

            $query = District::whereNull('deleted_at');

            $data['searchText'] = $key = $request->searchText;

            if ($key) {
                // if the search key is provided, proceed to build query for search
                $query->where(function ($q) use ($key) {
                    $q->where('district_name', 'LIKE', '%' . $key . '%');
                    
                
                });
            }
            $exists = $query->count();
            if ($exists > 0) {
                $list = $query->orderBy($data['order_by'], $data['order'])->paginate(AdminHelper::ADMIN_LIST_LIMIT);
                $data['list'] = $list;
            } else {
                $data['list'] = array();
            }       
            return view('admin.district.list', $data);
        } catch (Exception $e) {
            return redirect()->route('admin.district.list')->with('error', $e->getMessage());
        }
    }

   /*****************************************************/
    # DistrictController
    # Function name : Add
    # Author        :
    # Created Date  : 23-08-2020
    # Purpose       : Showing subAdminList of users
    # Params        : Request $request
    /*****************************************************/
    public function add(Request $request) {
        $data['page_title']     = 'Add District';
        $data['panel_title']    = 'Add District';
    
        try
        {
        	if ($request->isMethod('POST'))
        	{
				$validationCondition = array(
                    'district_name' => 'required|min:2|max:255|unique:'.(new District)->getTable().',district_name',
				);
				$validationMessages = array(
					'district_name.required'    => 'Please enter name',
					'district_name.min'         => 'Name should be should be at least 2 characters',
                    'district_name.max'         => 'Name should not be more than 255 characters',
				);

				$Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
				if ($Validator->fails()) {
					return redirect()->route('admin.district.add')->withErrors($Validator)->withInput();
				} else {
                    
                    $new = new District;
                    $new->district_name = trim($request->district_name, ' ');
                    $save = $new->save();
                
					if ($save) {						
						$request->session()->flash('alert-success', 'District has been added successfully');
						return redirect()->route('admin.district.list');
					} else {
						$request->session()->flash('alert-danger', 'An error occurred while adding the district');
						return redirect()->back();
					}
				}
            }
			return view('admin.district.add', $data);
		} catch (Exception $e) {
			return redirect()->route('admin.district.list')->with('error', $e->getMessage());
		}
    }

    /*****************************************************/
    # DistrictController
    # Function name : Edit
    # Author        :
    # Created Date  : 23-09-2020
    # Purpose       : Showing subAdminList of users
    # Params        : Request $request
    /*****************************************************/
    public function edit(Request $request, $id = null) {
        $data['page_title']  = 'Edit District';
        $data['panel_title'] = 'Edit District';

        try
        {           
            $pageNo = Session::get('pageNo') ? Session::get('pageNo') : '';
            $data['pageNo'] = $pageNo;
           
            $details = District::find($id);
            $data['id'] = $id;

            if ($request->isMethod('POST')) {
                if ($id == null) {
                    return redirect()->route('admin.district.list');
                }
                $validationCondition = array(
                    'district_name'         => 'required|min:2|max:255|unique:' .(new District)->getTable().',district_name,'.$id.'',
                );
                $validationMessages = array(
                    'district_name.required'    => 'Please enter name',
                    'district_name.min'         => 'Name should be should be at least 2 characters',
                    'district_name.max'         => 'Name should not be more than 255 characters',
                );
                
                $Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
                if ($Validator->fails()) {
                    return redirect()->back()->withErrors($Validator)->withInput();
                } else {
                    $details->district_name        = trim($request->district_name, ' ');
                    $save = $details->save();                        
                    if ($save) {
                        $request->session()->flash('alert-success', 'District has been updated successfully');
                        return redirect()->route('admin.district.list', ['page' => $pageNo]);
                    } else {
                        $request->session()->flash('alert-danger', 'An error occurred while updating the district');
                        return redirect()->back();
                    }
                }
            }
            
            
            return view('admin.district.edit')->with(['details' => $details, 'data' => $data]);

        } catch (Exception $e) {
            return redirect()->route('admin.district.list')->with('error', $e->getMessage());
        }
    }

    /*****************************************************/
    # DistrictController
    # Function name : Status
    # Author        :
    # Created Date  : 23-09-2020
    # Purpose       : Showing subAdminList of users
    # Params        : Request $request
    /*****************************************************/
    public function status(Request $request, $id = null)
    {
        try
        {
            if ($id == null) {
                return redirect()->route('admin.district.list');
            }
            $details = District::where('id', $id)->first();
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
                return redirect()->route('admin.district.list')->with('error', 'Invalid district');
            }
        } catch (Exception $e) {
            return redirect()->route('admin.district.list')->with('error', $e->getMessage());
        }
    }

    
    
}
