<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Faker\Factory;
use Cocur\Slugify\Slugify;
use Rhumsaa\Uuid\Uuid;
use Carbon\Carbon;
use App\Model\Category;
use App\Model\Work;
use App\Model\Template;

class TestTemplateSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		
		$this->command->info('Truncating current templates.');
		DB::table(with(new Template)->getTable())->truncate();

		$slugify = new Slugify();
		$data = [];
		$faker = Factory::create();
		for($a = 0; $a <= 10; $a++){
			
			$data[] = array(
				'uid' => Uuid::uuid4()->toString(),
				'temp_name' => 'Temp '.$a,
				'temp_desc' => $faker->text,
				'slug' => $slugify->slugify('Temp '.$a),
				'backsvg' => $faker->text,
				'frontsvg' => $faker->text,
				'created_at' => Carbon::now(),
				'updated_at' => Carbon::now()
			);
		}
		Template::insert($data);
		$this->command->info('Template Test Data seeded!');


		$this->command->info('Truncating current category templates.');
		DB::table(with(new Tag)->getTable())->truncate();
		$data = [];
		$faker = Factory::create();
		for($a = 1; $a <= 10; $a++){
			
			$data[] = array(
				'category_id' => $a,
				'template_id' => $a
			);
		}
		Tag::insert($data);
		$this->command->info('Category Template Test Data seeded!');

	}

	

}
