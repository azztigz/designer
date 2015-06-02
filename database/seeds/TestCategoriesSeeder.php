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
use App\Model\Tag;
use App\Model\User;
use App\Model\Currency;

class TestCategoriesSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */

	public function __construct(){
		$this->slug = new Slugify();
	}

	public function run()
	{

	//Admin User
		$this->command->info('Truncating current Users.');
		DB::table(with(new User)->getTable())->truncate();

		$data = [];
		$faker = Factory::create();
			
			$data[] = array(
				'uid' => Uuid::uuid4()->toString(),
				'email' => 'master@printarabia.ae',
				'password' => '$2y$10$tbU2OtyIk3D6w683sFWmy.o7ijUt2.BcpAwTlkOd4G13nuRcbQUri',
				'permissions' => json_encode(array('user.create'  => 1,
							            'user.delete' => 1,
							            'user.view'   => 1,
							            'user.update' => 1,
							           )),
				'activated'	=> 1,
				'first_name' => 'Admin',
				'last_name' => 'Admin',
				'created_at' => Carbon::now(),
				'updated_at' => Carbon::now()
			);

		User::insert($data);
		$this->command->info('Users Test Data seeded!');

	//Works
		// $this->command->info('Truncating current Works.');
		// DB::table(with(new Work)->getTable())->truncate();

		// $data = [];
		// $faker = Factory::create();
		// for($a = 1; $a <= 4; $a++){
			
		// 	$data[] = array(
		// 		'uid' => Uuid::uuid4()->toString(),
		// 		'work_title' => 'Work '.$a,
		// 		'work_desc' => $faker->text,
		// 		'slug' => $this->slug->slugify('Work '.$a),
		// 		'backsvg' => "0".$a."/Trees_bc-0".$a."-01.svg",
		// 		'frontsvg' => "0".$a."/Trees_bc-0".$a."-02.svg",
		// 		'backjpg' => "0".$a."/Trees_bc-0".$a."-01.jpg",
		// 		'frontjpg' => "0".$a."/Trees_bc-0".$a."-02.jpg",
		// 		'template_id' => $a,
		// 		'created_at' => Carbon::now(),
		// 		'updated_at' => Carbon::now()
		// 	);
		// }

		// $l = 1;
		// for($a = 1; $a <= 30; $a++){
			
		// 	$data[] = array(
		// 		'uid' => Uuid::uuid4()->toString(),
		// 		'work_title' => 'Work '.$a,
		// 		'work_desc' => $faker->text,
		// 		'slug' => $this->slug->slugify('Work '.$a),
		// 		'backsvg' => "0".$l."/Trees_bc-0".$l."-01.svg",
		// 		'frontsvg' => "0".$l."/Trees_bc-0".$l."-02.svg",
		// 		'backjpg' => "0".$l."/Trees_bc-0".$l."-01.jpg",
		// 		'frontjpg' => "0".$l."/Trees_bc-0".$l."-02.jpg",
		// 		'template_id' => $l,
		// 		'created_at' => Carbon::now(),
		// 		'updated_at' => Carbon::now()
		// 	);

		// 	if($l >= 4){
		// 		$l = 1;
		// 	}

		// 	$l++;
		// }

		// Work::insert($data);
		// $this->command->info('Works Test Data seeded!');

	//Categories
		$this->command->info('Truncating current categories.');
		DB::table(with(new Category)->getTable())->truncate();

		$data = [];
		$faker = Factory::create();
		for($a = 1; $a <= 6; $a++){
			
			$data[] = array(
				'uid' => Uuid::uuid4()->toString(),
				'cat_name' => 'Cat '.$a,
				'description' => $faker->text,
				'slug' => $this->slug->slugify('Cat '.$a),
				'created_at' => Carbon::now(),
				'updated_at' => Carbon::now()
			);
		}
		Category::insert($data);
		$this->command->info('Categories Test Data seeded!');

	//Templates
		$this->command->info('Truncating current Templates.');
		DB::table(with(new Template)->getTable())->truncate();

		$data = [];
		$faker = Factory::create();

		for($a = 1; $a <= 6; $a++){

			$data[] = array(
				'uid' => Uuid::uuid4()->toString(),
				'temp_name' => 'Temp '.$a,
				'temp_desc' => $faker->text,
				'slug' => $this->slug->slugify('Temp '.$a),
				'folder' => "0".$a,
				'backsvg' => "Bakery_bc-0".$a."-01.svg",
				'frontsvg' => "Bakery_bc-0".$a."-02.svg",
				'backjpg' => "Bakery_bc-0".$a."-01.jpg",
				'frontjpg' => "Bakery_bc-0".$a."-02.jpg",
				'created_at' => Carbon::now(),
				'updated_at' => Carbon::now()
			);

		}

		/*$l = 1;

		for($a = 1; $a <= 30; $a++){

			$data[] = array(
				'uid' => Uuid::uuid4()->toString(),
				'temp_name' => 'Temp '.$a,
				'temp_desc' => $faker->text,
				'slug' => $this->slug->slugify('Temp '.$a),
				'backsvg' => "0".$l."/Bakery_bc-0".$l."-01.svg",
				'frontsvg' => "0".$l."/Bakery_bc-0".$l."-02.svg",
				'backjpg' => "0".$l."/Bakery_bc-0".$l."-01.jpg",
				'frontjpg' => "0".$l."/Bakery_bc-0".$l."-02.jpg",
				'created_at' => Carbon::now(),
				'updated_at' => Carbon::now()
			);

			if($l >= 8){
				$l = 1;
			}

			$l++;
		}*/


		Template::insert($data);
		$this->command->info('Templates Test Data seeded!');


	//Category_Template
		$this->command->info('Truncating current Category Templates.');
		DB::table(with(new Tag)->getTable())->truncate();

		$data = [];
		$faker = Factory::create();
		
		for($a = 1; $a <= 6; $a++){
			
			$data[] = array(
				'category_id' => $a,
				'template_id' => $a
			);
		}

		// for($a = 1; $a <= 4; $a++){
			
		// 	for($b = 1; $b <= 15; $b++){
		// 		$data[] = array(
		// 			'category_id' => $a,
		// 			'template_id' => $b
		// 		);
		// 	}

		// 	Tag::insert($data);
		// 	unset($data);
		// }

		Tag::insert($data);
		$this->command->info('Category Template Test Data seeded!');

	//Currencies
		$this->command->info('Truncating current Currencies.');
		DB::table(with(new Currency)->getTable())->truncate();

		$data = [];
		$faker = Factory::create();
			
		$data = array(
					array(
						'name' => 'US Dollar',
						'alpha3' => 'USD',
						'country' => 'United States',
						'created_at' => Carbon::now(),
						'updated_at' => Carbon::now()
					),
					array(
						'name' => 'Arab Emirates Dirham',
						'alpha3' => 'AED',
						'country' => 'United Arab Emirates Dirham',
						'created_at' => Carbon::now(),
						'updated_at' => Carbon::now()
					)
				);

		Currency::insert($data);
		$this->command->info('Currencies Test Data seeded!');

	}

	

}
