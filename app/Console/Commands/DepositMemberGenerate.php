<?php

namespace App\Console\Commands;

use App\Jobs\MemberDepositGenerator;
use Illuminate\Console\Command;

class DepositMemberGenerate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deposit:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Deposit Member from Config Member';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        MemberDepositGenerator::dispatchNow();
    }
}
