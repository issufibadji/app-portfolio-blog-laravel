# 00 — Contexto Geral do Projeto

Este documento descreve o propósito, a arquitetura, o stack tecnológico e as regras de desenvolvimento do projeto. É o ponto de entrada obrigatório para qualquer engenheiro que vá contribuir com o sistema. Todas as decisões arquiteturais relevantes estão registradas aqui.

---

## Propósito do Projeto

Este sistema é um **portfólio pessoal com blog integrado**, construído como CMS completo com painel administrativo. O objetivo é apresentar trabalhos, habilidades, experiência profissional e publicar artigos, tudo gerenciado por um admin protegido por autenticação.

O sistema **não é modular**. É um **MVC monolítico tradicional Laravel** com separação clara entre área pública (frontend) e painel administrativo (backend).

---

## Arquitetura

O sistema segue o padrão **MVC monolítico** padrão do Laravel:

```
app-portfolio-blog-laravel/
├── app/
│   ├── DataTables/             ← Classes Yajra DataTables (Blog)
│   ├── helper/                 ← Funções globais (upload, delete, cor, sidebar)
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          ← 26 controllers do painel admin
│   │   │   ├── Auth/           ← Controllers de autenticação (Breeze)
│   │   │   └── Frontend/       ← HomeController (site público)
│   │   ├── Middleware/
│   │   └── Requests/
│   ├── Mail/                   ← ContactMail (formulário de contato)
│   ├── Models/                 ← 24 models Eloquent
│   └── Providers/
├── database/
│   ├── migrations/             ← 28 migrations
│   └── seeders/
├── resources/
│   └── views/
│       ├── admin/              ← Views do painel administrativo
│       ├── auth/               ← Views de autenticação (Breeze)
│       ├── frontend/           ← Views do site público
│       ├── layouts/            ← Layouts base
│       └── mail/               ← Templates de e-mail
├── routes/
│   ├── web.php                 ← Rotas públicas + admin
│   ├── auth.php                ← Rotas de autenticação (Breeze)
│   └── api.php                 ← API mínima (Sanctum)
└── public/
    ├── assets/                 ← CSS, JS, fontes, imagens estáticas
    └── uploads/                ← Arquivos enviados pelo admin
```

---

## Stack Tecnológico

| Tecnologia | Versão | Papel |
|---|---|---|
| PHP | ^8.1 | Runtime |
| Laravel | ^10.10 | Framework base |
| Laravel Breeze | ^1.21 | Scaffolding de autenticação |
| Laravel Sanctum | ^3.2 | Autenticação de API |
| Yajra DataTables | ^10.0 | Tabelas server-side no admin |
| Yoeunes Toastr | ^2.3 | Notificações toast |
| Tailwind CSS | ^3.1.0 | Estilização utility-first |
| Alpine.js | ^3.4.2 | Interatividade client-side |
| Vite | ^4.0.0 | Asset bundler |
| MySQL | 8.0+ | Banco de dados |
| Node.js | 18+ | Build toolchain |

---

## Camadas da Aplicação

### Frontend Público (`/`)
Área acessível a qualquer visitante. Gerenciada por `HomeController`. Exibe portfólio, blog, serviços, habilidades, experiência, depoimentos e formulário de contato.

### Painel Administrativo (`/admin/`)
Área protegida por autenticação Laravel Breeze (`auth`, `verified`). Possui 26 controllers CRUD para gerenciar todo o conteúdo do site.

### Autenticação
Baseada em Laravel Breeze. Rotas definidas em `routes/auth.php`. Apenas um usuário administrador acessa o painel.

---

## Princípios de Desenvolvimento

### 1. Controllers são finos
Controllers recebem a requisição, validam via `$request->validate()`, interagem com o Model e retornam view ou redirect. Sem lógica de negócio complexa.

### 2. Upload centralizado em helper
Toda lógica de upload de arquivo usa `handleUpload()` em `app/helper/helpers.php`. Nunca duplicar lógica de upload nos controllers.

### 3. Configurações via Models singleton
Configurações como `GeneralSetting`, `SeoSetting`, `FooterInfo` são registros únicos no banco — o admin edita o único registro existente, nunca cria novos.

### 4. Seções com configuração própria
Cada seção do site (Portfolio, Skill, Feedback, Blog, Contact) tem um Model `*SectionSetting` com título e subtítulo editáveis.

---

## Regras de Desenvolvimento

### Convenções de nomenclatura

| Tipo | Convenção | Exemplo |
|---|---|---|
| Model | PascalCase singular | `BlogCategory.php` |
| Controller admin | `NomeController` em `Admin/` | `BlogController.php` |
| Controller frontend | `NomeController` em `Frontend/` | `HomeController.php` |
| View admin | `resources/views/admin/nome/` | `admin/blog/index.blade.php` |
| View frontend | `resources/views/frontend/` | `frontend/home.blade.php` |
| Migration | snake_case descritiva | `create_blogs_table.php` |
| Upload | `public/uploads/` | `public/uploads/blog/imagem.jpg` |

### Rotas admin

Todas as rotas admin seguem o padrão:
- Prefixo: `/admin/`
- Middleware: `['auth', 'verified']`
- Name prefix: `admin.`

```php
Route::prefix('admin')->middleware(['auth', 'verified'])->name('admin.')->group(function () {
    Route::resource('blog', BlogController::class);
});
// Rota acessível como: route('admin.blog.index')
```

---

## O que este sistema NÃO é

- **Não é modular** — sem `nwidart/laravel-modules`; todo código está em `app/`
- **Não tem multi-usuário** — um único admin gerencia todo o conteúdo
- **Não tem API pública** — `api.php` é mínimo, apenas com rota de usuário autenticado
- **Não tem Livewire** — UI reativa é feita com Alpine.js + DataTables
- **Não tem RBAC** — sem roles/permissions; o único acesso restrito é `auth + verified`
- **Não tem soft deletes generalizados** — apenas `Hero` usa `SoftDeletes`
