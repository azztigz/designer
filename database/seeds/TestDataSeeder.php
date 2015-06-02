<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class TestDataSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();
		DB::statement('SET NAMES utf8;');
		DB::statement('SET FOREIGN_KEY_CHECKS = 0;');

		$this->call('TestCategoriesSeeder');
		// $this->call('TestTemplateSeeder');
		// $this->call('TestWorksSeeder');
		// $this->call('TestLibrarySeeder');

		
		DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
	}

}
