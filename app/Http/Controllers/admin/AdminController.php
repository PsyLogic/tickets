<?php

namespace App\Http\Controllers\admin;

use App\User;
use Auth;
use Carbon\Carbon;
use Datatables;
use Gate;
use Hash;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Session;

class AdminController extends Controller
{
	//admin user view
	public function index(){
		if(Gate::denies('admin')) {
			abort(403,trans('messages.notAuthorised'));
		}
		//admin user view datatable
		if(\Request::ajax()){
			$admin = User::getAlladmins();
			return Datatables::of($admin)
				->edit_column('name',function($row){
				    return $row->userName;
				})
				->add_column('action', function ($row) {
					 return '<button type="button" class="btn btn-success btn-sm" onclick="showModal('.$row->id.')"  data-pk="'.$row->id.'">Edit</button>
					<button class="btn btn-danger btn-sm" onclick="deleteUser('.$row->id.',\''.$row->userName.'\')">Delete</button>';
				})
				->make(true);
		}
			$data['adminData'] = User::getAlladmins()->take(10)->get();
			$data['total'] = User::where('users.user_type', 'admin')->count();

			$data['activeOpenAdmin'] = "active";
			$data['activeUser'] = "active";
			$data['openUser'] = 'open';

			return view('admin.admin.index', $data);

	}

	//admin user create
    public function create()
    {
	    if(Gate::denies('admin')) {
		    abort(403,trans('messages.notAuthorised'));
	    }
	    $data['activeOpenAdmin'] = "active";
	    return view('admin.admin.create', $data);
    }

	//admin user save
    public function store(Request $request)
    {
	    if(Gate::denies('admin')) {
		    abort(403, trans('messages.notAuthorised'));
	    }
	    if (\Request::ajax()){
			$admin = new User();
		    $this->validate($request,$admin->adminRules);


		    $admin->name = $request->name;
		    $admin->email = $request->email;
		    $admin->password = bcrypt($request->password);
		    $admin->user_type = 'admin';
		    $admin->save();

		    return ['status' => 'success', 'message' => trans('messages.adminAdd')];
	    }
    }

	//admin user edit view
    public function edit($id)
    {

	    if(Gate::denies('admin')) {
		    abort(403, trans('messages.notAuthorised'));
	    }
	    $admin= User::find($id);
	    return view('admin.admin.edit', ['admin' => $admin]);

    }

	//admin user update
	public function update(Request $request, $id)
	{
		if(Gate::denies('admin')) {
			abort(403, trans('messages.notAuthorised'));
		}
		if (\Request::ajax()) {
			$admin =  User::find($id);
			$this->validate($request,$admin->adminUpdateRules($id));

			$admin->name = $request->name;
			$admin->email = $request->email;
			if ($request->has('password')!='') {
				$admin->password = bcrypt($request->password);
			}
			$admin->save();

			return ['status' => 'success', 'message' =>  trans('messages.adminUpdate')];
		}
	}

	//admin user delete
    public function destroy($id)
    {
	    if(Gate::denies('admin')) {
		    abort(403,trans('messages.notAuthorised'));
	    }
	    if(\Request::ajax()) {
		    $admin= User::find($id);
		    $admin->delete();

		    Session::flash('message', trans('messages.adminDelete'));
		    Session::flash('alert-class', 'alert-success');
			Session::flash('toastrTitle', 'success');

		    return ['status' => 'success', 'message' => trans('messages.adminDelete')];
	    }
    }

}
