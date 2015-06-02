<?php namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Template extends Model {

	protected $table = 'templates';

	protected $fillable = ['uid','temp_name','temp_desc','slug','folder','backsvg','frontsvg','backjpg','frontjpg'];
	
	public function category()
    {
        return $this->belongsTo('App\Model\Category', 'id');
    }

    public function categories()
    {
        return $this->belongsToMany('App\Model\Tag','category_template','template_id','category_id');
    }

}