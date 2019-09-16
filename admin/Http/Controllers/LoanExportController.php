<?php

namespace Admin\Http\Controllers;

use Admin\Exports\ForecastLoanExport;
use App\Model\BorrowerLoan;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
// use Maatwebsite\Excel\Excel;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\Html;
use SleepingOwl\Admin\Contracts\AdminInterface;
use SleepingOwl\Admin\Http\Controllers\AdminController;
use ZipArchive;

class LoanExportController extends Controller
{

    public function __construct(Request $request, AdminInterface $admin, Application $application)
    {

        // $this->excel = $excel;
        parent::__construct($request, $admin, $application);
    }

    /**
     * Экспорт просрочников
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function exportExecutiveInscriptionToWord(\Illuminate\Http\Request $request)
    {
        $ids = explode(",", $request->input("ids"));
        $word_data = $request->input("notary_data");

        if ($request->ajax() && !empty($ids)) {
            $response_data = [];

            $loans = BorrowerLoan::whereIn('id', $ids)->get();
            $word_data["loans"] = $loans;

            $response_data["file_path"] = $this->generateExecutiveInscriptionHTML($word_data);
            $response = new \Illuminate\Http\JsonResponse($response_data);
            return $response;
        }
    }

    public function generateExecutiveInscriptionHTML($word_data)
    {
        $files_for_zip = [];
        foreach($word_data["loans"] as $loan){
            $phpWord = new PhpWord();
            $section = $phpWord->addSection();

            //Генерируем HTML из представления
            $html = view("admin::executive_inscription_item", ["loan" => $loan, "word_data" => $word_data]);

            //Доабвляем HTML в word файл
            Html::addHtml($section, $html, false, false);
            $objWriter = IOFactory::createWriter($phpWord, 'Word2007');

            //Сохраняем сгенерированный WORD файл
            $file_path = 'executive_inscription/executive_inscription_item_'. $loan->id .'.docx';
            $objWriter->save(storage_path($file_path));

            $files_for_zip[] = $file_path;
        }

        $phpWord = new PhpWord();

        $section = $phpWord->addSection();
        $html = view("admin::executive_inscription_list", ["word_data" => $word_data]);

        Html::addHtml($section, $html, false, false);
        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');

        $file_path = 'executive_inscription/executive_inscription_list.docx';
        $objWriter->save(storage_path($file_path));

        $files_for_zip[] = $file_path;
        return $this->addFilesToZip($files_for_zip);
    }

    public function addFilesToZip($files){
        $zip = new ZipArchive;
        $zip_path = 'executive_inscription/executive_inscription.zip';
        unlink(storage_path($zip_path));
        if ($zip->open(storage_path($zip_path), ZipArchive::CREATE) === TRUE)
        {
            foreach($files as $file_path){
                $content = file_get_contents(storage_path($file_path));
                $zip->addFromString(pathinfo ( $file_path, PATHINFO_BASENAME), $content);
            }
            $zip->close();
        }
        return $zip_path;
    }


    /**
     * Экспорт по прогнозируемым заявкам
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function exportLoansToCsv(\Illuminate\Http\Request $request)
    {
        $ids = (!empty($request->input("ids"))) ? explode(",", $request->input("ids")) : [];
        $lf_days = $request->input("lf_days");

        return (new ForecastLoanExport($ids, $lf_days))->download('loan_export.xlsx');

        //return Excel::download(new ForecastLoanExport($ids, $lf_days), 'loan_export.xlsx');
//        dd(new ForecastLoanExport($ids, $lf_days));
//        return (new ForecastLoanExport($ids, $lf_days))->download('loan_export.xlsx');
    }



}
