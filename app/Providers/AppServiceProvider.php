<?php

namespace App\Providers;

use App\Models\Book;
use App\User;
use Illuminate\Auth\SessionGuard;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Guards\TokenGuard;
use Laravel\Passport\Passport;

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

        Relation::enforceMorphMap([
            'books' => Book::class,
            'users' => User::class,
        ]);

        // Ref: https://dzone.com/articles/how-to-use-laravel-macro-with-example
        SessionGuard::macro('activeBook', function () {
            $activeBook = Book::find($this->activeBookId());
            if (is_null($activeBook)) {
                $activeBook = Book::find(config('masjid.default_book_id'));
            }

            return $activeBook;
        });
        SessionGuard::macro('activeBookId', function () {
            if (($bookId = request()->get('active_book_id')) && ($nonce = request()->get('nonce'))) {
                $activeBook = Book::find($bookId);
                if (!is_null($activeBook) && $activeBook->nonce == $nonce) {
                    return $bookId;
                }
            }

            return $this->getSession()->get('active_book_id') ?: config('masjid.default_book_id');
        });
        TokenGuard::macro('activeBook', function () {
            $activeBook = Book::find($this->activeBookId());
            if (is_null($activeBook)) {
                $activeBook = Book::find(config('masjid.default_book_id'));
            }

            return $activeBook;
        });
        TokenGuard::macro('activeBookId', function () {
            return request()->get('active_book_id') ?: config('masjid.default_book_id');
        });
        SessionGuard::macro('setActiveBook', function ($activeBookId) {
            $this->getSession()->put('active_book_id', $activeBookId);
        });

        View::composer(['layouts._top_nav_active_book'], function ($view) {
            $activeBooks = Book::where('status_id', Book::STATUS_ACTIVE)->pluck('name', 'id');

            return $view->with('activeBooks', $activeBooks);
        });

        Passport::personalAccessTokensExpireIn(now()->addDay());
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
