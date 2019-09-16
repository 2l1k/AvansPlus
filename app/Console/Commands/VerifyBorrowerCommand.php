<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class VerifyBorrowerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'VerifyBorrower:verify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verification of borrowers';

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
        \App\Jobs\VerifyBorrowerTask::dispatch();
    }
}