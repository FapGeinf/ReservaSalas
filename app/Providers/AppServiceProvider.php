<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Unidade;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        try {
            $unidades = Unidade::all();
        } catch (\Exception $e) {
            $unidades = null;
        }
        view()->share('unidades',$unidades);
    }
}
