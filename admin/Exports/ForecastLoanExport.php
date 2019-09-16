<?php

namespace Admin\Exports;

use App\Model\BorrowerLoan;
use App\Services\LoanService;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

/*
 * Экпорт заявок в Excel
 */

class ForecastLoanExport implements FromView
{
    use Exportable;

    public function __construct($ids, $lf_days)
    {
        $this->ids = $ids;
        $this->lf_days = $lf_days;
    }

//    public function collection()
//    {
//        if(!empty($this->ids)){
//            $loans =  BorrowerLoan::whereIn('id', $this->ids)->get();
//        }else{
//            $loans = BorrowerLoan::all();
//        }
//        return $loans;
//    }

    public function view(): View
    {
        if (!empty($this->ids)) {
            $loans = BorrowerLoan::whereIn('id', $this->ids)->get();
        } else {
            $loans = BorrowerLoan::all();
        }

        $totals = [
            "total_sum_by_issued" => 0,
            "total_sum_maturity" => 0,
            "total_net_profit" => 0,
        ];


        foreach ($loans as $loan) {
            $loan = LoanService::forecastCalculateLoanData($loan, strtotime($this->lf_days));

            $loan->net_profit = $loan->amount_maturity - $loan->sum;
            $loan->timepoint = date("Y-m-d H:i", strtotime($this->lf_days));

            $totals["total_sum_by_issued"] += $loan->sum;
            $totals["total_sum_maturity"] += $loan->amount_maturity;
            $totals["total_net_profit"] += $loan->net_profit;
        }

        return view('admin::exports.forecast_loans', [
            'loans' => $loans,
            'totals' => $totals,
        ]);
    }


}