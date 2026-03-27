# 04 — Frontend (Site Público)

Documentação do site público: estrutura de rotas, controller, seções, fluxo de dados e stack de UI.

---

## Stack de UI

| Tecnologia | Papel |
|---|---|
| Blade templates | Renderização server-side |
| Tailwind CSS v3 | Estilização utility-first |
| Alpine.js v3 | Interatividade client-side (dropdown, toggle, accordion) |
| Assets estáticos em `public/assets/` | CSS/JS de bibliotecas externas (Bootstrap, jQuery, Slick, etc.) |
| Vite | Compila apenas `resources/css/app.css` e `resources/js/app.js` |

---

## Rotas Públicas

| Método | URI | Controller@método | Nome | Descrição |
|---|---|---|---|---|
| GET | `/` | `HomeController@index` | — | Homepage completa |
| GET | `/portfolio` | `HomeController@portfolio` | — | Listagem do portfólio |
| GET | `/portfolio-details/{id}` | `HomeController@showPortfolio` | — | Detalhe de um projeto |
| GET | `/blogs` | `HomeController@blog` | — | Listagem do blog |
| GET | `/blog-details/{id}` | `HomeController@showBlog` | — | Detalhe de um post |
| POST | `/contact` | `HomeController@contact` | — | Envio do formulário de contato |

---

## `HomeController` — Fluxo de Dados

O `HomeController` busca todos os dados necessários e os passa para a view. Padrão: **múltiplos queries Eloquent, sem caching**.

### `index()` — Homepage

```php
public function index()
{
    $hero              = Hero::latest()->first();
    $typerTitles       = TyperTitle::all();
    $services          = Service::latest()->take(6)->get();
    $about             = About::first();
    $portfolioSetting  = PortfolioSectionSetting::first();
    $categories        = Category::all();
    $portfolioItems    = PortfolioItem::with('category')->latest()->get();
    $skillSetting      = SkillSectionSetting::first();
    $skillItems        = SkillItem::all();
    $experiences       = Experience::all();
    $feedbackSetting   = FeedbackSectionSetting::first();
    $feedbacks         = Feedback::all();
    $blogSetting       = BlogSectionSetting::first();
    $blogs             = Blog::with('blogCategory')->latest()->take(3)->get();
    $contactSetting    = ContactSectionSetting::first();
    $footerInfo        = FooterInfo::first();
    $footerContactInfo = FooterContactInfo::first();
    $footerSocialLinks = FooterSocialLink::all();
    $footerUsefulLinks = FooterUsefulLink::all();
    $footerHelpLinks   = FooterHelpLink::all();
    $seoSetting        = SeoSetting::first();

    return view('frontend.home', compact(
        'hero', 'typerTitles', 'services', 'about',
        'portfolioSetting', 'categories', 'portfolioItems',
        'skillSetting', 'skillItems', 'experiences',
        'feedbackSetting', 'feedbacks',
        'blogSetting', 'blogs',
        'contactSetting',
        'footerInfo', 'footerContactInfo', 'footerSocialLinks',
        'footerUsefulLinks', 'footerHelpLinks',
        'seoSetting'
    ));
}
```

### `showPortfolio($id)` — Detalhe de Projeto

```php
public function showPortfolio($id)
{
    $portfolioItem = PortfolioItem::with('category')->findOrFail($id);
    $footerInfo    = FooterInfo::first();
    // ...dados de rodapé e SEO
    return view('frontend.portfolio-details', compact('portfolioItem', ...));
}
```

### `contact()` — Formulário de Contato

```php
public function contact(Request $request)
{
    $request->validate([
        'name'    => 'required|string|max:255',
        'email'   => 'required|email',
        'subject' => 'required|string',
        'message' => 'required|string',
    ]);

    Mail::to($adminEmail)->send(new ContactMail($request->all()));

    return redirect()->back()->with('success', 'Mensagem enviada!');
}
```

---

## Estrutura de Views do Frontend

```
resources/views/frontend/
├── home.blade.php              ← Homepage completa (todas as seções)
├── portfolio.blade.php         ← Listagem de projetos
├── portfolio-details.blade.php ← Detalhe de um projeto
├── blog.blade.php              ← Listagem de posts
└── blog-details.blade.php      ← Detalhe de um post

resources/views/layouts/
└── frontend.blade.php          ← Layout base do site público
```

---

## Seções da Homepage

A homepage exibe todas as seções em sequência vertical:

| Ordem | Seção | Model(s) usados | Funcionalidade |
|---|---|---|---|
| 1 | **Hero/Banner** | `Hero`, `TyperTitle` | Imagem de fundo, título com Typer.js, botão CTA |
| 2 | **Services** | `Service` | Grid de serviços (até 6) |
| 3 | **About** | `About` | Foto, texto, botão download currículo |
| 4 | **Portfolio** | `PortfolioSectionSetting`, `Category`, `PortfolioItem` | Galeria com filtro por categoria (Alpine.js/JS) |
| 5 | **Skills** | `SkillSectionSetting`, `SkillItem` | Barras de progresso animadas |
| 6 | **Experience** | `Experience` | Cards de experiência/processo |
| 7 | **Feedback** | `FeedbackSectionSetting`, `Feedback` | Carousel de depoimentos |
| 8 | **Blog** | `BlogSectionSetting`, `Blog` | 3 posts mais recentes |
| 9 | **Contact** | `ContactSectionSetting` | Formulário de contato |
| 10 | **Footer** | `FooterInfo`, `FooterContactInfo`, `FooterSocialLink`, `FooterUsefulLink`, `FooterHelpLink` | Rodapé completo |

---

## Assets Estáticos

Os assets em `public/assets/` são servidos diretamente, sem passar pelo Vite:

```
public/assets/
├── css/
│   ├── bootstrap.min.css
│   ├── style.css               ← CSS principal do tema
│   └── ...
├── js/
│   ├── jquery.min.js
│   ├── bootstrap.bundle.min.js
│   ├── typer.js                ← Animação de texto
│   ├── slick.min.js            ← Carousel de depoimentos
│   └── main.js                 ← JS customizado do tema
├── fonts/
└── images/
```

Referenciados via `asset()`:
```blade
<link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
```

---

## Typer.js — Animação de Títulos

O hero exibe um efeito de digitação com os títulos gerenciados pelo admin.

### No controller
```php
$typerTitles = TyperTitle::all(); // ['PHP Developer', 'Laravel Expert', ...]
```

### Na view
```blade
<span class="typer" data-words="{{ $typerTitles->pluck('title')->implode(',') }}"></span>
```

---

## Filtro de Portfólio por Categoria

O portfólio usa filtragem client-side (JavaScript/Alpine.js) baseada em atributos `data-filter`:

```blade
{{-- Botões de filtro --}}
<button data-filter="all">Todos</button>
@foreach($categories as $cat)
    <button data-filter="{{ $cat->slug }}">{{ $cat->name }}</button>
@endforeach

{{-- Items --}}
@foreach($portfolioItems as $item)
    <div class="portfolio-item" data-category="{{ $item->category->slug }}">
        <img src="/{{ $item->image }}" alt="{{ $item->title }}">
    </div>
@endforeach
```

---

## Download de Currículo

O admin pode fazer upload de um arquivo PDF na seção About. O download é servido via rota admin:

```
GET /admin/about/resume/download → AboutController@downloadResume
```

No frontend, o botão aponta para a rota de download:

```blade
<a href="{{ route('admin.about.resume.download') }}" download>
    Download CV
</a>
```

---

## Formulário de Contato — Mail

### `app/Mail/ContactMail.php`

```php
class ContactMail extends Mailable
{
    public function __construct(public array $data) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: $this->data['subject']);
    }

    public function content(): Content
    {
        return new Content(view: 'mail.contact');
    }
}
```

### Template do e-mail
```
resources/views/mail/contact.blade.php
```

---

## SEO

O `SeoSetting` é carregado em todas as pages do frontend e injetado no `<head>`:

```blade
{{-- No layout frontend --}}
<title>{{ $seoSetting->meta_title ?? config('app.name') }}</title>
<meta name="description" content="{{ $seoSetting->meta_description }}">
<meta name="keywords"    content="{{ $seoSetting->meta_keywords }}">
```

---

## Vite — Assets Compilados

Apenas dois arquivos passam pelo pipeline Vite:

```js
// vite.config.js
export default defineConfig({
    plugins: [laravel({
        input: ['resources/css/app.css', 'resources/js/app.js'],
        refresh: true,
    })],
});
```

```blade
{{-- No layout --}}
@vite(['resources/css/app.css', 'resources/js/app.js'])
```

O restante dos assets (Bootstrap, jQuery, tema) é estático em `public/assets/`.
