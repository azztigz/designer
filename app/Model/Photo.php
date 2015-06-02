<?php namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model {

	protected $table = 'photos';

	protected $fillable = ['uid','user_uid','image_name','image_path','mime_type'];

}
