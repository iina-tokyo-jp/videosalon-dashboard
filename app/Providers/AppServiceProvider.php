<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();
        URL::forceScheme('https');

        if(config('app.debug') && config('app.url') == 'http://localhost') {
            $filename = 'query_' . date('d-m-y') . '.log';
            $endTime = microtime(true);
            $dataToLog = 'Time: ' . Carbon::now() . "\n";
            $dataToLog .= 'Duration: ' . number_format($endTime - LARAVEL_START, 3) . "";
            File::append(storage_path('logs' . DIRECTORY_SEPARATOR . $filename), $dataToLog . "\n" . str_repeat("=", 20) . "\n");

            DB::listen(function($query) use ($filename) {
                File::append(
                    storage_path('logs' . DIRECTORY_SEPARATOR . $filename),
                    $query->sql . ' [' . implode(', ', $query->bindings) . ']' . PHP_EOL . "\n"
                );
            });
        }
    }
}
