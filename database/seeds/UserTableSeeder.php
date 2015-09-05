<?php

use App\CN\CNUsers\User;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends \Illuminate\Database\Seeder {
	
	/**
	 * Fills the salon users table with fake data
	 */
	public function run() {
		$faker = Faker::create ();
		//foreach ( range ( 1, 10 ) as $index ) {
			User::create ( [
					'userId' => 1,
					'firstName' => 'CN',
					'lastName' => 'Admin',
					'mobileNumber' => '1234',
					'email' => 'admin@somecollege.com',
					'password' => Hash::make('admin@somecollege'),
					'avatarUrl'=> '/uploads/image.jpg',
					'roleId' => 1,
					'deptId' => 1,
					'collegeId' => 1,
					'slug'=>'CN_Admin',
					'updated_at' => $faker->dateTime, // This is automatically added for the statement : $table->timestamp();
					'created_at' => $faker->dateTime // This is automatically added for the statement : $table->timestamp();
			] );

		User::create ( [
			'userId' => 2,
			'firstName' => 'Man',
			'lastName' => 'Admin',
			'mobileNumber' => '1234',
			'email' => 'admin1@somecollege.com',
			'password' => Hash::make('admin1@somecollege'),
			'roleId' => 1,
			'deptId' => 1,
			'collegeId' => 1,
			'slug'=>'Man_Admin',
			'updated_at' => $faker->dateTime, // This is automatically added for the statement : $table->timestamp();
			'created_at' => $faker->dateTime // This is automatically added for the statement : $table->timestamp();
		] );
		}
	//}
}