<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTemplatesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('templates', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('uid');
			$table->string('temp_name');
			$table->text('temp_desc');
			$table->string('slug');
			$table->string('folder');
			$table->text('backsvg');
			$table->text('frontsvg');
			$table->text('backjpg');
			$table->text('frontjpg');
			$table->timestamps();
		});

		Schema::create('category_template', function(Blueprint $table)
		{
			
			$table->integer('category_id')->unsigned();
			$table->integer('template_id')->unsigned();
			$table->primary(array('category_id', 'template_id'));
			// We'll need to ensure that MySQL uses the InnoDB engine to
			// support the indexes, other engines aren't affected.
			$table->engine = 'InnoDB';
		});

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('templates');
		Schema::drop('category_template');
	}

}
