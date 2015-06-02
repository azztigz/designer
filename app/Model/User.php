<?php namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class User extends Model {

	protected $table = 'users';

	protected $fillable = ['uid','user_uid','email','password','permission','activated','last_login','persist_code','return_url','authorization_token','verification_key','currency','first_name','last_name'];

}