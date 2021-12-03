<?php

use Illuminate\Database\Seeder;

class PowerUser extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = App\User::create([
            'name'           => "POWER ADMIN",
            'email'          => 'powered@develoop.in',
            'username'       => 'pow-sugoi',
            'password'       =>  Hash::make('infinitel00p!'),
            // hasil encrypt "$2y$10$opEu/SFOw0gjiLKCDajQuOF97wO.xiOA6UzitB1W08KmvEIfvNf5G"
            'remember_token' => str_random(10),
            'position_id'    => 1,
            'created_at'  	 => new DateTime,
            'updated_at'  	 => new DateTime,
        ]);

        $user->assignRole('POWERADMIN');
    }
}
