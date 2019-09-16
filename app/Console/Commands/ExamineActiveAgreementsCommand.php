<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ExamineActiveAgreementsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ExamineActiveAgreementsCommand:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Исследование (перебор) действующих займов';

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
        \App\Jobs\ExamineActiveAgreementsTask::dispatch();
    }
}