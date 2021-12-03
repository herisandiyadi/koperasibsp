<?php

namespace App\Jobs;

use App\Notifications\NewMemberRegistered;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class NotifyNewMemberRegisteredJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $tries = 3;
    public $member;
    protected $notifyThem = [
        'admin_koperasi'
    ];
    /**
     * NotifyNewMemberRegisteredJob constructor.
     * @param $member
     */
    public function __construct($member)
    {
        $this->member = $member;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $users = User::whereHas('position', function($q){
            $q->whereIn('name', $this->notifyThem);
        });

        foreach ($users->get() as $user)
        {
            $user->notify(new NewMemberRegistered($this->member));
        }
    }
}
