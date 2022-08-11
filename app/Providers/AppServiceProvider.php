<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;
use Laravel\Sanctum\Sanctum;
use App\Models\PersonalAccessToken;

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
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);

        Response::macro('attachment', function ($content, $name, $type = 'application/pdf') {
            $headers = [
                'Content-type'        => $type,
                'Content-Disposition' => "attachment; filename=\"{$name}\"",
            ];
        
            return Response::make($content, 200, $headers);        
        });
    }
}
