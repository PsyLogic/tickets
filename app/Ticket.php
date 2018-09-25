<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;


class Ticket extends Model
{
	protected $table 	= 'tickets';
	protected $fillable = ['subject', 'description'];
	public $timestamps  = true;


	public static $rules = array(
		'subject' => 'required|max:100',
		'description' => 'required|min:10',
		'priority' => 'required',
		'category' => 'required'
		// .. more rules here ..
	);
	public $updateRules = array(
		'subject' => 'required|max:100',
		'description' => 'required|min:20|',
		'priority' => 'required|exists:priorities,id',
		'agent' => 'required|exists:users,id',
		'status' => 'required|exists:status,id'
		// .. more rules here ..
	);

	public function scopeGetActiveTickets(){
		return Ticket::select('tickets.id','tickets.subject', DB::raw('GROUP_CONCAT(users.name)  as ownerName'),
			DB::raw('(select name from users WHERE id=tickets.agent_id) as agentName'),
			DB::raw('(select name from status WHERE id=tickets.status_id) as statusName'),
			DB::raw('(select name from categories WHERE id=tickets.category_id) as categoryName'),
			DB::raw('(select name from priorities WHERE id=tickets.priority_id) as priorityName'),'tickets.updated_at',
			'status.color as statusColor','priorities.color as priorityColor','categories.color as categoryColor')
			->where('tickets.type','opened')
			->leftJoin('status', 'tickets.status_id', '=', 'status.id')
			->leftJoin('users', 'users.id', '=', 'tickets.owner_id')
			->leftJoin('categories', 'tickets.category_id', '=', 'categories.id')
			->leftJoin('priorities', 'tickets.priority_id', '=', 'priorities.id')
			->groupBy('tickets.id');

	}

	public function scopeGetCompletedTickets($query){
		return $query->select('tickets.id','tickets.subject', DB::raw('GROUP_CONCAT(users.name)  as ownerName'),
			DB::raw('(select name from users WHERE id=tickets.agent_id) as agentName'),
			DB::raw('(select name from status WHERE id=tickets.status_id) as statusName'),
			DB::raw('(select name from categories WHERE id=tickets.category_id) as categoryName'),
			DB::raw('(select name from priorities WHERE id=tickets.priority_id) as priorityName'),'tickets.updated_at',
			'status.color as statusColor','priorities.color as priorityColor','categories.color as categoryColor','tickets.created_at','tickets.type')
             ->where('tickets.type','closed')
             ->leftJoin('status', 'tickets.status_id', '=', 'status.id')
             ->leftJoin('users', 'users.id', '=', 'tickets.owner_id')
             ->leftJoin('categories', 'tickets.category_id', '=', 'categories.id')
             ->leftJoin('priorities', 'tickets.priority_id', '=', 'priorities.id')
             ->groupBy('tickets.id');
	}

	public function scopeGetTicketDetail($query,$id){
		return $query->select('tickets.id','tickets.subject', DB::raw('GROUP_CONCAT(users.name)  as ownerName'),
			DB::raw('(select name from users WHERE id=tickets.agent_id) as agentName'),
			DB::raw('(select name from status WHERE id=tickets.status_id) as statusName'),
			DB::raw('(select name from categories WHERE id=tickets.category_id) as categoryName'),
			DB::raw('(select name from priorities WHERE id=tickets.priority_id) as priorityName'),'tickets.updated_at','tickets.created_at',
			'status.color as statusColor','priorities.color as priorityColor','categories.color as categoryColor','tickets.description','tickets.type','tickets.owner_id','tickets.agent_id')
             ->where('tickets.id',$id)
             ->leftJoin('status', 'tickets.status_id', '=', 'status.id')
             ->leftJoin('users', 'users.id', '=', 'tickets.owner_id')
             ->leftJoin('categories', 'tickets.category_id', '=', 'categories.id')
             ->leftJoin('priorities', 'tickets.priority_id', '=', 'priorities.id')
             ->groupBy('tickets.id');
	}

	public function scopeGetTicketEditDetail($query,$id){
		return $query->select('tickets.id','tickets.subject','tickets.description','tickets.category_id as categoryId',
			'tickets.status_id as statusId','tickets.priority_id as priorityId','tickets.agent_id as agentId','tickets.type')
             ->where('tickets.id', $id)
             ->leftJoin('status', 'tickets.status_id', '=', 'status.id')
             ->leftJoin('users', 'users.id', '=', 'tickets.owner_id')
             ->leftJoin('categories', 'tickets.category_id', '=', 'categories.id')
             ->leftJoin('priorities', 'tickets.priority_id', '=', 'priorities.id')
             ->groupBy('tickets.id');
	}

	public function scopeGetAllTickets(){
		return Ticket::all();
	}
	public function comments(){
		return $this->hasMany('App\Comment','ticket_id','id');
	}
}
