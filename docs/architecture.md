# Arquitetura do Sistema

## Visao Geral

O projeto segue a arquitetura **MVC (Model-View-Controller)** do Laravel, com separacao clara entre camada publica (frontend) e camada administrativa (admin panel).

```
┌─────────────────────────────────────────────────────────┐
│                      Requisicao HTTP                     │
└────────────────────────────┬────────────────────────────┘
                             │
                    ┌────────▼────────┐
                    │   Middleware    │
                    │  auth, verified │
                    └────────┬────────┘
                             │
          ┌──────────────────┼──────────────────┐
          │                  │                  │
   ┌──────▼──────┐   ┌──────▼──────┐   ┌──────▼──────┐
   │  Frontend   │   │    Admin    │   │     API     │
   │  Routes     │   │   Routes    │   │   Routes    │
   │  (publico)  │   │ (/admin/*)  │   │  (/api/*)   │
   └──────┬──────┘   └──────┬──────┘   └──────┬──────┘
          │                  │                  │
   ┌──────▼──────┐   ┌──────▼──────┐   ┌──────▼──────┐
   │    Home     │   │   Admin     │   │  Sanctum    │
   │ Controller  │   │ Controllers │   │  API Auth   │
   └──────┬──────┘   └──────┬──────┘   └─────────────┘
          │                  │
   ┌──────▼──────────────────▼──────┐
   │           Models (Eloquent)     │
   │  Blog, Portfolio, Skill, etc.   │
   └───────────────┬────────────────┘
                   │
   ┌───────────────▼────────────────┐
   │           MySQL Database        │
   └────────────────────────────────┘
```

---

## Camadas da Aplicacao

### 1. Rotas (`routes/`)

| Arquivo | Descricao |
|---|---|
| `web.php` | Rotas publicas e admin (70+ rotas) |
| `api.php` | Endpoints REST via Sanctum |
| `auth.php` | Rotas de autenticacao (Breeze) |

**Grupos de rotas:**
```php
// Publico — sem autenticacao
Route::get('/', [HomeController::class, 'index']);

// Admin — requer auth + email verificado
Route::prefix('admin')->middleware(['auth', 'verified'])->group(function () {
    Route::resource('blog', BlogController::class);
    // ...
});

// API — Sanctum token
Route::middleware('auth:sanctum')->get('/user', fn($req) => $req->user());
```

### 2. Controllers (`app/Http/Controllers/`)

```
Controllers/
├── Frontend/
│   └── HomeController.php        # Unico controller publico — agrega todos os dados
├── Admin/                         # 27 controllers CRUD
│   ├── BlogController.php
│   ├── PortfolioItemController.php
│   ├── DashboardController.php
│   ├── AboutController.php        # Inclui download de curriculo
│   ├── GeneralSettingController.php
│   ├── SeoSettingController.php
│   └── [+22 controllers]
└── Auth/                          # 9 controllers (Breeze)
    ├── AuthenticatedSessionController.php
    ├── RegisteredUserController.php
    └── [+7 controllers]
```

**Padrao Admin Controller:**
```php
class BlogController extends Controller
{
    public function index(BlogDataTable $dataTable)  // lista com DataTable
    public function create()                          // form de criacao
    public function store(Request $request)           // salva novo registro
    public function edit(Blog $blog)                  // form de edicao
    public function update(Request $request, Blog $blog) // atualiza registro
    public function destroy(Blog $blog)               // exclui registro
}
```

### 3. Models (`app/Models/`)

**24 modelos Eloquent** organizados por dominio:

```
Conteudo:        Blog, BlogCategory, PortfolioItem, Category,
                 Service, SkillItem, Experience, Feedback,
                 Hero, TyperTitle, About

Configuracoes:   BlogSectionSetting, PortfolioSectionSetting,
                 SkillSectionSetting, FeedbackSectionSetting,
                 ContactSectionSetting, GeneralSetting, SeoSetting

Rodape:          FooterSocialLink, FooterInfo, FooterContactInfo,
                 FooterUsefulLink, FooterHelpLink

Sistema:         User
```

**Relacionamentos principais:**
```php
// Blog pertence a uma categoria
Blog::belongsTo(BlogCategory::class, 'category');

// PortfolioItem pertence a uma categoria
PortfolioItem::belongsTo(Category::class, 'category_id');
```

### 4. Views (`resources/views/`)

```
views/
├── frontend/
│   ├── layouts/
│   │   ├── master.blade.php        # Layout base publico
│   │   └── inc/                    # Navbar, footer, scripts, estilos
│   ├── pages/
│   │   ├── home.blade.php          # Pagina inicial (inclui widgets)
│   │   ├── portfolio.blade.php     # Lista de projetos
│   │   ├── portfolio-details.blade.php
│   │   ├── blog.blade.php          # Lista de posts
│   │   └── blog-details.blade.php
│   └── widgets/                    # Secoes da homepage
│       ├── hero.blade.php
│       ├── about.blade.php
│       ├── portfolio.blade.php
│       ├── skills.blade.php
│       ├── experience.blade.php
│       ├── feedback.blade.php
│       ├── blog.blade.php
│       └── contact.blade.php
├── admin/
│   ├── layouts/                    # Layout base do admin
│   ├── dashboard.blade.php
│   └── [pasta por recurso]/        # blog/, portfolio-item/, etc.
├── auth/                           # Login, registro, reset de senha
├── components/                     # Componentes Blade reutilizaveis
└── mail/
    └── contact-mail.blade.php
```

---

## Padroes Arquiteturais

### DataTables (Yajra)

Cada recurso admin tem uma classe DataTable dedicada em `app/DataTables/`:

```php
class BlogDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return datatables()->eloquent($query)
            ->addColumn('image', fn($row) => '<img src="'.$row->image.'">')
            ->addColumn('action', fn($row) => view('admin.blog.action', ['row' => $row]));
    }

    public function query(Blog $model): QueryBuilder
    {
        return $model->with('blogCategory')->newQuery();
    }
}
```

### File Upload Helper

Funcoes globais em `app/helper/helpers.php`:

```php
function handleUpload($inputName, $model = null): string
{
    // Move o arquivo para public/uploads/
    // Se $model fornecido, deleta o arquivo antigo
    // Retorna o caminho do novo arquivo
}

function deleteFileIfExist($filePath): void
{
    // Remove arquivo do disco com seguranca
}
```

### Settings Pattern

Cada secao tem uma tabela de configuracoes com sempre **um unico registro**:

```php
// Em qualquer controller de setting:
$setting = BlogSectionSetting::first();

// Na view:
{{ $blogSetting->title }}
{{ $blogSetting->sub_title }}
```

### Helpers de UI

```php
function getColor($index): string   // Retorna uma cor ciclica (6 opcoes)
function setSidebarActive($route)   // Marca item ativo no sidebar admin
```

---

## Fluxo de Dados — Homepage

```
HomeController@index()
│
├── Hero::first()
├── TyperTitle::all()
├── About::first()
├── Service::all()
├── Category::all()
├── PortfolioItem::with('category')->latest()->take(6)->get()
├── PortfolioSectionSetting::first()
├── SkillSectionSetting::first()
├── SkillItem::all()
├── Experience::all()
├── Feedback::all()
├── Blog::latest()->take(5)->get()
├── BlogSectionSetting::first()
└── ContactSectionSetting::first()
    │
    └── return view('frontend.pages.home', compact(...todos os dados...))
```

---

## Seguranca

| Aspecto | Implementacao |
|---|---|
| Autenticacao | Laravel Breeze + sessao |
| Autorizacao | Middleware `auth` + `verified` |
| CSRF | Token automatico em todos os forms |
| Senhas | `bcrypt` via `Hash::make()` |
| API | Laravel Sanctum (tokens) |
| Uploads | Salvos em `public/uploads/`, sem execucao |
| SQL Injection | Eloquent ORM com bindings seguros |
| XSS | Blade escapa automaticamente com `{{ }}` |

---

## Frontend Assets

```
Vite (build tool)
├── resources/css/app.css     → Tailwind CSS
├── resources/js/app.js       → Alpine.js + Axios
└── vite.config.js

Compilado para:
public/build/
├── assets/app-[hash].css
└── assets/app-[hash].js
```

**Modo desenvolvimento:**
```bash
npm run dev    # Hot reload via Vite
```

**Build para producao:**
```bash
npm run build  # Minifica e gera hash dos assets
```
