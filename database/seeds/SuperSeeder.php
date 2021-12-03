<?php

use Illuminate\Database\Seeder;
use Faker\Generator as Faker;

class SuperSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
            $user = App\User::create([
                'name'           => 'super',
                'email'          => 'su@bsp.co.id',
                'username'       => 'superadmin',
                'password'       => Hash::make('demo123'),
                'remember_token' => str_random(10),
                'position_id'    => 1,
                'created_at'  	 => new DateTime,
                'updated_at'  	 => new DateTime,
            ]);
            $user->assignRole('SUPERADMIN');
    }
}
