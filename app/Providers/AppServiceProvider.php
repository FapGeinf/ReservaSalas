<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Login;
use App\Models\Unidade;
use App\Models\User; 

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
        view()->share('unidades', $unidades);

        Event::listen(Login::class, function (Login $event) {
            /** @var User $user */
            $user = $event->user;

            if (empty($user->unidade_fk)) {
                $nomeUnidadeAd = null;
                
                if (method_exists($user, 'firstLdapMessage') || property_exists($user, 'guid')) {
                    $ldapUser = $user->ldap()->first();
                    
                    if ($ldapUser) {
                        $nomeUnidadeAd = $ldapUser->getFirstAttribute('department');

                        if (empty($nomeUnidadeAd)) {
                            $dn = $ldapUser->getFirstAttribute('distinguishedname');
                            
                            if ($dn) {
                                if (preg_match('/OU=([^,]+)/i', $dn, $match)) {
                                    $nomeUnidadeAd = $match[1]; // Ex: DEAC, GEINF, DAF, etc.
                                }
                            }
                        }
                    }
                }

                if ($nomeUnidadeAd) {
                    $unidade = Unidade::where('nome', 'LIKE', "%{$nomeUnidadeAd}%")->first();

                    if ($unidade) {
                        $user->unidade_fk = $unidade->id;
                        $user->save();
                    }
                }
            }
        });
    }
}