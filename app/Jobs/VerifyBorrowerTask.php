<?php

namespace App\Jobs;

use App\Helpers\VerificationHelper;
use App\Model\VerifiedDebtor;
use App\Model\VerifiedRestricted;
use App\Model\VerifiedTaxpayer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class VerifyBorrowerTask implements ShouldQueue
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
     * Начинаем парсинг данных с egov
     *
     * @return void
     */
    public function handle()
    {
        //Находим все не проверенные ИИНы в таблцие ДОЛЖНИКОВ
        $verified_debtors = VerifiedDebtor::where("is_verified", '<>', 1)->take(20)->get();
        foreach($verified_debtors as $verified_debtor){

            $IIN = $verified_debtor->IIN;
//            Производим парсинг
            $debtors_result = VerificationHelper::findAmongDebtors($IIN);
            if (isset($debtors_result["status"])) {
                $verified_debtor->where("IIN", $IIN);
                if (!empty($debtors_result["pure_table"])) {
                    $verified_debtor->update([
                        "html_result" => $debtors_result["pure_table"],
                        "is_verified" => 1
                    ]);
                } elseif ($debtors_result["status"] == 0) {
                    $verified_debtor->update([
                        "is_verified" => 1
                    ]);
                }
            }

        }


        //Находим все не проверенные ИИНы в таблцие НАЛОГОПЛАТЕЛЬЩИКОВ
        $verified_taxpayers = VerifiedTaxpayer::where("is_verified", '<>', 1)->take(20)->get();
        foreach($verified_taxpayers as $verified_taxpayer)
        {
            $IIN = $verified_taxpayer->IIN;

            $taxpayers_result = VerificationHelper::findAmongTaxpayers($IIN);
            if (isset($taxpayers_result["status"])) {
                $verified_taxpayer->where("IIN", $IIN);
                if (!empty($taxpayers_result["pure_table"])) {
                    $verified_taxpayer->update([
                        "html_result" => $taxpayers_result["pure_table"],
                        "is_verified" => 1
                    ]);
                } elseif ($taxpayers_result["status"] == 0) {
                    $verified_taxpayer->update([
                        "is_verified" => 1
                    ]);
                }
            }
        }

        //Находим все не проверенные ИИНы в таблцие временно ограниченных на выезд
        $verified_restricted = VerifiedRestricted::where("is_verified", '<>', 1)->take(20)->get();
        foreach($verified_restricted as $verified_restricted_item)
        {
            $IIN = $verified_restricted_item->IIN;

            $restricted_result = VerificationHelper::findAmongRestricted($IIN);
            if (isset($restricted_result["status"])) {
                $verified_restricted_item->where("IIN", $IIN);
                if (!empty($restricted_result["pure_table"])) {
                    $verified_restricted_item->update([
                        "html_result" => $restricted_result["pure_table"],
                        "is_verified" => 1
                    ]);
                } elseif ($restricted_result["status"] == 0) {
                    $verified_restricted_item->update([
                        "is_verified" => 1
                    ]);
                }
            }
        }

    }

}
