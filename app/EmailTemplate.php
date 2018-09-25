<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    protected $table = 'email_templates';
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'subject','content'];
    public $incrementing = false;
    public $timestamps = false;

    public $rules = array('subject' => 'required|max:20','contents'=>'required|max:255');

    public static function getEmailTemplate($email_id){
        return self::find($email_id);
    }
}
