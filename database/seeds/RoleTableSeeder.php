<?php

use App\CN\CNRoles\Role;
use Faker\Factory as Faker;

class RoleTableSeeder extends \Illuminate\Database\Seeder {
	
	/**
	 * Fills the salon users table with fake data
	 */
	/*public function run() {
		$faker = Faker::create ();
		foreach ( range ( 1, 10 ) as $index ) {
			RoleModel::create ( [
					'msg_title' => $faker->sentence,
					'msg_body' => $faker->paragraph(4),
					'updated_at' => $faker->dateTime,
					'created_at' => $faker->dateTime
			] );
		}
	}*/

	public function run(){

		DB::table('roles')->delete();

		$faker = Faker::create ();

		Role::create(array(
			'roleId' => 1,
			'roleType' => 1,
			'roleName' => 'Admin',
			'updated_at' => $faker->dateTime,
			'created_at' => $faker->dateTime
		));

	}
}