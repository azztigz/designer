<?php namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Session extends Model {

	protected $table = 'sessions';

	protected $fillable = ['user_uid','link','session_start'];

}
