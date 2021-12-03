<?php

use Illuminate\Database\Seeder;

class RegisterMember extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		factory(App\User::class)->create()->each(function ($user) {

            $member = factory(App\Member::class)->make();
            $user->member()->save($member);

            $deposit = factory(App\DepositTransaction::class, 2)->make();
			$member->deposit()->saveMany($deposit);

			$depositDetail = factory(App\DepositTransactionDetail::class)->make();
            $deposit->detaildeposit()->saveMany($depositDetail);
        });
    }
}
