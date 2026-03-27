# 001 — Guia de Instalação

Este documento cobre todos os passos para colocar o ambiente de desenvolvimento em funcionamento, desde os pré-requisitos até a verificação do painel administrativo. Siga na ordem indicada.

---

## Pré-requisitos

| Dependência | Versão mínima | Verificar com |
|---|---|---|
| PHP | 8.1+ | `php --version` |
| Composer | 2.x | `composer --version` |
| Node.js | 18+ | `node --version` |
| npm | 9+ | `npm --version` |
| MySQL | 8.0+ | `mysql --version` |
| Git | qualquer | `git --version` |

Extensões PHP obrigatórias: `pdo`, `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`, `fileinfo`, `gd` (para manipulação de imagens).

---

## Instalação

### 1. Clonar o repositório

```bash
git clone <repo-url> app-portfolio-blog-laravel
cd app-portfolio-blog-laravel
```

### 2. Instalar dependências PHP

```bash
composer install
```

### 3. Configurar variáveis de ambiente

```bash
cp .env.example .env
php artisan key:generate
```

Edite `.env` e configure o banco de dados e e-mail:

```dotenv
APP_NAME="Portfólio"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=portfolio_blog
DB_USERNAME=root
DB_PASSWORD=sua_senha

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=seu_usuario
MAIL_PASSWORD=sua_senha
MAIL_FROM_ADDRESS="contato@seusite.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### 4. Criar o banco de dados

```bash
mysql -u root -p -e "CREATE DATABASE portfolio_blog CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

### 5. Executar migrations

```bash
php artisan migrate
```

### 6. Criar link de storage (para uploads)

```bash
php artisan storage:link
```

### 7. Instalar dependências Node e buildar assets

```bash
npm install
npm run dev
```

---

## Servidor de Desenvolvimento

Execute em dois terminais separados:

**Terminal 1 — Backend:**
```bash
php artisan serve
```

**Terminal 2 — Frontend (assets com HMR):**
```bash
npm run dev
```

Acesse: `http://localhost:8000`

---

## Criar Usuário Admin

O sistema usa Laravel Breeze. Crie o primeiro usuário admin via `tinker` ou pelo formulário de registro (rota `/register`):

```bash
php artisan tinker
```

```php
App\Models\User::create([
    'name'              => 'Admin',
    'email'             => 'admin@exemplo.com',
    'password'          => bcrypt('senha_segura'),
    'email_verified_at' => now(),
]);
```

Ou acesse `/register` no browser, crie a conta e verifique o e-mail (em dev, use `php artisan tinker` para marcar como verificado).

---

## Verificação da Instalação

| Rota | Esperado |
|---|---|
| `GET /` | Site público (homepage do portfólio) |
| `GET /admin/dashboard` | Redireciona para login (se não autenticado) |
| `GET /login` | Formulário de login |
| `GET /admin/dashboard` (após login) | Dashboard do admin |

---

## Estrutura de Uploads

O sistema salva arquivos em `public/uploads/`. Certifique-se de que o diretório tem permissão de escrita:

```bash
chmod -R 775 public/uploads/
```

Os helpers de upload (`handleUpload()` e `deleteFileIfExist()`) estão em `app/helper/helpers.php`.

---

## Solução de Problemas

### Erro 500 após instalar

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

### Uploads não funcionam

Verifique se `public/uploads/` existe e tem permissão de escrita. O link de storage deve existir em `public/storage`.

### Assets não carregam

```bash
npm run build
```

### DataTables não carregam dados

Verifique se as rotas do admin estão com `auth` e `verified` e se o usuário está com e-mail verificado.

### Erro de chave de aplicação

```bash
php artisan key:generate
```

---

## Preparação para Produção

```bash
# Instalar dependências sem pacotes de dev
composer install --no-dev --optimize-autoloader

# Buildar assets para produção
npm run build

# Cachear tudo
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Rodar migrations em produção
php artisan migrate --force

# Link de storage (se ainda não feito)
php artisan storage:link
```

Configure também no servidor:
- Apontar document root para `public/`
- Permissão de escrita em `storage/` e `public/uploads/`
- Variável `APP_ENV=production` e `APP_DEBUG=false` no `.env`
