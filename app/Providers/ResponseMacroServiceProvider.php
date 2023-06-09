<?php

namespace App\Providers;

use App\Services\Transactions\CsvTransformer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class ResponseMacroServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Response::macro('csv', function (Collection $transactions) {
            $output = (new CsvTransformer($transactions))->toString();

            return Response::make(rtrim($output, "\n"), 200, [
                'Content-type'        => 'text/csv',
                'Content-Disposition' => 'attachment; filename=transactions_'.date('YmdHis').'.csv',
            ]);
        });
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
