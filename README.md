
# 🏢 Agendaí — FAPEAM

Sistema interno para gestão e agendamento de salas de reunião com autenticação integrada ao Active Directory da FAPEAM (`fapeam.local`).

---

## 🛠️ Stack & Requisitos

* **PHP** 8.2+ | **Laravel** 11.x | **Laragon** (MySQL)
* **Pacote LDAP**: `directorytree/ldaprecord-laravel` (v3.x)
* **Servidor AD**: `172.16.1.199`

---

## 🚀 Instalação Rápida

### 1. Projeto e Dependências

```bash
cd C:\laragon\www
git clone <URL_DO_REPOSITORIO> ReservaSalas
cd ReservaSalas
composer install
cp .env.example .env
php artisan key:generate

```

### 2. Configurar `.env`

Ajuste as chaves de banco de dados e conexão AD:

```env
# Banco de Dados Local
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=reserva_salas
DB_USERNAME=root
DB_PASSWORD=

# Active Directory
LDAP_LOGGING=true
LDAP_CONNECTION=default
LDAP_HOST=172.16.1.199
LDAP_USERNAME="CN=Seu Usuario,OU=Usuarios,DC=fapeam,DC=local"
LDAP_PASSWORD="SuaSenhaDoAD"
LDAP_PORT=389
LDAP_BASE_DN="DC=fapeam,DC=local"
LDAP_UNIDADES_BASE_DN="OU=FAPEAM,DC=fapeam,DC=local"

```

### 3. Provedor de Autenticação (`config/auth.php`)

Garanta o mapeamento correto entre o Active Directory e o banco de dados MySQL:

```php
'providers' => [
    'users' => [
        'driver' => 'ldap',
        'model' => LdapRecord\Models\ActiveDirectory\User::class,
        'database' => [
            'model' => App\Models\User::class,
            'sync_passwords' => false,
            'sync_attributes' => [
                'name'     => 'cn',
                'username' => 'samaccountname', // OBRIGATÓRIO para o LdapRecord importar
                'login'    => 'samaccountname',
                'email'    => 'userprincipalname',
            ],
        ],
    ],
],

```

### 4. Banco e Sincronização

```bash
# Executa as migrations do banco
php artisan migrate

# Popula as Unidades oficiais (buscando as OUs do AD)
php artisan db:seed --class=UnidadeSeeder

# Testa a conexão com o Active Directory
php artisan ldap:test

# 1º: Importa os usuários do AD para o MySQL local
php artisan config:clear
php artisan ldap:import users

# 2º: Vincula a unidade_fk dos usuários recém-importados via atributos do AD (Department / OU)
php artisan ad:link-users-unidades

```

---

## ⚡ Comandos Úteis

| Comando | Função |
| --- | --- |
| `php artisan ldap:test` | Valida a comunicação com o AD |
| `php artisan db:seed --class=UnidadeSeeder` | Executa especificamente a carga e sincronização oficial de OUs no banco |
| `php artisan ldap:import users` | Importa/Atualiza os usuários do AD na tabela `users` |
| `php artisan ad:link-users-unidades` | Associa a `unidade_fk` aos usuários utilizando o `department` ou a primeira `OU` do DN |
| `php artisan ad:link-users-unidades --debug` | Executa o comando de vínculo exibindo logs detalhados e usuários sem OU mapeada |
| `php artisan ldap:import users --preview` | Simula a importação de usuários sem gravar dados |
| `php artisan optimize:clear` | Limpa todos os caches da aplicação |

```

```