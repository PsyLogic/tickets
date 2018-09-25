<?php

namespace App\Http\Controllers\admin;

use App\User;
use DB;
use Gate;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Session;
use Yajra\Datatables\Datatables;

class UserController extends Controller
{
	//user view
    public function index()
    {
	    if(Gate::denies('admin')) {
			abort(403, trans('messages.notAuthorised'));
	    }
		//user datatable
	    if(\Request::ajax()){
			$users = User::getUsers();
			return Datatables::of($users)
				->edit_column('status',function($row){
					return $row->color;
				})
				->add_column('action', function ($row) {
				 return '<button type="button" class="btn btn-success btn-sm " onclick="showModal('.$row->id.')"  data-pk="'.$row->id.'">Edit</button>
				 <button onclick="deleteUser('.$row->id.',\''.$row->name.'\')"  class="btn btn-danger btn-sm">Delete</button>';
			})
			->make(true);
	    }

		$data['userData'] =User::getUsers()->take(5)->get();
		$data['total'] = User::where('user_type','user')->count();
	    $data['activeOpenUser'] = "active open";
		$data['activeUser'] = "active";
		$data['openUser'] = 'open';

	    return view('admin.users.index',$data);
    }

	//user Edit view
    public function edit($id)
    {
	    if(Gate::denies('admin')) {
			abort(403, trans('messages.notAuthorised'));
	    }

	    $userData = User::find($id);
	    return view('admin.users.edit', ['userData' => $userData]);
    }

	//user update
    public function update(Request $request, $id)
    {
	    if(Gate::denies('admin')) {
			abort(403, trans('messages.notAuthorised'));
	    }

	    if (\Request::ajax()) {
			$users =  User::find($id);
		    $this->validate($request,$users->userUpdateRules($id));

		    $users->name = $request->name;
		    $users->email = $request->email;
		    $users->save();

		    return ['status'  => 'success',
					'message' => trans('messages.userUpdate')
					];
		}
    }

	//user Delete
    public function destroy($id)
    {
		if(Gate::denies('admin')) {
			abort(403, trans('messages.notAuthorised'));
		}

		if(\Request::ajax()) {
		    $user = User::find($id);
		    $user->delete();

		    return ['status' => 'success',
					'message' => trans('messages.userDelete')];
		}
    }
}
