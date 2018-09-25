<?php

namespace App\Http\Controllers\admin;

use App\Category;
use App\User;
use App\UserCategory;

use DB;
use Gate;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Session;
use Yajra\Datatables\Datatables;

class AgentController extends Controller
{

	//agent user view
    public function index()
    {
	    if(Gate::denies('admin')) {
		    abort(403,trans('messages.notAuthorised'));
	    }

		//agent user datatable view
	    if(\Request::ajax()){

		    $agent = User::getAllAgents();
			return Datatables::of($agent)
				->edit_column('name',function($row){
					return $row->userName;
				})
				->add_column('action', function ($row) {
			        return '<button type="button" class="btn btn-success btn-sm " onclick="showModal('.$row->id.')"  data-pk="'.$row->id.'">Edit</button>
					<button onclick="deleteAgent('.$row->id.',\''.$row->userName.'\')" class="btn btn-danger btn-sm">Delete</button>';
			    })
			->make(true);
	    }
	    $categories = Category::getCategories();
	    $agentData = User::getAllAgents()
	                     ->take(10)
	                     ->get();
	    $total = User::where('users.user_type', 'agent')
	                 ->count();
	    $data['agentData'] = $agentData;
	    $data['total'] = $total;

	    $data['categories'] = $categories;

		$data['activeOpenAgent'] = "active";
		$data['activeUser'] = "active";
		$data['openUser'] = 'open';

	    return view('admin.agent.index', $data);

    }

	//agent user create
    public function create()
    {
	    if(Gate::denies('admin')) {
		    abort(403,trans('messages.notAuthorised'));
	    }
	    $categories = Category::getCategories();
	    $data['categories'] = $categories;

	    return view('admin.agent.create', $data);
    }

	//agent user create
    public function store(Request $request)
    {
	    if(Gate::denies('admin')) {
		    abort(403,trans('messages.notAuthorised'));
	    }

	    if (\Request::ajax()){

			$agent = new User();
		    $this->validate($request,$agent->agentRules);

		    $agent->name = $request->name;
		    $agent->email = $request->email;
		    $agent->password = bcrypt($request->password);
		    $agent->user_type = 'agent';
		    $agent->save();

		    $userId=$agent->id;
		    $categories = $request->category;
		    foreach( $categories as $cat)
		    {
			    $userCategories = new UserCategory();
			    $userCategories->user_id = $userId;
			    $userCategories->category_id = $cat;
			    $userCategories->save();
		    }

		    return ['status' => 'success', 'message' => trans('messages.agentAdd')];
	    }
    }

	//agent user edit view
    public function edit($id)
    {
	    if(Gate::denies('admin')) {
		    abort(403,trans('messages.notAuthorised'));
	    }

		$data['categories'] = Category::getCategories();
	    $agent = User::getAgentEdit($id)->get();
		$data['selectedCategory'] = explode(",",$agent[0]->categoryId);
		$data['agent'] = $agent;

		return view('admin.agent.edit',$data);
    }

	//agent user update
    public function update(Request $request, $id)
    {
	    if(Gate::denies('admin')) {
		    abort(403,trans('messages.notAuthorised'));
	    }
	    if (\Request::ajax()) {
			$agent = User::find($id);
			$this->validate($request,$agent->agentUpdateRules($id) );


			$agent->name = $request->name;
			$agent->email = $request->email;
			if ($request->has('password')!='') {
				$agent->password = bcrypt($request->password);
			}
			$agent->save();

			UserCategory::where('user_id', $id)
						->delete();
			$categories = $request->category;
			foreach ($categories as $cat) {
				$userCategories = new UserCategory();
				$userCategories->user_id = $id;
				$userCategories->category_id = $cat;
				$userCategories->save();
			}

			return ['status' => 'success', 'message' => trans('messages.agentUpdate')];
		}

    }

	//agent user dalete
    public function destroy($id)
    {
	    if(Gate::denies('admin')) {
		    abort(403,trans('messages.notAuthorised'));
	    }
	    if(\Request::ajax()) {
		    $user = User::find($id);
		    $user->delete();

		    return ['status' => 'success', 'message' => trans('messages.agentDelete')];
	    }
    }
}
