<?php

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

Route::view('/', 'guest.welcome');
Route::get('/infaq', 'PublicPagesController@donate')->name('public.donate');

Auth::routes(['register' => false, 'reset' => false]);

Route::group(['prefix' => 'laporan-kas', 'as' => 'public_reports.'], function () {
    Route::get('/', 'Reports\PublicFinanceController@index')->name('index');
    Route::get('/ringkasan', 'Reports\PublicFinanceController@summary')->name('finance.summary');
    Route::get('/per_kategori', 'Reports\PublicFinanceController@categorized')->name('finance.categorized');
    Route::get('/rincian', 'Reports\PublicFinanceController@detailed')->name('finance.detailed');
});

Route::group(['prefix' => 'jadwal', 'as' => 'public_schedules.'], function () {
    Route::get('/', 'PublicScheduleController@today')->name('index');
    Route::get('/hari_ini', 'PublicScheduleController@today')->name('today');
    Route::get('/besok', 'PublicScheduleController@tomorrow')->name('tomorrow');
    Route::get('/pekan_ini', 'PublicScheduleController@thisWeek')->name('this_week');
    Route::get('/pekan_depan', 'PublicScheduleController@nextWeek')->name('next_week');
});

// Change Password Routes
Route::get('change-password', 'Auth\ChangePasswordController@show');
Route::patch('change-password', 'Auth\ChangePasswordController@update')->name('password.change');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/home', 'TransactionsController@index')->name('home');

    /*
     * User Profile Routes
     */
    Route::get('profile', 'Auth\ProfileController@show')->name('profile.show');
    Route::get('profile/edit', 'Auth\ProfileController@edit')->name('profile.edit');
    Route::patch('profile/update', 'Auth\ProfileController@update')->name('profile.update');

    /*
     * Transactions Routes
     */
    Route::get('transaction_search', 'TransactionSearchController@index')->name('transaction_search.index');
    Route::get('transactions/export-csv', 'Transactions\ExportController@csv')->name('transactions.exports.csv');
    Route::get('transactions/{transaction}/print_receipt', 'Transactions\ReceiptPrintController@show')->name('transactions.print_receipt');
    Route::resource('transactions', 'TransactionsController');

    /*
     * Categories Routes
     */
    Route::get('categories/{category}/export-csv', 'Transactions\ExportController@byCategory')->name('transactions.exports.by_category');
    Route::resource('categories', 'CategoriesController');

    /*
     * Report Routes
     */
    Route::group(['prefix' => 'report'], function () {
        Route::get('/', 'Reports\InternalFinanceController@dashboard')->name('reports.index');

        Route::get('/finance/dashboard', 'Reports\InternalFinanceController@dashboard')->name('reports.finance.dashboard');

        Route::get('/finance/summary', 'Reports\InternalFinanceController@summary')->name('reports.finance.summary');
        Route::get('/finance/summary_pdf', 'Reports\InternalFinanceController@summaryPdf')->name('reports.finance.summary_pdf');

        Route::get('/finance/categorized', 'Reports\InternalFinanceController@categorized')->name('reports.finance.categorized');
        Route::get('/finance/categorized_pdf', 'Reports\InternalFinanceController@categorizedPdf')->name('reports.finance.categorized_pdf');

        Route::get('/finance/detailed', 'Reports\InternalFinanceController@detailed')->name('reports.finance.detailed');
        Route::get('/finance/detailed_pdf', 'Reports\InternalFinanceController@detailedPdf')->name('reports.finance.detailed_pdf');
    });

    /*
     * Books Routes
     */
    Route::post('book_switcher', 'BookSwitcherController@store')->name('book_switcher.store');
    Route::get('books/{book}/export-csv', 'Transactions\ExportController@byBook')->name('transactions.exports.by_book');
    Route::patch('books_report_titles/{book}', 'Books\ReportTitleController@update')->name('books.report_titles.update');
    Route::resource('books', 'BookController');

    /*
     * Lang switcher routes
     */
    Route::patch('lang_switch', 'LangSwitcherController@update')->name('lang.switch');

    /*
     * Bank Accounts Routes
     */
    Route::apiResource('bank_accounts', 'BankAccountController');
    Route::apiResource('bank_accounts.balances', 'BankAccounts\BalanceController');

    /*
     * Partner Routes
     */
    Route::apiResource('partners', 'PartnerController');

    /*
     * Lecturings Routes
     */
    Route::resource('friday_lecturings', App\Http\Controllers\FridayLecturingController::class)
        ->parameters(['friday_lecturings' => 'lecturing'])
        ->only(['create', 'store', 'show', 'edit', 'update']);
    Route::resource('lecturings', App\Http\Controllers\LecturingController::class);

    Route::get('masjid_profile', [App\Http\Controllers\MasjidProfileController::class, 'show'])->name('masjid_profile.show');
    Route::get('masjid_profile/edit', [App\Http\Controllers\MasjidProfileController::class, 'edit'])->name('masjid_profile.edit');
    Route::patch('masjid_profile', [App\Http\Controllers\MasjidProfileController::class, 'update'])->name('masjid_profile.update');

    /*
     * Backup Restore Database Routes
     */
    Route::post('database_backups/upload', ['as' => 'database_backups.upload', 'uses' => 'DatabaseBackupController@upload']);
    Route::post('database_backups/{fileName}/restore', ['as' => 'database_backups.restore', 'uses' => 'DatabaseBackupController@restore']);
    Route::get('database_backups/{fileName}/dl', ['as' => 'database_backups.download', 'uses' => 'DatabaseBackupController@download']);
    Route::resource('database_backups', 'DatabaseBackupController', ['except' => ['create', 'show', 'edit']]);

    /*
     * Users Routes
     */
    Route::resource('users', App\Http\Controllers\UserController::class);
});
