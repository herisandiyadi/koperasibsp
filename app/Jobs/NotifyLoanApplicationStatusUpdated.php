<?php

namespace App\Jobs;

use App\Notifications\LoanApplicationStatusUpdated;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class NotifyLoanApplicationStatusUpdated implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $tries = 3;
    public $loanApplication;
    public $applicant;

    /**
     * Create a new job instance.
     *
     * NotifyLoanApplicationStatusUpdated constructor.
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
        $this->applicant->notify(new LoanApplicationStatusUpdated($this->loanApplication));
    }
}
