<?php

namespace App\Helpers;


use App\Model\BorrowerLoan;
use App\Presenters\LoanPresenter;
use PDF;

class ContractHelper
{
    /**
     * Генерация договора залога
     *
     * @param $loan_id
     */
    public static function generatePledgeAgreement(BorrowerLoan $loan)
    {
        $loan      = new LoanPresenter($loan);
        $pdf       = PDF::loadView('docs.pdf.bill', ["loan" => $loan]); //with generatePledgeTicket
        $file_path = "account/{$loan->borrower_id}/documents/{$loan->id}_agreement.pdf";

        if (!file_exists(dirname(storage_path($file_path)))) {
            mkdir(dirname(storage_path($file_path)), 0755, true);
        }

        $pdf->save(storage_path($file_path));

        return $file_path;
    }
}
