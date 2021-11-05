<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;

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
        Response::macro('attachment', function ($content, $name, $type = 'application/pdf') {
            $headers = [
                'Content-type'        => $type,
                'Content-Disposition' => "attachment; filename=\"{$name}\"",
            ];
        
            return Response::make($content, 200, $headers);        
        });
    }
}
