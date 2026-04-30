<p align="center">
  <a href="https://laravel.com" target="_blank">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
  </a>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-10.x-red?logo=laravel" alt="Laravel 10">
  <img src="https://img.shields.io/badge/PHP-8.1%2B-blue?logo=php" alt="PHP 8.1+">
  <img src="https://img.shields.io/badge/TailwindCSS-3.x-06B6D4?logo=tailwindcss" alt="Tailwind CSS">
  <img src="https://img.shields.io/badge/Alpine.js-3.x-8BC0D0?logo=alpinedotjs" alt="Alpine.js">
  <img src="https://img.shields.io/badge/MySQL-8.x-4479A1?logo=mysql&logoColor=white" alt="MySQL">
  <img src="https://img.shields.io/badge/License-MIT-green" alt="MIT License">
</p>

---

# Portfolio & Blog — Laravel 10

Um sistema completo de **portfolio pessoal com blog** e painel de administração, construido com Laravel 10, Tailwind CSS e Alpine.js. Permite gerenciar projetos, posts, habilidades, experiencias e todas as configuracoes do site atraves de um CMS proprio.

## Sumario

- [Funcionalidades](#funcionalidades)
- [Stack Tecnologico](#stack-tecnologico)
- [Instalacao Rapida](#instalacao-rapida)
- [Configuracao](#configuracao)
- [Acesso ao Sistema](#acesso-ao-sistema)
- [Estrutura do Projeto](#estrutura-do-projeto)
- [Documentacao](#documentacao)
- [Contribuindo](#contribuindo)
- [Licenca](#licenca)

---

## Funcionalidades

### Frontend (Publico)
- **Portfolio** — Showcase de projetos com categorias e detalhes
- **Blog** — Posts paginados com categorias e navegacao prev/next
- **Sobre** — Secao de apresentacao com download de curriculo
- **Habilidades** — Barras de progresso dinamicas por habilidade
- **Servicos** — Lista de servicos oferecidos
- **Experiencias** — Historico de trabalho/experiencias
- **Depoimentos** — Carrossel de feedbacks de clientes
- **Contato** — Formulario com envio de e-mail via AJAX
- **Design responsivo** — Mobile-first com Tailwind CSS

### Painel Admin (Protegido)
- CRUD completo para todos os conteudos do site
- Upload e gerenciamento de imagens
- DataTables para listagem eficiente (Yajra)
- Configuracoes por secao (titulos, subtitulos, etc.)
- Configuracoes gerais e SEO do site
- Gerenciamento do rodape (redes sociais, links, contato)
- Notificacoes toast em todas as acoes

---

## Stack Tecnologico

| Camada | Tecnologia | Versao |
|---|---|---|
| Backend | Laravel | 10.x |
| Linguagem | PHP | 8.1+ |
| Banco de Dados | MySQL | 8.x |
| CSS Framework | Tailwind CSS | 3.x |
| JS Reativo | Alpine.js | 3.x |
| Build Tool | Vite | 4.x |
| DataTables | Yajra DataTables | 10.x |
| Autenticacao API | Laravel Sanctum | 3.x |
| Notificacoes | Yoeunes Toastr | 2.x |
| HTTP Client | Guzzle | 7.x |
| Testes | PHPUnit | 10.x |

---

## Instalacao Rapida

### Pre-requisitos

- PHP >= 8.1
- Composer >= 2.x
- Node.js >= 18.x + npm
- MySQL >= 8.x
- Git

### Passo a passo

**1. Clonar o repositorio**
```bash
git clone https://github.com/mokammeltanvir/Portfolio-Web-Laravel.git
cd Portfolio-Web-Laravel
```

**2. Instalar dependencias PHP**
```bash
composer install
```

**3. Configurar o ambiente**
```bash
cp .env.example .env
php artisan key:generate
```

**4. Configurar banco de dados** — editar o `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=portfolio_web_laravel
DB_USERNAME=root
DB_PASSWORD=
```

**5. Executar migrations e seeders**
```bash
php artisan migrate
php artisan db:seed
```

**6. Instalar dependencias frontend e compilar assets**
```bash
npm install
npm run dev
```

**7. Iniciar o servidor**
```bash
php artisan serve
```

A aplicacao estara disponivel em `http://localhost:8000`.

---

## Configuracao

### Configuracao de E-mail (Contato)

No `.env`, configure o servidor de e-mail para o formulario de contato funcionar:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=seu@email.com
MAIL_PASSWORD=sua_senha_app
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=seu@email.com
MAIL_FROM_NAME="${APP_NAME}"
```

> Para desenvolvimento local, o **Mailpit** ja esta pre-configurado no `.env.example`.

### Storage de Uploads

```bash
php artisan storage:link
```

Os uploads sao salvos em `public/uploads/`.

---

## Acesso ao Sistema

Apos executar os seeders, o usuario administrador padrao e:

| Campo | Valor |
|---|---|
| URL Admin | `http://localhost:8000/login` |
| E-mail | `admin@domain.com` |
| Senha | `password` |

> **Altere a senha apos o primeiro login em producao.**

---

## Estrutura do Projeto

```
app-portfolio-blog-laravel/
├── app/
│   ├── DataTables/          # 12 classes DataTable (Yajra)
│   ├── Http/
│   │   └── Controllers/
│   │       ├── Admin/       # 27 controllers do painel admin
│   │       ├── Auth/        # Controllers de autenticacao
│   │       └── Frontend/    # Controller publico (HomeController)
│   ├── Mail/                # Mailables (ContactMail)
│   ├── Models/              # 24 modelos Eloquent
│   └── helper/
│       └── helpers.php      # Funcoes auxiliares globais
├── database/
│   ├── migrations/          # 28 migrations
│   ├── seeders/             # 17 seeders
│   └── factories/
├── resources/
│   └── views/
│       ├── admin/           # Views do painel admin
│       ├── frontend/        # Views publicas
│       │   ├── pages/       # Paginas completas
│       │   ├── widgets/     # Secoes reutilizaveis
│       │   └── layouts/     # Layouts base
│       ├── auth/            # Views de autenticacao
│       ├── components/      # Componentes Blade
│       └── mail/            # Templates de e-mail
├── routes/
│   ├── web.php              # 70+ rotas web
│   ├── api.php              # Endpoints API (Sanctum)
│   └── auth.php             # Rotas de autenticacao
├── docs/                    # Documentacao tecnica
│   ├── architecture.md
│   ├── database.md
│   ├── deployment.md
│   ├── api-routes.md
│   └── admin-guide.md
├── .env.example
├── composer.json
├── package.json
└── vite.config.js
```

---

## Documentacao

A documentacao tecnica completa esta disponivel no diretorio [`docs/`](docs/):

| Documento | Descricao |
|---|---|
| [Arquitetura](docs/architecture.md) | Visao geral da arquitetura, padroes e decisoes de design |
| [Banco de Dados](docs/database.md) | Schema completo, relacionamentos e diagramas |
| [Deploy](docs/deployment.md) | Guia de implantacao em producao (VPS, shared hosting, Docker) |
| [Rotas & API](docs/api-routes.md) | Referencia completa de rotas web e endpoints de API |
| [Guia Admin](docs/admin-guide.md) | Manual do painel de administracao |

---

## Contribuindo

Contribuicoes sao bem-vindas!

1. Faca um fork do repositorio
2. Crie uma branch para sua feature: `git checkout -b feature/minha-feature`
3. Commit suas mudancas: `git commit -m 'feat: adiciona minha feature'`
4. Push para a branch: `git push origin feature/minha-feature`
5. Abra um Pull Request

---

## Licenca

Este projeto esta licenciado sob a [MIT License](https://opensource.org/licenses/MIT).

---

<p align="center">Feito com Laravel 10</p>
