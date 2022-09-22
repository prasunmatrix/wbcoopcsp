<?php
/*****************************************************/
# Page/Class name   : BlockController
/*****************************************************/

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Helper;
use Image, Auth, Hash, Redirect, Validator, View;
use AdminHelper;
use App\District;
use App\Block;

class BlockController extends Controller
{
    /*****************************************************/
    # BlockController
    # Function name : List
    # Author        :
    # Created Date  : 24-09-2020
    # Purpose       : Showing subAdminList of users
    # Params        : Request $request
    /*****************************************************/
    public function list(Request $request) {
        $data['page_title'] = 'Block List';
        $data['panel_title']= 'Block List';
        
        try
        {
            $pageNo = $request->input('page');
            Session::put('pageNo',$pageNo);

            $data['order_by']   = 'created_at';
            $data['order']      = 'desc';

            $query = Block::whereNull('deleted_at');

            $data['searchText'] = $key = $request->searchText;

            if ($key) {
                // if the search key is provided, proceed to build query for search
                $query->where(function ($q) use ($key) {
                    $q->where('block_name', 'LIKE', '%' . $key . '%');
                    
                })->orWhereHas('districtDetails', function($q) use ($key) {
                     $q->where('district_name', 'LIKE', '%'. $key . '%');
                });
            }
            $exists = $query->count();
            if ($exists > 0) {
                $list = $query->orderBy($data['order_by'], $data['order'])->paginate(AdminHelper::ADMIN_LIST_LIMIT);
                $data['list'] = $list;
            } else {
                $data['list'] = array();
            }       
            return view('admin.block.list', $data);
        } catch (Exception $e) {
            return redirect()->route('admin.block.list')->with('error', $e->getMessage());
        }
    }

   /*****************************************************/
    # BlockController
    # Function name : Add
    # Author        :
    # Created Date  : 24-09-2020
    # Purpose       : Showing subAdminList of users
    # Params        : Request $request
    /*****************************************************/
    public function add(Request $request) {
        $data['page_title']     = 'Add Block';
        $data['panel_title']    = 'Add Block';
    
        try
        {
        	if ($request->isMethod('POST'))
        	{
				$validationCondition = array(
                    'block_name' => 'required|min:2|max:255|unique:'.(new Block)->getTable().',block_name',
                    'district_id' => 'required',
				);
				$validationMessages = array(
					'block_name.required'    => 'Please enter name',
					'block_name.min'         => 'Name should be should be at least 2 characters',
                    'block_name.max'         => 'Name should not be more than 255 characters',
                    'district_id.required'   => 'District is required',
				);

				$Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
				if ($Validator->fails()) {
					return redirect()->route('admin.block.add')->withErrors($Validator)->withInput();
				} else {
                    
                    $new = new Block;
                    $new->block_name = trim($request->block_name, ' ');
                    $new->district_id  = $request->district_id;
                    $save = $new->save();
                
					if ($save) {						
						$request->session()->flash('alert-success', 'Block has been added successfully');
						return redirect()->route('admin.block.list');
					} else {
						$request->session()->flash('alert-danger', 'An error occurred while adding the block');
						return redirect()->back();
					}
				}
            }
            $districtList = District::select('id','district_name')->where('status', '1')->orderBy('district_name', 'asc')->get();
            $data['districtList'] = $districtList;
			return view('admin.block.add', $data);
		} catch (Exception $e) {
			return redirect()->route('admin.block.list')->with('error', $e->getMessage());
		}
    }

    /*****************************************************/
    # BlockController
    # Function name : Edit
    # Author        :
    # Created Date  : 24-09-2020
    # Purpose       : Showing subAdminList of users
    # Params        : Request $request
    /*****************************************************/
    public function edit(Request $request, $id = null) {
        $data['page_title']  = 'Edit Block';
        $data['panel_title'] = 'Edit Block';

        try
        {           
            $pageNo = Session::get('pageNo') ? Session::get('pageNo') : '';
            $data['pageNo'] = $pageNo;
            $districtList = District::select('id','district_name')->where('status', '1')->orderBy('district_name', 'asc')->get();
            $data['districtList'] = $districtList;
            $details = Block::find($id);
            $data['id'] = $id;

            if ($request->isMethod('POST')) {
                if ($id == null) {
                    return redirect()->route('admin.block.list');
                }
                $validationCondition = array(
                    'block_name' => 'required|min:2|max:255|unique:' .(new Block)->getTable().',block_name,' .$id.',id,deleted_at,NULL',
                    'district_id' => 'required',
                );
                $validationMessages = array(
                    'block_name.required'    => 'Please enter name',
					'block_name.min'         => 'Name should be should be at least 2 characters',
                    'block_name.max'         => 'Name should not be more than 255 characters',
                    'district_id.required'   => 'District is required',
                );
                
                $Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
                if ($Validator->fails()) {
                    return redirect()->back()->withErrors($Validator)->withInput();
                } else {
                    $details->block_name        = trim($request->block_name, ' ');
                    $details->district_id     = $request->district_id;
                    $save = $details->save();                        
                    if ($save) {
                        $request->session()->flash('alert-success', 'Block has been updated successfully');
                        return redirect()->route('admin.block.list', ['page' => $pageNo]);
                    } else {
                        $request->session()->flash('alert-danger', 'An error occurred while updating the block');
                        return redirect()->back();
                    }
                }
            }
            
            
            return view('admin.block.edit')->with(['details' => $details, 'data' => $data, 'districtList' => $districtList]);

        } catch (Exception $e) {
            return redirect()->route('admin.block.list')->with('error', $e->getMessage());
        }
    }

    /*****************************************************/
    # BlockController
    # Function name : Status
    # Author        :
    # Created Date  : 24-09-2020
    # Purpose       : Showing subAdminList of users
    # Params        : Request $request
    /*****************************************************/
    public function status(Request $request, $id = null)
    {
        try
        {
            if ($id == null) {
                return redirect()->route('admin.block.list');
            }
            $details = Block::where('id', $id)->first();
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
                return redirect()->route('admin.block.list')->with('error', 'Invalid block');
            }
        } catch (Exception $e) {
            return redirect()->route('admin.block.list')->with('error', $e->getMessage());
        }
    }

    
    
}
