<?php

namespace App\Jobs;

use App\Notifications\LoanApplicationReceived;
use App\Notifications\NewLoanApplication;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Arr;

class NotifyNewLoanApplication implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $tries = 3;
    public $loanApplication;
    public $applicant;
    protected $notifyThem = [
        'admin_koperasi',
        'head_of_area'
    ];
    /**
     * Create a new job instance.
     *
     * NotifyNewLoanApplication constructor.
     * @param $loanApplication
     */
    public function __construct($loanApplication)
    {
        $this->loanApplication = $loanApplication;
        $this->applicant = $loanApplication->member->user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // when applicant is dansek remove dansek from notifyThem
        // dansek will be superior from applicant
//        if($this->applicant->isDansek()){
//            Arr::pull($this->notifyThem, 'dansek');
//        }

        $users = User::whereHas('position', function($q){
            $q->whereIn('name', $this->notifyThem);
        });

        // notify admins
        foreach ($users->get() as $user)
        {
            $user->notify(new NewLoanApplication($this->loanApplication));
        }
        // notify applicant/user
        $this->applicant->notify(new LoanApplicationReceived($this->loanApplication));

        // notify superior
        $superiors = $this->loanApplication->member->superior();
        if($superiors->count() > 0)
        {
            foreach ($superiors->get() as $superior)
            {
                $user = $superior->user;
                $user->notify(new NewLoanApplication($this->loanApplication));
            }
        }
    }
}
