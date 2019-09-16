<?php



$router->get('/information', ['as' => 'admin.information', function (\SleepingOwl\Admin\Contracts\Template\TemplateInterface $template) {

    return $template->view(
	    'Define your information here.',
        'Information'
    );

}]);

$router->post('/news/export.json', ['as' => 'admin.news.export', function (\Illuminate\Http\Request $request) {

    $response = new \Illuminate\Http\JsonResponse([
		'title' => 'Congratulation! You exported news.',
		'news' => App\Model\News5::whereIn('id', $request->_id)->get()
	]);

	return $response;

}]);

$router->post('/ajax/loans/export.word', "\Admin\Http\Controllers\LoanExportController@exportExecutiveInscriptionToWord")->name("admin.loans.export_executive_inscription.word");
$router->post('/ajax/loans/export.word', "\Admin\Http\Controllers\LoanExportController@exportExecutiveInscriptionToWord")->name("admin.loans.export_executive_inscription.word");

$router->get('/ajax/loans/forecast/export.excel', "\Admin\Http\Controllers\LoanExportController@exportLoansToCsv")->name("admin.loans.forecast.toExcel");
