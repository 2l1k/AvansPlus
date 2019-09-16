<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ExamineBorrowerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ExamineBorrowerCommand:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Исследование действующих заёмщиков';

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
        \App\Jobs\ExamineBorrowersTask::dispatch();
    }
}