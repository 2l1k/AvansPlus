<?php

namespace App\Jobs;

use App\Helpers\LoanHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ExamineActiveAgreementsTask implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $iin;

    /**
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Производим обработку действующих займов
     *
     * @return void
     */
    public function handle()
    {
        LoanHelper::examineActiveAgreements();
    }
}
