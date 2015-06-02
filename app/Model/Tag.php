<?php namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model {

	protected $table = 'category_template';

	public function templates()
    {
        return $this->hasMany('App\Model\Template','id');
    }
}
