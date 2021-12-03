<?php

namespace App\Jobs;

use App\GeneralSetting;
use App\Member;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class MemberDepositGenerator implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $cutOff = GeneralSetting::select('content')->where('name', '=', 'cut-off')->first();
        if(!empty($cutOff)){
            $members = Member::fActive()->fVerified()->get();
            foreach ($members as $member) {
                $member->storeMonthlyDeposit();
            }
        }
    }
}
