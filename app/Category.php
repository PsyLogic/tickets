<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $table = 'categories';
	protected $fillable = ['name', 'color'];
	public $timestamps = false;

	public $rules = array('name' => 'required|max:30|unique:categories', 'color' => 'required');

	public function categoryUpdateRules($id){
		return $rules =array('name'  => 'required|max:30|unique:categories,' . 'name,' . $id,'color' => 'required');
	}

	public function scopeGetCategories($query){
		return $query->lists('name','id');
	}
	public static function agentFilterCategories(){
		return Category::rightJoin('user_categories', 'user_categories.category_id', '=', 'categories.id')
			->lists('categories.name','categories.id');
	}
	public function getTicket($type){
		return Ticket::where('type',$type)->where('category_id',$this->id)->count();
	}

}
