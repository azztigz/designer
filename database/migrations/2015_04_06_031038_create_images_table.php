<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('images', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('uid');
			$table->string('work_id');
			$table->string('fotolia_id');
			$table->string('svg_id');
			$table->string('license');
			$table->string('price');
			$table->string('url');
			$table->text('license_details');
			$table->string('type');
			$table->string('tag');
			$table->string('status');
			$table->text('mediaInfo');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('images');
	}

}
