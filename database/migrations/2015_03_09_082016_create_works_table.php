<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('works', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('uid');
			$table->string('user_uid');
			$table->string('work_title');
			$table->string('work_desc');
			$table->string('slug');
			$table->string('folder');
			$table->text('backsvg');
			$table->text('frontsvg');
			$table->text('backjpg');
			$table->text('frontjpg');
			$table->integer('template_id');
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
		Schema::drop('works');
	}

}
