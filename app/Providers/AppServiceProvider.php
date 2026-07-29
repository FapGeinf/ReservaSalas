<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
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

            Log::info("=== INÍCIO DA VERIFICAÇÃO DE UNIDADE PARA O USUÁRIO: {$user->name} (Login: {$user->login}) ===");

            if (empty($user->unidade_fk)) {
                $siglaUnidadeAd = null;
                
                if (method_exists($user, 'firstLdapMessage') || property_exists($user, 'guid')) {
                    $ldapUser = $user->ldap()->first();
                    
                    if ($ldapUser) {
                        $siglaUnidadeAd = $ldapUser->getFirstAttribute('department');
                        Log::info("Atributo 'department' retornado pelo AD: " . ($siglaUnidadeAd ?: 'Vazio'));

                        if (empty($siglaUnidadeAd)) {
                            $dn = $ldapUser->getFirstAttribute('distinguishedname');
                            Log::info("Atributo 'distinguishedname' retornado pelo AD: " . ($dn ?: 'Vazio'));
                            
                            if ($dn) {
                                if (preg_match('/OU=([^,]+)/i', $dn, $match)) {
                                    $siglaUnidadeAd = trim($match[1]);
                                    Log::info("Sigla extraída da primeira OU via Regex: " . $siglaUnidadeAd);
                                } else {
                                    Log::warning("Não foi possível extrair a OU do DN: {$dn}");
                                }
                            }
                        }
                    } else {
                        Log::warning("Não foi possível carregar os dados LDAP para o usuário {$user->login}.");
                    }
                } else {
                    Log::warning("O model de usuário não possui métodos LDAP compatíveis.");
                }

                if (!empty($siglaUnidadeAd)) {
                    Log::info("Buscando unidade no banco de dados com a sigla: {$siglaUnidadeAd}");
                    
                    $unidade = Unidade::where('sigla', $siglaUnidadeAd)->first();

                    if ($unidade) {
                        $user->unidade_fk = $unidade->id;
                        $user->save();
                        Log::info("SUCESSO: Unidade '{$unidade->nome}' (ID: {$unidade->id}) vinculada ao usuário {$user->name}!");
                    } else {
                        Log::error("FALHA: A sigla '{$siglaUnidadeAd}' foi encontrada no AD, mas NÃO existe na tabela unidades do banco de dados!");
                    }
                } else {
                    Log::warning("Nenhuma sigla de unidade ou departamento foi identificada no AD para o usuário {$user->name}.");
                }
            } else {
                Log::info("O usuário {$user->name} já possui a unidade_fk preenchida (ID: {$user->unidade_fk}). Nenhuma alteração feita.");
            }

            Log::info("=== FIM DA VERIFICAÇÃO DE UNIDADE ===");
        });
    }
}