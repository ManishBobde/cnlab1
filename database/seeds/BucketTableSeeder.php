<?php

use App\CN\CNBuckets\Bucket;
use Faker\Factory as Faker;

class BucketTableSeeder extends \Illuminate\Database\Seeder {

	/**
	 * Fills the salon users table with fake data
	 */
	public function run() {
		$faker = Faker::create ();
		$array=implode(",", ['Inbox','Sent','Draft','Trash']);
		foreach ( range ( 1, 4 ) as $index ) {
			Bucket::create ( [
					'bucketType' => $faker->randomDigit,
					'bucketName' =>$faker->sentence,
					'updated_at' => $faker->dateTime,
					'created_at' => $faker->dateTime
			] );
		}
	}
}