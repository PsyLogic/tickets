<?php

namespace App\Http\Controllers\admin;

use App\Status;
use Carbon\Carbon;
use Gate;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Session;
use Yajra\Datatables\Datatables;

class StatusController extends Controller
{
	//status view
    public function index()
    {
		if(Gate::denies('admin')) {
			abort(403, trans('messages.notAuthorised'));
		}
		//status datatable view
	    if(\Request::ajax()) {
		    $query = Status::select('id', 'name','color');

			return Datatables::of($query)
				->edit_column('color',function($row) {
					return  '<div class="input-group color" data-color="'.$row->color.'" data-color-format="rgba">'.$row->color.'
                                                        <span class="input-group-btn">
                                                            <button class="btn default" type="button">
                                                                <i style="background-color: '.$row->color.';"></i>&nbsp;</button>
                                                        </span>
                                                    </div>';
				})
				->add_column('action', function ($row) {
					if(strtolower($row->name) == 'pending') {
						return '';
					}
					return '<button type="button" class="btn btn-success btn-sm statusEdit" onclick="showModal('.$row->id.')" data-pk="'.$row->id.'"> Edit </button>
					<button onclick="deleteStatus('.$row->id.',\''.$row->name.'\')" class="btn red btn-sm statusDelete"> Delete </button>';
				})
			->make(true);
	    }

	    $data['activeOpenStatus'] = "active open";
	    $data['active'] = "active";
	    $data['open'] = 'open';

	    $data['statusData'] = Status::select('id', 'name', 'color')->take(10)->get();
	    $data['total'] = Status::count();

	    return view('admin/status/index', $data);
    }

	//status create view
    public function create()
    {
		if(Gate::denies('admin')) {
			abort(403, trans('messages.notAuthorised'));
		}

	    return view('admin/status/create');
    }

	//status create
    public function store(Request $request)
    {
		if(Gate::denies('admin')) {
			abort(403, trans('messages.notAuthorised'));
		}

		if ($request->ajax()) {
			$status = new Status();
		    $this->validate($request,$status->rules);

		    $status->name = $request->name;
		    $status->color = $request->color;
		    $status->save();

			return ['status' => 'success', 'message' => trans('messages.statusAdd')];
		}
    }


	//status edit view
    public function edit($id)
    {
		if(Gate::denies('admin')) {
			abort(403, trans('messages.notAuthorised'));
		}
	    $status = Status::find($id);
	    return view('admin.status.edit', ['status' => $status]);
    }

	//status update
    public function update(Request $request, $id)
    {
		if(Gate::denies('admin')) {
			abort(403, trans('messages.notAuthorised'));
		}

		if ($request->ajax()) {
			$status = Status::find($id);
			$this->validate($request,$status->statusUpdateRules($id));

			$status->name = $request->name;
			$status->color = $request->color;
			$status->save();

			return ['status' => 'success', 'message' => trans('messages.statusUpdate')];
		}
    }

	//status delete
    public function destroy($id)
    {
		if(Gate::denies('admin')) {
			abort(403, trans('messages.notAuthorised'));
		}
	    if(\Request::ajax()) {
		    Status::destroy($id);
		    return ['status' => 'success', 'message' => trans('messages.statusDelete')];
	    }
    }
}
