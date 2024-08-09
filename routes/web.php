<?php

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

Auth::routes(['register' => false]);

Route::group([
    'middleware' => [
        'auth',
    ],
], function () {
    Route::get('/', 'IncomeController@index')->name('home');
    Route::get('/update-view', 'HomeController@updateView')->name('update-view');
});

Route::group([
    'middleware' => [
        'auth',
        'root'
    ],
], function () {
    Route::resource('employees', 'EmployeesController');
    Route::post('payment/{income}', 'PaymentController@store')
        ->name('payment.store');
    Route::post('store-tracker', 'HomeController@store')
        ->name('track-store');
    Route::delete('payment/{income}/{payment}', 'PaymentController@destroy')
        ->name('payment.destroy');

    Route::resource('expenses-categories', 'ExpensesCategories', [
            'except' => ['destroy']
        ]
    );
    Route::resource('recurring-expenses', 'RecurringExpenses', [
            'except' => ['destroy']
        ]
    );
    Route::resource('recurring-income', 'RecurringIncome');
    Route::get('expenses/depts', 'ExpensesListController@depts')->name('expenses.depts');
    Route::get('expenses/report', 'ExpensesListController@report')->name('expenses.report');
    Route::resource('rateHistory', 'EmployeeRateHistoryController');
    Route::resource('expenses', 'ExpensesListController');
    Route::resource('income-categories', 'IncomeCategoriesController');
    Route::resource('incomecost', 'IncomeCostController');
    Route::get('income/company/{company}', 'IncomeController@change')->name('income.company');
    Route::get('income/plans', 'IncomeController@plans')->name('income.plans');
    Route::resource('income', 'IncomeController');
    Route::get('income-table', 'IncomeTableController@index');
    Route::get('pre-income-table', 'IncomeTableController@short');
    Route::resource('precome', 'PrecomeController');
    Route::get('expenses-table', 'ExpenseTableController@index');
    Route::get('client-table', 'ClientTableController@index');
    Route::resource('import', 'ImportController');
    Route::resource('client-contact', 'ClientContactController', [
            'only' => ['store', 'destroy']
        ]
    );
    Route::resource('income-mail', 'IncomeEmailsController', [
            'only' => ['store', 'create']
        ]
    );
    Route::resource('projects', 'ProjectsController');

    Route::get('report/main', 'MainReportController@index')->name('report.main');
    Route::get('report/demo', 'MainReportController@demo')->name('report.demo');
    Route::get('calculator', 'ProjectTimeCalculator@index')->name('calculator');
    Route::resource('url', 'UrlController');
    Route::get('urlTable', 'UrlTableController@index');
    Route::resource('service', 'ServicesController');
    Route::get('invoice-report/{date}', 'InvoiceReportController@zip')->name('zip');

    Route::resource('clients', 'ClientsController');

    Route::get('invoice/{income}/{language}', 'InvoiceController@download')->name('download-invoice');
    Route::get('report/{income}/{language}', 'InvoiceController@report')->name('download-report');

    Route::resource('news', 'NewsController');
    Route::resource('logos', 'LogosController');
    Route::resource('icons', 'IconsController');
    Route::resource('tasks', 'TasksController')->only(['index']);
    Route::get('tasks/open', 'TasksController@open')->name('tasks.open');
    Route::get('week/current', 'TaskController@current')->name('week.current');
    Route::get('week/{list}', 'TaskController@edit')->name('week.edit');
    Route::post('week/{list}/import', 'TaskController@import')->name('week.import');
    Route::resource('payments', 'PaymentsController')->only(['index']);
});
