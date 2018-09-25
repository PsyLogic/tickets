<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Priority extends Model
{/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $table = 'priorities';
	protected $fillable = ['name', 'color'];
	public $timestamps = false;

	public $rules = array('name' => 'required|max:30|unique:priorities', 'color' => 'required');

	public function priorityUpdateRules($id){
		return array('name'  => 'required|max:30|unique:priorities,' . 'name,' . $id,'color' => 'required');
	}
	public function scopeGetPriorities($query){
		return $query->lists('name','id' );
	}

}


