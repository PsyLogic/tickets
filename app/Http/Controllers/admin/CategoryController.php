<?php

namespace App\Http\Controllers\admin;

use App\Category;
use Carbon\Carbon;
use Gate;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Session;
use Yajra\Datatables\Datatables;

class CategoryController extends Controller
{
	//category view
    public function index()
    {
		if(Gate::denies('admin')) {
			abort(403, trans('messages.notAuthorised'));
		}
		//category datatable view
	    if(\Request::ajax()){
		    $category = Category::select('id', 'name','color');
		    return Datatables::of($category)
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
				 return '<button class="btn btn-success btn-sm" onclick="showModal('.$row->id.')" data-pk="'.$row->id.'">Edit</button>
				 <button onclick="deleteCategory('.$row->id.',\''.$row->name.'\')" class="btn red btn-sm">Delete</button>';
				})
			->make(true);
	    }

		$data['categoryData'] = Category::select('id', 'name', 'color')->take(10)->get();
		$data['total'] = Category::count();
	    $data['activeOpenCategory'] = "active open";
	    $data['active'] = "active";
	    $data['open'] = 'open';

		return view('admin.category.index', $data);
    }

	//category create view
    public function create()
    {
	    if(Gate::denies('admin')) {
		    abort(403, trans('messages.notAuthorised'));
	    }
	    return view('admin.category.create');
    }

	//category create
    public function store(Request $request)
    {
		if(Gate::denies('admin')) {
			abort(403, trans('messages.notAuthorised'));
		}

	    if (\Request::ajax()){
			$category = new Category();
		    $this->validate($request, $category->rules);

		    $category->name = $request->name;
		    $category->color = $request->color;
		    $category->save();

		    return ['status' => 'success', 'message' => trans('messages.categoryAdd')];
		}
    }

	//category  edit view
    public function edit($id)
    {
		if(Gate::denies('admin')) {
			abort(403, trans('messages.notAuthorised'));
		}

	    $categoryData = Category::find($id);

	    return view('admin.category.edit', ['categoryData' => $categoryData]);
	}

	//category update
    public function update(Request $request, $id)
    {
	    if(Gate::denies('admin')) {
		    abort(403, trans('messages.notAuthorised'));
	    }

	    if (\Request::ajax()) {
			$categoryUpdate = Category::find($id);
		    $this->validate($request,$categoryUpdate-> categoryUpdateRules($id));

		    $categoryUpdate->name = $request->name;
		    $categoryUpdate->color = $request->color;
		    $categoryUpdate->updated_at = Carbon::now();
		    $categoryUpdate->save();

		    return ['status' => 'success', 'message' => trans('messages.categoryUpdate')];
	    }
    }

	//category datlete view and delete
    public function destroy($id)
    {
	    if(Gate::denies('admin')) {
		    abort(403, trans('messages.notAuthorised'));
	    }

	    if(\Request::ajax()) {
		    $category = Category::find($id);
		    $category->delete();

		    return ['status' => 'success', 'message' => trans('messages.categoryDelete')];
	    }
    }
}
