---
name: arquitetura-portfolio-blog
description: Arquitetura do projeto portfólio/blog Laravel 10. Cobre padrões MVC, organização de controllers, helpers de upload, DataTables, convenções de rota, validação de formulários e boas práticas para manutenção e evolução do sistema.
---

# 01 — Arquitetura — Portfolio Blog Laravel 10

Referência técnica completa para engenheiros que trabalham neste projeto. Cobre padrões de design, decisões de stack, estrutura de código e boas práticas específicas deste sistema.

---

## Visão Geral da Arquitetura

O sistema é um **MVC monolítico Laravel 10** com duas áreas distintas:

| Área | Prefixo | Middleware | Controlador base |
|---|---|---|---|
| Site Público | `/` | nenhum | `Frontend\HomeController` |
| Painel Admin | `/admin/` | `auth`, `verified` | `Admin\*Controller` |
| Autenticação | `/login`, `/register` | guest | Auth controllers (Breeze) |

---

## Padrões de Design Utilizados

### 1. MVC Padrão Laravel

- **Models** — `app/Models/` — Eloquent com fillable, casts, relacionamentos
- **Controllers** — `app/Http/Controllers/Admin/` e `Frontend/` — finos, sem lógica de negócio
- **Views** — `resources/views/admin/` e `frontend/` — Blade com layouts e componentes

### 2. Helper Functions (Funções Globais)

Localização: `app/helper/helpers.php`

Autocarregado via `composer.json`:
```json
"autoload": {
    "files": ["app/helper/helpers.php"]
}
```

| Função | Assinatura | Responsabilidade |
|---|---|---|
| `handleUpload()` | `handleUpload($file, $path, $oldFile = null)` | Salva arquivo em `public/uploads/$path`, deleta o anterior se existir |
| `deleteFileIfExist()` | `deleteFileIfExist($path)` | Deleta arquivo se existir no disco |
| `getColor()` | `getColor($index)` | Retorna cor Tailwind baseada em índice (para badges dinâmicos) |
| `setSidebarActive()` | `setSidebarActive($routeName)` | Retorna classe CSS `active` se a rota atual corresponder |

**Uso correto de upload em controllers:**
```php
// Store
if ($request->hasFile('image')) {
    $data['image'] = handleUpload($request->file('image'), 'blog');
}

// Update (substitui o arquivo anterior)
if ($request->hasFile('image')) {
    $data['image'] = handleUpload($request->file('image'), 'blog', $blog->image);
}

// Destroy
deleteFileIfExist($blog->image);
$blog->delete();
```

### 3. DataTables (Yajra)

Usado no módulo de Blog. Classe em `app/DataTables/BlogDataTable.php`.

```php
// Controller usa a classe DataTable
public function index(BlogDataTable $dataTable)
{
    return $dataTable->render('admin.blog.index');
}
```

A DataTable encapsula:
- Query Eloquent com `with('blogCategory')`
- Definição de colunas com renderização HTML (imagem, badge de categoria, botões de ação)
- Configuração de exports (Excel, CSV, PDF, Print)

### 4. Configurações Singleton no Banco

Alguns Models são registros únicos (o admin edita, não cria):

| Model | Propósito |
|---|---|
| `GeneralSetting` | Logo, favicon, nome do site (key/value store) |
| `SeoSetting` | Meta title, description, keywords |
| `FooterInfo` | Texto rodapé, copyright, powered by |
| `FooterContactInfo` | Endereço, telefone, e-mail do rodapé |

**Padrão de controller para singletons:**
```php
// index — exibe o único registro
public function index()
{
    $setting = SeoSetting::first();
    return view('admin.seo-setting.index', compact('setting'));
}

// update — atualiza pelo ID do único registro
public function update(Request $request, SeoSetting $seoSetting)
{
    $seoSetting->update($request->validated());
    return redirect()->back()->with('success', 'Atualizado com sucesso!');
}
```

---

## Estrutura de Rotas

Todas as rotas admin são `resource` dentro de um grupo:

```php
Route::prefix('admin')
    ->middleware(['auth', 'verified'])
    ->name('admin.')
    ->group(function () {
        Route::resource('blog', BlogController::class);
        Route::resource('blog-category', BlogCategoryController::class);
        // ...
    });
```

**Nomes de rotas gerados:**
```
admin.blog.index
admin.blog.create
admin.blog.store
admin.blog.show
admin.blog.edit
admin.blog.update
admin.blog.destroy
```

**Rotas especiais (não-resource):**
```php
// Download de currículo
Route::get('about/resume/download', [AboutController::class, 'downloadResume'])
    ->name('admin.about.resume.download');

// Dashboard
Route::get('dashboard', [DashboardController::class, 'index'])
    ->name('admin.dashboard');
```

---

## Estrutura de Controllers Admin

Padrão CRUD completo para cada entidade:

```php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(BlogDataTable $dataTable)
    {
        return $dataTable->render('admin.blog.index');
    }

    public function create()
    {
        $categories = BlogCategory::all();
        return view('admin.blog.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'category'    => 'required|exists:blog_categories,id',
            'description' => 'required|string',
            'image'       => 'required|image|max:5000',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = handleUpload($request->file('image'), 'blog');
        }

        Blog::create($data);
        return redirect()->route('admin.blog.index')->with('success', 'Post criado!');
    }

    public function edit(Blog $blog)
    {
        $categories = BlogCategory::all();
        return view('admin.blog.edit', compact('blog', 'categories'));
    }

    public function update(Request $request, Blog $blog)
    {
        $data = $request->validate([...]);

        if ($request->hasFile('image')) {
            $data['image'] = handleUpload($request->file('image'), 'blog', $blog->image);
        }

        $blog->update($data);
        return redirect()->route('admin.blog.index')->with('success', 'Post atualizado!');
    }

    public function destroy(Blog $blog)
    {
        deleteFileIfExist($blog->image);
        $blog->delete();
        return redirect()->route('admin.blog.index')->with('success', 'Post deletado!');
    }
}
```

---

## Validação de Imagens

Padrão usado em todos os controllers que recebem upload:

```php
'image' => 'required|image|max:5000',        // nova imagem obrigatória
'image' => 'nullable|image|max:5000',        // atualização (imagem opcional)
```

- Máximo: **5MB** (5000 KB)
- Tipos aceitos: jpg, jpeg, png, gif, svg, webp (padrão da rule `image`)
- Destino: `public/uploads/{entidade}/`

---

## Relacionamentos Eloquent

```
Category (1) ──────── (N) PortfolioItem
BlogCategory (1) ────── (N) Blog
```

**No model `PortfolioItem`:**
```php
public function category(): BelongsTo
{
    return $this->belongsTo(Category::class);
}
```

**No model `Category`:**
```php
public function portfolioItems(): HasMany
{
    return $this->hasMany(PortfolioItem::class);
}
```

---

## Matriz de Decisão

| Situação | Solução |
|---|---|
| Lista com filtros e paginação (admin) | Yajra DataTable (`app/DataTables/`) |
| CRUD simples (admin) | Resource Controller + Blade views |
| Upload de imagem | `handleUpload()` no helper |
| Deleção de arquivo | `deleteFileIfExist()` no helper |
| Configuração global do site | Model singleton (`GeneralSetting`, etc.) |
| Notificação de sucesso/erro | `->with('success', '...')` + Toastr |
| Formulário de contato | `ContactMail` + `Mail::to()->send()` |
| Dados para o site público | `HomeController` com múltiplos `Model::all()` |
| Animação de texto (typer) | `TyperTitle` model + Typer.js |

---

## Pontos Críticos

1. **Upload path** — sempre usar `public/uploads/{entidade}/` via `handleUpload()`, nunca salvar diretamente em `storage/`
2. **SoftDelete** — apenas `Hero` usa `SoftDeletes`; os demais modelos deletam permanentemente
3. **Email verification** — middleware `verified` está em todas as rotas admin; o usuário precisa verificar e-mail antes de acessar
4. **Slug** — `Category` e `BlogCategory` têm campo `slug`; gerar no controller antes de salvar
5. **DataTable** — apenas `Blog` usa `BlogDataTable`; os demais usam Eloquent direto na view
6. **Assets estáticos** — estão em `public/assets/` (não compilados pelo Vite); apenas `resources/css/app.css` e `resources/js/app.js` passam pelo Vite
7. **Toastr** — notificações flash lidas no layout via `session('success')` e `session('error')`
