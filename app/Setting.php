<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';
    public $timestamps = false;

    public $rules = array('name' => 'required|max:30',
        'address' =>'required',
        'email' => 'required|email',
        'phone' => 'required|max:10',
        'logo'  => 'mimes:jpeg,bmp,png');
    public  function rules($id){
        return array('name' => 'required|max:30',
                            'address' =>'required',
                            'email' => 'required|email|unique:settings,' . 'email,' . $id,
                            'phone' => 'required|max:10',
                            'logo'  => 'mimes:jpeg,bmp,png');
    }
}
