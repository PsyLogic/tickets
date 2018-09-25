<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CommentStatus extends Model
{
    protected $table = 'comment_status';
    protected $fillable = ['comment_id', 'owner_id','agent_id'];
    public $timestamps = false;

    public static function getAgentCommentStatus($ticketId){
        return CommentStatus::where('tickets.id',$ticketId)->where('comment_status.agent_status','unread')
            ->leftJoin('comments', 'comment_status.comment_id', '=', 'comments.id')
            ->leftJoin('tickets', 'comments.ticket_id', '=', 'tickets.id')->count();
    }
    public static function getUserCommentStatus($ticketId){
        return CommentStatus::where('tickets.id',$ticketId)->where('comment_status.owner_status','unread')
            ->leftJoin('comments', 'comment_status.comment_id', '=', 'comments.id')
            ->leftJoin('tickets', 'comments.ticket_id', '=', 'tickets.id')->count();
    }
    public static function getAdminCommentStatus($ticketId){
        return CommentStatus::where('tickets.id',$ticketId)->where('comment_status.admin_status','unread')
            ->leftJoin('comments', 'comment_status.comment_id', '=', 'comments.id')
            ->leftJoin('tickets', 'comments.ticket_id', '=', 'tickets.id')->count();
    }
}
