<?php

namespace App\Helpers;

use App\Model\BorrowerLoan;
use App\Services\LoanService;

class LoanHelper
{

    /**
     * Исследованеи активных займов
     *
     * @param array $ids
     */
    public static function examineActiveAgreements($ids = [])
    {
        $active_loans_query = BorrowerLoan::activeLoan()->approved();

        if (!empty($ids)) {
            $active_loans_query->whereIn("id", $ids);
        }

        $active_loans = $active_loans_query->get();

        foreach ($active_loans as $loan) {
            $loan_new_data = LoanService::recalculateLoanData($loan);
            $loan->fill($loan_new_data)
                ->save();

            //Если займ просрочен, меняем статус
            if (strtotime($loan->expiration_date) < strtotime(date("Y-m-d H:i:s")) && $loan->loan_status_id != StatusHelper::LOAN_IS_OVERDUE) {
                $loan->update([
                    "loan_status_id" => StatusHelper::LOAN_IS_OVERDUE
                ]);
            } elseif ($loan->loan_status_id == StatusHelper::LOAN_IS_OVERDUE && !empty($loan->extension_date) && strtotime($loan->expiration_date) > strtotime(date("Y-m-d H:i:s"))) {
                $loan->update([
                    "loan_status_id" => StatusHelper::LOAN_EXTENDED
                ]);
            }
        }
    }

}
