<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
	protected $table = 'comments';
	protected $fillable = ['comment', 'user_id','ticket_id'];
	public $timestamps = false;
	public $rules = array('comment' => 'required|min:15');

	public function user()
	{
		return $this->belongsTo(User::class,'user_id');
	}

	public function scopeGetTotalComments($query ,$id){
		return $query->whereticket_id($id)
			->leftJoin('users', 'users.id', '=', 'comments.user_id')
			->select('comments.comment','comments.created_at','comments.comment','users.name','users.user_type')->get();
	}


}
