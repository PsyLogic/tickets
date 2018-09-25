<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $table = 'status';
	protected $fillable = ['name', 'color'];
	public $timestamps = false;
	public $rules = array('name' => 'required|max:30|unique:status,name', 'color' => 'required');

	public function statusUpdateRules($id){
		return array('name'  => 'required|max:30|unique:status,' . 'name,' . $id,
			'color' => 'required');
	}

	public function scopeGetStatus($query){
		return $query->lists('name','id');
	}

}
