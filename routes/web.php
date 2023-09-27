<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//Route::group(['middleware' => ['admin.verify']], function () {
//    Route::get('/register', 'Auth\RegisterController@showRegistrationForm');
//    Route::post('/register', 'Auth\RegisterController@register');
//});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/', function () {
    $user_data = Auth::user();

    return view('home',get_defined_vars());

})->middleware('auth');

Route::get('/InP', function () {
    return view('instructionsPage');
});


#Route::get('enter_notes', function () {
#    return view('EnterDailyNotes');
#})->name('enterNotes');

Route::group(['prefix'=> 'AdminPanel' , 'middleware'=>['auth','admin.verify']], function () {

    Route::get('ChangeVersionNumberForm', 'App\Http\Controllers\AdminController@change_version_numberForm')->name('ChangeVersionNumber.Form');
    Route::post('ApplyChangeVersionNumber', 'App\Http\Controllers\AdminController@ApplyChangeVersionNumber')->name('NewVersion.apply');


});
Route::group(['prefix'=> 'DailyAccounts' ], function () {

    Route::get('summary-page', 'App\Http\Controllers\DailyController@DailySummary')->name('DailySummary.show');
    Route::get('print_daily_data', 'App\Http\Controllers\DailyController@print_daily_date')->name('DailyData.print');

});



Route::group(['prefix'=> 'DailyNotes' , 'middleware'=>'auth'], function () {
    Route::get('DailyNotesShow', 'App\Http\Controllers\DailyController@DailyNotesShow')->name('DailyNotes.show');
    Route::post('DailyNotesShow', 'App\Http\Controllers\DailyController@DailyNotesWithDate')->name('notesWithDate.show');


    Route::get('enterDailyNotes', 'App\Http\Controllers\DailyController@enterDailyNotes')->name('DailyNotesForm');
    Route::post('storenotes', 'App\Http\Controllers\DailyController@storenotes')->name('notes.store');
    Route::get('noteDelete/{id}', 'App\Http\Controllers\DailyController@noteDelete')->name('note.Delete');
    Route::get('noteEdit/{id}', 'App\Http\Controllers\DailyController@noteEdit')->name('note.edit');
    Route::post('noteUpdate/{id}', 'App\Http\Controllers\DailyController@noteUpdate')->name('note.update');

});
Route::group(['prefix'=> 'DailyForms' , 'middleware'=>'auth'], function (){

    Route::get('SalesForm', 'App\Http\Controllers\DailyController@SalesForm')->name('SalesForm');
    Route::post('StoreSales', 'App\Http\Controllers\DailyController@StoreSales')->name('sales.store');


    Route::get('PlatformBalance', 'App\Http\Controllers\DailyController@PlatformsBalanceForms')->name('PlatformBalanceForm');
    Route::Post('StorePlatformBalance', 'App\Http\Controllers\DailyController@StorePlatformBalance')->name('PlatformBalance.store');


    Route::get('OutForm', 'App\Http\Controllers\DailyController@OutForm')->name('OutsForm');
    Route::Post('StoreOuts', 'App\Http\Controllers\DailyController@StoreOuts')->name('Outs.store');


    Route::get('LendForm', 'App\Http\Controllers\DailyController@LendForm')->name('LendForm');
    Route::Post('StoreLend', 'App\Http\Controllers\DailyController@StoreLend')->name('Lends.store');



    Route::get('payToMerchantForm', 'App\Http\Controllers\DailyController@payToMerchantForm')->name('payToMerchantForm');
    Route::Post('StorePaytoMerchant', 'App\Http\Controllers\DailyController@store_pay_to_merchant')->name('payToMerchant.store');



    Route::get('DealersBuyForm', 'App\Http\Controllers\DailyController@DealersBuyForm')->name('DealersBuyForm');
    Route::post('StoreDealersBuy', 'App\Http\Controllers\DailyController@StoreDealersBuy')->name('DealerBuy.store');

    Route::get('CustomersPaymentForm', 'App\Http\Controllers\DailyController@CustomersPaymentForm')->name('CustomersPaymentForm');
    Route::post('StoreCustomerPay', 'App\Http\Controllers\DailyController@StoreCustomerPay')->name('CustomerPay.store');


    Route::post('StoreEndDaily', 'App\Http\Controllers\DailyController@storeenddaily')->name('EndDaily.store');

});


Route::group(['prefix'=> 'DataShow' , 'middleware'=>'auth'], function () {

    Route::get('SalesShow', 'App\Http\Controllers\DailyController@SalesShow')->name('sales.show');
    Route::post('SalesShow', 'App\Http\Controllers\DailyController@SalesShowWhithDates')->name('SalesShow.apply.dates');
    Route::get('SalesDelete/{id}', 'App\Http\Controllers\DailyController@SalesDelete')->name('Sales.Delete');
    Route::get('EditSales/{id}', 'App\Http\Controllers\DailyController@SalesEdit')->name('sales.edit');
    Route::post('SalesUpdate/{id}', 'App\Http\Controllers\DailyController@SalesUpdate')->name('Sales.Update');



    Route::get('CustomerPaymentsShow', 'App\Http\Controllers\DailyController@CustomerPaymentsShow')->name('CustomerPay.show');
    Route::post('CustomerPaymentsShow', 'App\Http\Controllers\DailyController@CustomerPaymentsShowWithDate')->name('CustomerPayWithDate.show');
    Route::get('CustomerPayment/{id}', 'App\Http\Controllers\DailyController@CustomerPaymentDelete')->name('CustomerPayment.Delete');
    Route::get('EditCustomerPayment/{id}', 'App\Http\Controllers\DailyController@CustomerPaymentEdit')->name('CustomerPayment.edit');
    Route::post('CustomerPaymentUpdate/{id}', 'App\Http\Controllers\DailyController@CustomerPaymentUpdate')->name('CustomerPayment.Update');

    Route::get('LoansShow', 'App\Http\Controllers\DailyController@LoansShow')->name('Loans.show');
    Route::post('LoansShow', 'App\Http\Controllers\DailyController@LoansShowWithDate')->name('LoansShowWithDate.show');
    Route::get('Loans/{id}', 'App\Http\Controllers\DailyController@LoansDelete')->name('Loans.Delete');
    Route::get('EditLoans/{id}', 'App\Http\Controllers\DailyController@LoansEdit')->name('Loans.edit');
    Route::post('LoansUpdate/{id}', 'App\Http\Controllers\DailyController@LoansUpdate')->name('Loans.Update');



    Route::get('OutsShow', 'App\Http\Controllers\DailyController@OutsShow')->name('Outs.show');
    Route::post('OutsShow', 'App\Http\Controllers\DailyController@OutsShowWithDate')->name('OutsShowWithDate.show');
    Route::get('Outs/{id}', 'App\Http\Controllers\DailyController@OutsDelete')->name('Outs.Delete');
    Route::get('EditOuts/{id}', 'App\Http\Controllers\DailyController@OutsEdit')->name('Outs.edit');
    Route::post('OutsUpdate/{id}', 'App\Http\Controllers\DailyController@OutsUpdate')->name('Outs.Update');



    Route::get('PurchasesShow', 'App\Http\Controllers\DailyController@PurchasesShow')->name('Purchases.show');
    Route::post('PurchasesShow', 'App\Http\Controllers\DailyController@PurchasesShowWithDate')->name('PurchasesWithDate.show');
    Route::get('Purchases/{id}', 'App\Http\Controllers\DailyController@PurchasesDelete')->name('Purchases.Delete');
    Route::get('EditPurchases/{id}', 'App\Http\Controllers\DailyController@PurchasesEdit')->name('Purchases.edit');
    Route::post('PurchasesUpdate/{id}', 'App\Http\Controllers\DailyController@PurchasesUpdate')->name('Purchases.Update');


    Route::get('PlatformBalanceShow', 'App\Http\Controllers\DailyController@PlatformBalanceShow')->name('PlatformBalance.show');
    Route::post('PlatformBalanceShow', 'App\Http\Controllers\DailyController@PlatformBalanceShowWithDate')->name('PlatformBalanceShowWithDate.show');
    Route::get('PlatformBalance/{id}', 'App\Http\Controllers\DailyController@PlatformBalanceDelete')->name('PlatformBalance.Delete');
    Route::get('EditPlatformBalance/{id}', 'App\Http\Controllers\DailyController@PlatformBalanceEdit')->name('PlatformBalance.edit');
    Route::post('PlatformBalanceUpdate/{id}', 'App\Http\Controllers\DailyController@PlatformBalanceUpdate')->name('PlatformBalance.Update');

    Route::get('balance-sales-show', 'App\Http\Controllers\DailyController@balance_sales_show')->name('BalanceSales.show');
    Route::post('balance-sales-show', 'App\Http\Controllers\DailyController@balanceSalesShowWithDate')->name('balanceSalesShowWithDate.show');

    Route::get('PayMerchantShow', 'App\Http\Controllers\DailyController@PayMerchantShow')->name('PayMerchant.show');
    Route::post('PayMerchantShow', 'App\Http\Controllers\DailyController@MerchantPaysWithDate')->name('MerchantPaysWithDate.show');
    Route::get('PayMerchant/{id}', 'App\Http\Controllers\DailyController@PayMerchantDelete')->name('PayMerchant.Delete');
    Route::get('EditPayMerchant/{id}', 'App\Http\Controllers\DailyController@PayMerchantEdit')->name('PayMerchant.edit');
    Route::post('PayMerchantUpdate/{id}', 'App\Http\Controllers\DailyController@PayMerchantUpdate')->name('PayMerchant.Update');




});

