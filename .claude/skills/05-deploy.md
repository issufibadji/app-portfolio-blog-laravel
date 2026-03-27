# 05 — Deploy e Go-Live

Checklist e guia completo para publicar o portfólio/blog em produção. Cobre servidor, banco de dados, assets, e-mail e validação final.

---

## Checklist de Go-Live

### Pré-requisitos do Servidor

- [ ] PHP 8.1+ instalado com extensões: `pdo`, `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`, `fileinfo`, `gd`
- [ ] Composer 2.x instalado
- [ ] Node.js 18+ e npm instalados (apenas para build)
- [ ] MySQL 8.0+ configurado
- [ ] Document root apontando para `public/`
- [ ] `mod_rewrite` habilitado (Apache) ou equivalente Nginx

---

### 1. Código e Dependências

```bash
# Clonar ou fazer upload do código
git clone <repo-url> /var/www/portfolio

# Instalar dependências PHP (sem dev)
composer install --no-dev --optimize-autoloader

# Instalar dependências Node e buildar assets
npm install
npm run build

# Remover node_modules após build (opcional, economiza espaço)
rm -rf node_modules
```

---

### 2. Variáveis de Ambiente

```bash
cp .env.example .env
php artisan key:generate
```

Configurações críticas para produção:

```dotenv
APP_NAME="Seu Nome | Portfólio"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://seudominio.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=portfolio_prod
DB_USERNAME=usuario_prod
DB_PASSWORD=senha_forte

MAIL_MAILER=smtp
MAIL_HOST=smtp.provedor.com
MAIL_PORT=587
MAIL_USERNAME=seu@email.com
MAIL_PASSWORD=senha_email
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="contato@seudominio.com"
MAIL_FROM_NAME="Seu Nome"
```

---

### 3. Banco de Dados

```bash
# Criar banco
mysql -u root -p -e "CREATE DATABASE portfolio_prod CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Rodar migrations
php artisan migrate --force
```

---

### 4. Permissões e Storage

```bash
# Permissões de escrita para storage e uploads
chmod -R 775 storage/
chmod -R 775 public/uploads/
chown -R www-data:www-data storage/ public/uploads/

# Criar link de storage simbólico
php artisan storage:link
```

---

### 5. Cache de Produção

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

---

### 6. Criar Usuário Admin

```bash
php artisan tinker
```

```php
App\Models\User::create([
    'name'              => 'Admin',
    'email'             => 'admin@seudominio.com',
    'password'          => bcrypt('senha_muito_segura'),
    'email_verified_at' => now(),
]);
```

---

### 7. Configuração do Servidor Web

#### Nginx (recomendado)

```nginx
server {
    listen 80;
    server_name seudominio.com www.seudominio.com;
    root /var/www/portfolio/public;
    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

#### Apache (.htaccess)

O arquivo `public/.htaccess` já está incluído no projeto com as rewrite rules do Laravel.

---

### 8. HTTPS / SSL

```bash
# Usando Certbot (Let's Encrypt)
certbot --nginx -d seudominio.com -d www.seudominio.com
```

---

## Validação Pós-Deploy

| Verificação | URL | Esperado |
|---|---|---|
| Homepage pública | `https://seudominio.com/` | Site carrega com conteúdo |
| Portfólio | `https://seudominio.com/portfolio` | Projetos listados |
| Blog | `https://seudominio.com/blogs` | Posts listados |
| Login admin | `https://seudominio.com/login` | Formulário de login |
| Dashboard admin | `https://seudominio.com/admin/dashboard` | Dashboard carrega |
| Upload de imagem | Via admin | Imagem salva em `public/uploads/` |
| Formulário de contato | Formulário no site | E-mail recebido |
| Download de currículo | Botão "Download CV" | PDF baixado |
| DataTable (Blog admin) | `admin/blog` | Tabela carrega com dados |

---

## Atualizações em Produção

```bash
# 1. Ativar modo manutenção
php artisan down

# 2. Atualizar código
git pull origin master

# 3. Instalar novas dependências
composer install --no-dev --optimize-autoloader

# 4. Rodar novas migrations
php artisan migrate --force

# 5. Limpar caches
php artisan optimize:clear

# 6. Recachear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 7. Rebuild assets (se necessário)
npm install && npm run build

# 8. Desativar modo manutenção
php artisan up
```

---

## Solução de Problemas em Produção

### Erro 500

```bash
# Verificar logs
tail -f storage/logs/laravel.log
```

Causas comuns:
- `.env` não configurado corretamente
- `APP_KEY` não gerado
- Permissões incorretas em `storage/`

### Assets não carregam

```bash
npm run build
```

Verificar se `public/build/` foi gerado e se o servidor está servindo arquivos estáticos corretamente.

### Uploads não salvam

```bash
ls -la public/uploads/
# Se não existir:
mkdir -p public/uploads
chmod -R 775 public/uploads
chown -R www-data:www-data public/uploads
```

### DataTable em branco

- Verificar se o usuário está autenticado e com e-mail verificado
- Verificar logs do Laravel para erros de query
- Confirmar que `session` está configurado corretamente no `.env`

### E-mail não enviado

```bash
# Testar configuração de e-mail
php artisan tinker
Mail::raw('Teste', fn($m) => $m->to('teste@exemplo.com')->subject('Teste'));
```

---

## Backup

```bash
# Backup do banco de dados
mysqldump -u usuario -p portfolio_prod > backup_$(date +%Y%m%d).sql

# Backup dos uploads
tar -czf uploads_$(date +%Y%m%d).tar.gz public/uploads/
```

Recomendado: automatizar via cron job diário.
