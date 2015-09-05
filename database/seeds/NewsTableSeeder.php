<?php

use App\CN\CNNews\News;
use Faker\Factory as Faker;

class NewsTableSeeder extends \Illuminate\Database\Seeder {
	
	/**
	 * Fills the salon users table with fake data
	 */
	public function run() {
		$faker = Faker::create ();
		//foreach ( range ( 1, 10 ) as $index ) {
			News::create ( [
					'newsId' => 1,
					'newsTitle' => 'title',
					'newsDesc' =>'some description',
					'creatorId' => 1,
					'updated_at'=> $faker->dateTime,
					'created_at'=> $faker->dateTime
			]);
		}
	//}
}
