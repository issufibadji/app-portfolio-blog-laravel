# 03 — Painel Administrativo

Documentação completa do painel admin: controllers, rotas, views, funcionalidades e convenções.

---

## Visão Geral

O painel admin é acessado via `/admin/` e protegido por:
- `auth` — usuário deve estar autenticado
- `verified` — e-mail deve estar verificado

Todas as rotas admin usam prefixo `admin.` nos nomes, permitindo `route('admin.blog.index')`.

---

## Mapa Completo de Controllers Admin

| Controller | Rota base | Seção gerenciada |
|---|---|---|
| `DashboardController` | `admin/dashboard` | Dashboard (visão geral) |
| `HeroController` | `admin/hero` | Banner principal |
| `TyperTitleController` | `admin/typer-title` | Títulos animados |
| `ServiceController` | `admin/service` | Serviços/especialidades |
| `AboutController` | `admin/about` | Seção Sobre + currículo |
| `CategoryController` | `admin/category` | Categorias do portfólio |
| `PortfolioItemController` | `admin/portfolio-item` | Projetos do portfólio |
| `PortfolioSectionSettingController` | `admin/portfolio-section-setting` | Config. seção Portfólio |
| `SkillSectionSettingController` | `admin/skill-section-setting` | Config. seção Skills |
| `SkillItemController` | `admin/skill-item` | Itens de skill |
| `ExperienceController` | `admin/experience` | Seção de experiência |
| `FeedbackController` | `admin/feedback` | Depoimentos |
| `FeedbackSectionSettingController` | `admin/feedback-section-setting` | Config. seção Depoimentos |
| `BlogCategoryController` | `admin/blog-category` | Categorias do blog |
| `BlogController` | `admin/blog` | Posts do blog (DataTable) |
| `BlogSectionSettingController` | `admin/blog-section-setting` | Config. seção Blog |
| `ContactSectionSettingController` | `admin/contact-section-setting` | Config. seção Contato |
| `FooterSocialLinkController` | `admin/footer-social-link` | Links sociais rodapé |
| `FooterInfoController` | `admin/footer-info` | Texto/copyright rodapé |
| `FooterContactInfoController` | `admin/footer-contact-info` | Contato no rodapé |
| `FooterUsefulLinkController` | `admin/footer-useful-link` | Links úteis rodapé |
| `FooterHelpLinkController` | `admin/footer-help-link` | Links ajuda rodapé |
| `SettingController` | `admin/settings` | Página de configurações |
| `GeneralSettingController` | `admin/general-setting` | Logo, favicon, nome |
| `SeoSettingController` | `admin/seo-setting` | Meta tags SEO |
| `ProfileController` | `admin/profile` | Perfil do usuário |

---

## Estrutura de Views Admin

```
resources/views/admin/
├── dashboard.blade.php
├── layouts/
│   ├── app.blade.php           ← Layout base do admin (sidebar, topbar)
│   └── _partials/
│       ├── sidebar.blade.php
│       └── topbar.blade.php
├── hero/
│   ├── index.blade.php
│   ├── create.blade.php
│   └── edit.blade.php
├── blog/
│   ├── index.blade.php         ← Usa Yajra DataTable
│   ├── create.blade.php
│   └── edit.blade.php
├── blog-category/
├── blog-setting/
├── about/
├── category/
├── contact-setting/
├── experience/
├── feedback/
├── feedback-setting/
├── footer-social-link/
├── footer-info/
├── footer-contact-info/
├── footer-useful-link/
├── footer-help-link/
├── portfolio-item/
├── portfolio-setting/
├── service/
├── skill-item/
├── skill-setting/
├── typer-title/
├── general-setting/
└── seo-setting/
```

---

## Padrão de Views

### Layout base (`admin/layouts/app.blade.php`)

```blade
<!DOCTYPE html>
<html>
<head>
    @yield('css')
</head>
<body>
    @include('admin.layouts._partials.sidebar')
    <div class="main-content">
        @include('admin.layouts._partials.topbar')
        <div class="content">
            @yield('content')
        </div>
    </div>
    @yield('js')
</body>
</html>
```

### View de listagem padrão (index.blade.php)

```blade
@extends('admin.layouts.app')
@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h5>Título da Seção</h5>
            <a href="{{ route('admin.recurso.create') }}" class="btn btn-primary">
                Adicionar Novo
            </a>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>...</thead>
                <tbody>
                    @foreach($items as $item)
                    <tr>
                        <td>{{ $item->title }}</td>
                        <td>
                            <a href="{{ route('admin.recurso.edit', $item) }}" class="btn btn-sm btn-warning">Editar</a>
                            <form action="{{ route('admin.recurso.destroy', $item) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Deletar</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
```

---

## Blog — DataTable

O Blog usa Yajra DataTables para listagem com server-side processing, exportação e paginação.

### Classe DataTable

```php
// app/DataTables/BlogDataTable.php

class BlogDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query->newQuery()))
            ->addColumn('action', 'admin.blog.action')   // partial com botões
            ->editColumn('image', function (Blog $blog) {
                return '<img src="/' . $blog->image . '" width="50">';
            })
            ->editColumn('category', function (Blog $blog) {
                return $blog->blogCategory->name ?? '-';
            })
            ->rawColumns(['action', 'image']);
    }

    public function query(Blog $model): QueryBuilder
    {
        return $model->newQuery()->with('blogCategory');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('blog-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->buttons([
                Button::make('excel'),
                Button::make('csv'),
                Button::make('pdf'),
                Button::make('print'),
            ]);
    }
}
```

### Controller usando DataTable

```php
public function index(BlogDataTable $dataTable)
{
    return $dataTable->render('admin.blog.index');
}
```

### View com DataTable

```blade
@extends('admin.layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/datatables/dataTables.bootstrap4.min.css') }}">
@endsection

@section('content')
    {{ $dataTable->table() }}
@endsection

@section('js')
    <script src="{{ asset('assets/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    {{ $dataTable->scripts() }}
@endsection
```

---

## Seções de Configuração do Rodapé

O rodapé é dividido em 5 controllers independentes:

| Controller | Responsabilidade | Tipo |
|---|---|---|
| `FooterInfoController` | Texto do rodapé, copyright, "powered by" | Singleton |
| `FooterContactInfoController` | Endereço, telefone, e-mail | Singleton |
| `FooterSocialLinkController` | Links de redes sociais (ícone + URL) | CRUD |
| `FooterUsefulLinkController` | Links úteis (título + URL) | CRUD |
| `FooterHelpLinkController` | Links de ajuda (título + URL) | CRUD |

---

## Configurações Gerais e SEO

### `GeneralSettingController`
Gerencia configurações do tipo chave-valor (`setting_key`, `setting_value`):
- Logo do site
- Favicon
- Nome do site
- E-mail de contato geral

### `SeoSettingController`
Registro único com:
- `meta_title` — exibido no `<title>` de todas as páginas
- `meta_description` — meta description global
- `meta_keywords` — meta keywords globais

---

## Notificações Flash (Toastr)

Os controllers usam `->with('success', ...)` e `->with('error', ...)`. O layout admin renderiza via Toastr:

```blade
{{-- No layout admin, após os scripts --}}
@if(session('success'))
    <script>toastr.success("{{ session('success') }}")</script>
@endif
@if(session('error'))
    <script>toastr.error("{{ session('error') }}")</script>
@endif
```

---

## Perfil do Usuário

O controller `ProfileController` (do Breeze) gerencia:
- Atualização de nome e e-mail (`PUT /profile`)
- Deleção da conta (`DELETE /profile`)
- Troca de senha via formulário separado

---

## Sidebar — Navegação Admin

A sidebar exibe links para todas as seções gerenciáveis. A função helper `setSidebarActive()` retorna a classe `active` para o item correspondente à rota atual:

```php
// app/helper/helpers.php
function setSidebarActive(string $routeName): string
{
    return request()->routeIs($routeName) ? 'active' : '';
}
```

```blade
{{-- Na sidebar --}}
<a href="{{ route('admin.blog.index') }}" class="{{ setSidebarActive('admin.blog.*') }}">
    Blog
</a>
```

---

## Fluxo de Adição de Nova Seção CRUD

Quando precisar adicionar uma nova seção gerenciável ao admin:

```bash
# 1. Criar migration
php artisan make:migration create_nova_entidade_table

# 2. Criar model
php artisan make:model NovaEntidade

# 3. Criar controller no namespace Admin
php artisan make:controller Admin/NovaEntidadeController --resource

# 4. Registrar rota em routes/web.php
Route::resource('nova-entidade', NovaEntidadeController::class);

# 5. Criar views em resources/views/admin/nova-entidade/
#    index.blade.php, create.blade.php, edit.blade.php

# 6. Adicionar link na sidebar

# 7. Rodar migration
php artisan migrate
```
