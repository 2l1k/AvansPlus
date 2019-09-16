<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/page/payment', function () {
    return view('pages.payment_info');
})->name('pages.payment_info');

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

$router->get('login', 'Auth\LoginController@showLoginForm')->name('login');

Route::post('/borrower', 'BorrowerController@store')->name("borrower.store");

Route::options('/{any}', function(){ return ''; })->where('any', '.*');

Route::group(["prefix" => "cloudpayments"], function () {
    Route::post('check', 'Loan\CloudpaymentsController@check');
    Route::post('pay', 'Loan\CloudpaymentsController@pay');
    Route::post('fail', 'Loan\CloudpaymentsController@fail');
});

Route::group(['middleware' => 'web'], function () {
    Route::auth();
    //CRON
    Route::get('/CRON/verification', 'CronController@verification');

    //Testing the code
    Route::get('/testModule/findDebtors', 'TestController@findDebtors');
    Route::get('/testModule/findTaxpayers', 'TestController@findTaxpayers');
    Route::get('/testModule/findRestricted', 'TestController@findRestricted');
    Route::get('/testModule/test', 'TestController@test');

    Route::get('/', 'IndexController@index')->name("home.index");


    Route::group(['prefix' => 'borrower', 'as' => 'borrower.'], function () {
        Route::post('/checkConfirmationCode', 'BorrowerController@checkConfirmationCode');
        Route::post('/resetPassword', 'BorrowerController@resetPassword');
        Route::post('/addNewPassword', 'BorrowerController@addNewPassword');
    });


    Route::group(['prefix' => 'account', 'as' => 'account.'], function () {
        Route::post('/login', 'AccountController@login')->name("login");
        Route::get('/logout', 'AccountController@logout')->name("logout");
    });


    Route::resource('loan', 'LoanController')->only([
        "create",
        "edit",
        "store"
    ]);
    Route::get('/home', 'HomeController@index');

    Route::group(['prefix' => 'qiwi', 'as' => 'qiwi.'], function () {
        Route::get('/pay', 'Loan\QiwiController@pay')->name("pay");
    });

});

Route::group(['middleware' => 'App\Http\Middleware\BorrowerMiddleware'], function () {

    Route::resource('borrower', 'BorrowerController')->only([
        "update"
    ]);


    Route::group(['prefix' => 'borrower', 'as' => 'borrower.'], function () {
        Route::post('/updateBankAccountNumber', 'BorrowerController@updateBankAccountNumber');
        Route::get('/loadDocuments', 'BorrowerController@loadDocuments');
        Route::post('/loadDocuments', 'BorrowerController@loadDocuments');
    });

    Route::resource('account', 'AccountController')->only([
        "index", "edit"
    ]);

//    Route::resource('loans', 'LoanController')->only([
//        "create", "show"
//    ]);
    Route::group(['prefix' => 'loan', 'as' => 'loan.'], function () {

        Route::post('/addNewLoan', 'LoanController@addNewLoan')->name("addNewLoan"); //Добавление новго займа
        Route::post('/refuse', 'LoanController@refuse')->name("refuse"); //Отмена займа на шаге оформления
        Route::post('/loadAgreementDocuments', 'LoanController@loadAgreementDocuments');
        Route::post('/sendConfirmationCode', 'LoanController@sendConfirmationCode')->name("sendConfirmationCode");
        Route::post('/checkConfirmationCode', 'LoanController@checkConfirmationCode')->name("checkConfirmationCode");
        Route::post('/sendPledgeAgreement', 'LoanController@sendPledgeAgreement')->name("sendPledgeAgreement");
        Route::get('/repayment', 'Loan\PaymentController@repayment')->name("repayment");
        Route::get('/extend', 'Loan\PaymentController@extend')->name("extend");
        Route::post('/active/checkStatus', 'LoanController@checkStatus')->name("active.checkStatus");

    });

});


Route::group(['prefix' => '/images/uploads/account/'], function () {
		Route::get("{borrower_id}/documents/{name}", function ($borrower_id, $name) {


			if (!file_exists(storage_path("/images/uploads/account/{$borrower_id}/documents/{$name}"))) {
				abort(404);
			}
			$path = storage_path("/images/uploads/account/{$borrower_id}/documents/{$name}");
			$file = File::get($path);
			$type = File::mimeType($path);
			$response = Response::make($file, 200);
			$response->header("Content-Type", $type);
			return $response;

		})->where('name', '(.*)');
	});

Route::group(['prefix' => '/account/'], function () {
    Route::get("{borrower_id}/documents/{name}", function ($borrower_id, $name) {


        if (!file_exists(storage_path("/account/{$borrower_id}/documents/{$name}"))) {
            abort(404);
        }
        $path = storage_path("/account/{$borrower_id}/documents/{$name}");
        $file = File::get($path);
        $type = File::mimeType($path);
        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);
        return $response;

    })->where('name', '(.*)');
});

Route::group(['prefix' => '/executive_inscription/'], function () {
    Route::get('{file_name}', function ($file_name) {

        $path = storage_path("/executive_inscription/{$file_name}");
        if (!file_exists($path)) {
            abort(404);
        }
        $response = Response::make(File::get($path), 200);
        $response->header("Content-Type", File::mimeType($path));
        return $response;

    })->where('name', '(.*)');
});

