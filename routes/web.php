<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{UpdateController, AuthController};
use App\Http\Controllers\Admin\Customers\{CustomerController, IndividualController, EntityController};
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\AJAXController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\ExpenseController;
use App\Http\Controllers\Admin\AccountingController;
use App\Http\Controllers\Admin\CurrencyController;
use App\Http\Controllers\Admin\PaymentMethodsController;
use App\Http\Controllers\Admin\WagesPayoutController;

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

Route::get('/', function(){return redirect()->route('admin.index');});
Route::view('/signin', 'signin');
Route::view('/login', 'signin')->name('login');
Route::post('auth', AuthController::class)->name('auth');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/update', UpdateController::class);
Route::group(['as' => 'admin.', 'prefix' => 'admin', 'middleware' => 'auth'], function(){
    Route::get('/', DashboardController::class)->name('index');
    Route::get('/orders/buy', [TransactionController::class,'buy_order'])->name('orders.buy');
    Route::get('/orders/sell', [TransactionController::class,'sell_order'])->name('orders.sell');
    Route::get('/orders/upload', [TransactionController::class,'upload'])->name('orders.upload');
    Route::post('/orders/upload/sheet', [TransactionController::class,'upload_sheet']);
    Route::post('/orders/upload/analysis', [TransactionController::class,'upload_analysis']);
    Route::post('/orders/create-from-uploaded-sheet', [TransactionController::class,'create_from_uploaded_sheet']);
    Route::get('/transaction/buy', [TransactionController::class,'transaction_buy'])->name('transaction.buy');
    Route::get('/transaction/sell', [TransactionController::class,'transaction_sell'])->name('transaction.sell');
    Route::get('/transaction/turnover', [TransactionController::class, 'turnover'])->name('transaction.turnover');
    Route::resource('/transaction', TransactionController::class)->except(['create'])->names('transaction');
    Route::get('/tranascation/{id}/print', [TransactionController::class, 'print'])->name('transaction.print');
    Route::get('/tranascation/{id}/pdf', [TransactionController::class, 'pdf'])->name('transaction.pdf');
    Route::get('/settings', SettingsController::class)->name('settings');
    Route::post('/settings/store', [SettingsController::class, 'store'])->name('settings.store');

    Route::get('/customers/all', [CustomerController::class, 'index'])->name('customers.all');
    Route::post('/customers/info/{id}', [CustomerController::class, 'addInfo']);
    Route::get('/customers/info/{id}', [CustomerController::class, 'getInfo']);
    Route::delete('/customers/info/{id?}', [CustomerController::class, 'deleteInfo']);
    Route::get('/customers/{id}/details', [CustomerController::class, 'details']);
    Route::delete('/customers/{id}', [CustomerController::class, 'destroy']);
    Route::get('/customers/{id}/edit', [CustomerController::class, 'edit']);
    Route::resource('/customers/individual', IndividualController::class)->names('customers.individual');
    Route::resource('/customers/entity', EntityController::class)->names('customers.entity');

    Route::group(['prefix' => 'ajax'],  function(){ 
        Route::get('/transactions', [AJAXController::class, 'transactions']);
        Route::get('/customers', [AJAXController::class, 'customers']);
        Route::get('/users', [AJAXController::class, 'users']);
        Route::get('/employees', [AJAXController::class, 'employees']);
        Route::get('/wages-payout', [AJAXController::class, 'wages_payout']);
        Route::get('/expenses', [AJAXController::class, 'expenses']);
        Route::get('/currencies', [AJAXController::class, 'currencies']);
        Route::get('/payment-methods', [AJAXController::class, 'payment_methods']);
        Route::post('/fifo', [AJAXController::class, 'fifo']);
        Route::get('/coins', [AJAXController::class, 'coins']);
        Route::post('/general-journal', [AJAXController::class, 'store_general_journal']);

        Route::post('/account', [AJAXController::class, 'new_account']);
        Route::delete('/account/{account}', [AJAXController::class, 'delete_account']);
        Route::put('/account/{account}', [AJAXController::class, 'edit_account']);

        Route::get('/ledger/{account_id}', [AJAXController::class, 'ledger']);
        Route::get('/revenues_and_expenses', [AJAXController::class, 'revenues_and_expenses']);
        Route::get('/turnover', [AJAXController::class, 'turnover']);

        Route::post('/set-option', [AJAXController::class, 'set_option']);
    });

    Route::resource('/users', UserController::class)->names('users');
    Route::resource('/employees', EmployeeController::class)->names('employees');
    Route::get('/my-profile', [UserController::class, 'show_profile'])->name('profile');
    Route::post('/my-profile/edit', [UserController::class, 'profile_edit'])->name('profile.edit');
    Route::post('/my-profile/changepassword', [UserController::class, 'profile_changepassword'])->name('profile.changepassword');

    Route::group(['prefix' => 'accounting', 'as'=>'accounting'], function(){
        Route::resource('/expenses', ExpenseController::class);
        Route::get('/coa', [AccountingController::class, 'coa']);
        Route::get('/general-journal', [AccountingController::class, 'general_journal']);
        Route::get('/general-journal/entries', [AccountingController::class, 'general_journal_entries']);
        Route::delete('/general-journal/entries/{entry}', [AccountingController::class, 'destroy_general_journal_entries']);
        Route::get('/ledger', [AccountingController::class, 'ledger']);
        Route::get('/trial_balance', [AccountingController::class, 'trial_balance']);
        Route::get('/statement', [AccountingController::class, 'statement']);
        Route::get('/statement/profit_loss', [AccountingController::class, 'statement_profit_loss']);
        Route::get('/reports', [AccountingController::class, 'reports']);
        Route::get('/fifo-grid-pdf', [AccountingController::class, 'fifo_grid_pdf']);
    });

    Route::resource('/currency', CurrencyController::class)->names('currency');
    Route::resource('/payment-methods', PaymentMethodsController::class)->names('payment-methods');

    Route::get('/wages-payout', [WagesPayoutController::class, 'index']);
    Route::get('/wages-payout/{payout}/pdf', [WagesPayoutController::class, 'pdf']);
    Route::get('/paid_wages/{wages_paid}/pdf', [WagesPayoutController::class, 'paid_wages_pdf']);
    Route::post('/wages-payout', [WagesPayoutController::class, 'store']);
    Route::delete('/wages-payout/{payout}', [WagesPayoutController::class, 'destroy']);
    Route::post('/wages-payout/{payout}/pay', [WagesPayoutController::class, 'pay']);
});