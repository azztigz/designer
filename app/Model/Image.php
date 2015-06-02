<?php namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Image extends Model {

	protected $table = 'images';

	protected $fillable = ['uid','work_id','fotolia_id','price','url','license_details','type','tag','status','mediaInfo'];

}
