<?php

namespace App;

use DB;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{

    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];


	public $adminRules = array(
		'name' => 'required',
		'email' => 'required|email|unique:users',
		'password' => 'required|min:6'
	);
	public $agentRules = array(
		'name' => 'required',
		'email' => 'required|email|unique:users',
		'password' => 'required|min:6',
		'category' => 'required|exists:categories,id'
	);

	public $profileUpdatePassword = array(
		'old_password'=>'required',
		'new_password'=>'required|different:old_password',
		'confirm_password'=>'required|same:new_password'
	);

	public function profileUpdate($id){
		return array(
			'name' => 'required',
			'email' => 'required|email|unique:users,'.'email,'.$id,
		);
	}

	public function adminUpdateRules($id){
		return  array(
			'name' => 'required',
			'email' => 'required|email|unique:users,'.'email,'.$id,
			'password' => 'alpha_num|min:6',
		);
	}
	public function agentUpdateRules($id){
		return array(
			'name'     => 'required',
			'email'    => 'required|email|unique:users,' . 'email,' . $id,
			'password' => 'alpha_num|min:6',
			'category' => 'required|exists:categories,id'
		);
	}
	public function userUpdateRules($id){
		return  array(
			'name' => 'required',
			'email'    => 'required|email|unique:users,' . 'email,' . $id,
		);
	}
	public function scopeGetUsers($query) {
		return $query->Select('users.id','users.name','users.email',DB::raw('count(tickets.subject) as ticketNo'))
		             ->where('user_type','user')
		             ->leftJoin('tickets', 'users.id', '=', 'tickets.owner_id')
		             ->groupBy('users.id');
	}

	public function scopeGetAgents($query,$category)
	{
		return $query->leftJoin('user_categories', 'users.id', '=', 'user_categories.user_id')
		           ->where('user_categories.category_id',$category)
		           ->lists('users.name','users.id');
	}
	public function scopeGetAllAgents($query)
	{
		return $query->select('users.id', 'users.name as userName', 'users.email', DB::raw('GROUP_CONCAT(categories.name)  as categoryNames'))
		             ->where('users.user_type','agent')
		             ->leftJoin('user_categories', 'users.id', '=', 'user_categories.user_id')
		             ->leftJoin('categories', 'user_categories.category_id', '=', 'categories.id')
		             ->groupBy('users.id');
	}
	public function scopeGetAlladmins($query)
	{
		return $query->select('users.id', 'users.name as userName', 'users.email')
		             ->where('users.user_type','admin')
		             ->groupBy('users.id');
	}


	public function scopeGetAgentEdit($query,$id)
	{
		return $query->select('users.id','users.name as userName', 'users.email', DB::raw('GROUP_CONCAT(categories.id)  as categoryId'))
		             ->where('users.id',$id)
		             ->leftJoin('user_categories', 'users.id', '=', 'user_categories.user_id')
		             ->leftJoin('categories', 'user_categories.category_id', '=', 'categories.id')
		             ->groupBy('users.id');
	}

	public function scopeGetTicketOwnerOrAgent($query,$owner,$agent){
		return $query->select('name','email')->where('id',$owner)->orWhere('id',$agent);
	}

	public function totalUserTickets($type)
	{
		return Ticket::where('type', $type)
				->where('owner_id',$this->id)
				->count();
	}
	public function totalAgentTickets($type)
	{
		return Ticket::where('type', $type)
		      ->where(function ($query) {
			      $query->orWhere('agent_id',$this->id);
		      })
		      ->count();
	}

	public function agentCreatedTickets()
	{
		return Ticket::where(function ($query) {
			             $query->orWhere('owner_id',$this->id);
		             })
		             ->count();
	}
	public function agentAllTickets($type)
	{
		return Ticket::where('type', $type)
					->where(function ($query) {
						$query->orWhere('owner_id',$this->id)
						->orWhere('agent_id',$this->id);
		            })
		             ->count();
	}
	public function agentAssignedTickets()
	{
		return Ticket::where(function ($query) {
			             $query->orWhere('agent_id',$this->id);
		             })
		             ->count();
	}

	public function userCreatedTickets()
	{
		return Ticket::where(function ($query) {
			$query->orWhere('owner_id',$this->id);
		})
		->count();
	}


}
