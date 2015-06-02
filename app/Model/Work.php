<?php namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Work extends Model {

	protected $table = 'works';

	protected $fillable = ['uid','user_uid','work_title','work_desc','slug','backsvg','frontsvg','backjpg','frontjpg','template_id'];

}
