<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use App\Support\Section;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {

        parent::boot();


        Route::bind('section', function (string $value) {
            return Section::fromSlug($value); 
        });
    }
}
