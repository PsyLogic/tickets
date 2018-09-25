<?php

namespace App\Http\Controllers\admin;

use App\Priority;
use Carbon\Carbon;
use Gate;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Session;
use Yajra\Datatables\Datatables;

class PriorityController extends Controller
{
	//Priority  view
    public function index()
    {
		if(Gate::denies('admin')) {
			abort(403, trans('messages.notAuthorised'));
		}
		//Priority datatable view
	    if(\Request::ajax()){
		    $priority = Priority::select('id', 'name','color');

		    return Datatables::of($priority)
			    ->edit_column('color',function($row){
				    return  '<div class="input-group color colorpicker-default" data-color="'.$row->color.'" data-color-format="rgba">
								'.$row->color.'
                                <span class="input-group-btn">
                                    <button class="btn default" type="button">
                                        <i style="background-color: '.$row->color.';"></i>&nbsp;
					                </button>
                                </span>
                             </div>';
			    })
				->add_column('action', function ($row) {
				 return '<button class="btn btn-success btn-sm " onclick="showModal('.$row->id.')" data-pk="'.$row->id.'">Edit</button>
						 <button onclick="deletePriority('.$row->id.',\''.$row->name.'\')" class="btn red btn-sm priorityDelete">Delete</button>';
				 })
			->make(true);
	    }

		$data['priorityData'] = Priority::select('id', 'name', 'color')->take(10)->get();
		$data['total'] = Priority::all()->count();

	    $data['activeOpenPriority'] = "active open";
	    $data['active'] = "active";
	    $data['open'] = 'open';

	    return view('admin/priority/index', $data);
    }

	//Priority create view
    public function create()
    {
		if(Gate::denies('admin')) {
			abort(403, trans('messages.notAuthorised'));
		}

		return view('admin/priority/create');
    }

	//Priority create
    public function store(Request $request)
    {
		if(Gate::denies('admin')) {
			abort(403, trans('messages.notAuthorised'));
		}

	    if (\Request::ajax()){
			$priority = new Priority();
		    $this->validate($request,$priority->rules);

		    $priority->name = $request->name;
		    $priority->color = $request->color;
		    $priority->save();

		    return ['status' => 'success', 'message' => trans('messages.priorityAdd')];
	    }
    }


	//Priority edit view
    public function edit($id)
    {
		if(Gate::denies('admin')) {
			abort(403, trans('messages.notAuthorised'));
		}

	    $prioritiesData = Priority::find($id);
	    return view('admin.priority.edit',['prioritiesData'=>$prioritiesData]);
    }

	//Priority update
    public function update(Request $request, $id)
    {
		if(Gate::denies('admin')) {
			abort(403, trans('messages.notAuthorised'));
		}
	    if (\Request::ajax()) {
			$priorityUpdate = Priority::find($id);
		    $this->validate($request,$priorityUpdate->priorityUpdateRules($id));

		    $priorityUpdate->name = $request->name;
		    $priorityUpdate->color = $request->color;
		    $priorityUpdate->updated_at = Carbon::now();
		    $priorityUpdate->save();

		    return ['status' => 'success', 'message' => trans('messages.priorityUpdate')];
		}
    }

	//Priority delete view and delete
    public function destroy($id)
    {
		if(Gate::denies('admin')) {
			abort(403, trans('messages.notAuthorised'));
		}
	    if(\Request::ajax()) {
			    $priority = Priority::find($id);
			    $priority->delete();
			    return ['status' => 'success', 'message' => trans('messages.priorityDelete')];
		    }

    }
}
