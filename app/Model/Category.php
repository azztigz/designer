<?php namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Category extends Model {

	protected $table = 'categories';

	protected $fillable = ['uid'];

	public function templates()
    {
        return $this->hasMany('App\Model\Tag','category_id');
    }

}
