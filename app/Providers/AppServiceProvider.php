<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Events\Login;
use App\Models\Unidade;
use App\Models\User;
use Throwable;

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
        // Compartilha a lista de unidades com todas as views (com fallback em caso de erro)
        try {
            $unidades = Unidade::all();
        } catch (Throwable $e) {
            Log::warning('Não foi possível carregar unidades no boot: ' . $e->getMessage());
            $unidades = null;
        }
        view()->share('unidades', $unidades);

        // Listener do evento de login
        Event::listen(Login::class, function (Login $event) {
            /** @var User $user */
            $user = $event->user;

            Log::info("=== INÍCIO DA VERIFICAÇÃO DE UNIDADE PARA O USUÁRIO: {$user->name} (Login: {$user->login}) ===");

            if (empty($user->unidade_fk)) {
                $siglaUnidadeAd = null;

                if (method_exists($user, 'ldap')) {
                    try {
                        $ldapUser = $user->ldap()->first();

                        if ($ldapUser) {
                            if (method_exists($ldapUser, 'getFirstAttribute')) {
                                $siglaUnidadeAd = $ldapUser->getFirstAttribute('department');
                                Log::info("Atributo 'department' retornado pelo AD: " . ($siglaUnidadeAd ?: 'Vazio'));
                            } else {
                                Log::warning("O objeto LDAP não possui o método getFirstAttribute(). Verifique o pacote LDAP.");
                            }

                            if (empty($siglaUnidadeAd)) {
                                $dn = null;
                                if (method_exists($ldapUser, 'getFirstAttribute')) {
                                    $dn = $ldapUser->getFirstAttribute('distinguishedname');
                                } elseif (property_exists($ldapUser, 'distinguishedname')) {
                                    $dn = $ldapUser->distinguishedname; // fallback para propriedade direta
                                }
                                
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
                    } catch (Throwable $e) {
                        Log::error("Erro ao acessar o LDAP para o usuário {$user->login}: " . $e->getMessage());
                    }
                } else {
                    Log::warning("O modelo User não possui o método ldap(). Verifique se o pacote LDAP está configurado corretamente.");
                }

                if (!empty($siglaUnidadeAd)) {
                    Log::info("Buscando unidade no banco de dados com a sigla: {$siglaUnidadeAd}");

                    try {
                        $unidade = Unidade::where('sigla', $siglaUnidadeAd)->first();

                        if ($unidade) {
                            $user->unidade_fk = $unidade->id;
                            $user->save();
                            Log::info("SUCESSO: Unidade '{$unidade->nome}' (ID: {$unidade->id}) vinculada ao usuário {$user->name}!");
                        } else {
                            Log::error("FALHA: A sigla '{$siglaUnidadeAd}' foi encontrada no AD, mas NÃO existe na tabela unidades do banco de dados!");
                        }
                    } catch (Throwable $e) {
                        Log::error("Erro ao buscar ou salvar a unidade para o usuário {$user->login}: " . $e->getMessage());
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