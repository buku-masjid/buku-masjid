<?php

namespace App\Providers;

use App\Models\Book;
use Illuminate\Auth\SessionGuard;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        require_once app_path().'/Helpers/functions.php';
        require_once app_path().'/Helpers/date_time.php';
        Paginator::useBootstrap();

        // Ref: https://dzone.com/articles/how-to-use-laravel-macro-with-example
        SessionGuard::macro('activeBook', function () {
            $activeBookId = $this->getSession()->get('active_book_id') ?: config('masjid.default_book_id');
            $activeBook = Book::find($activeBookId);
            if (is_null($activeBook)) {
                $activeBook = Book::find(config('masjid.default_book_id'));
            }
            return $activeBook;
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
