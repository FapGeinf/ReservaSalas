<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\User;
use LdapRecord\Models\ActiveDirectory\User as LdapUser;
use LdapRecord\Container;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->validate([
            'login'    => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);
        
    
        $domain = env('LDAP_AUTH_DOMAIN');

        $loginInput = $request->login;
        $passwordInput = $request->password;

        $loginSam = trim($loginInput );
        $ldapLogin =  $loginSam . '@' . $domain;

        try {
            if (!Container::getConnection('default')
                ->auth()
                ->attempt($ldapLogin, $passwordInput)
            ) {
                return back()->withErrors([
                    'login' => 'Usuário ou senha inválidos no AD.',
                ]);
            }

            
            $ldapUser = LdapUser::where(
                'samaccountname',
                $loginInput
            )->first();
            

            if (!$ldapUser) {
                return back()->withErrors([
                    'login' => 'Usuário autenticou, mas não foi encontrado no AD.',
                ]);
            }

            $domain = env('LDAP_AUTH_DOMAIN');
            $user = User::updateOrCreate(
                ['guid' => $ldapUser->getConvertedGuid()],
                [
                    'login'         => $loginSam,
                    'username'      => $loginSam,
                    'name'          => $ldapUser->getFirstAttribute('displayname') ?? $loginSam,
                    'email'         => $ldapUser->getFirstAttribute('mail'),
                    'domain'        => $domain,
                    'auth_provider' => 'ldap',
                    'password'      => null,
                ]
            );

            
            Auth::login($user);
            $request->session()->regenerate();

            return redirect()->intended('dashboard');

        } catch (\Throwable $e) {
            return back()->withErrors([
                'login' => 'Erro ao autenticar no Active Directory.',
            ]);
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}