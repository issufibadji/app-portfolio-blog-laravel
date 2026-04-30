# Rotas e API

## Rotas Web (Publico)

Prefixo base: `/`

| Metodo | URI | Controller | Action | Descricao |
|---|---|---|---|---|
| GET | `/` | `HomeController` | `index` | Homepage completa |
| GET | `/portfolio` | `HomeController` | `portfolio` | Lista todos os projetos |
| GET | `/portfolio-details/{id}` | `HomeController` | `showPortfolio` | Detalhes de um projeto |
| GET | `/blogs` | `HomeController` | `blog` | Lista posts (9 por pagina) |
| GET | `/blog-details/{id}` | `HomeController` | `showBlog` | Detalhes de um post |
| POST | `/contact` | `HomeController` | `contact` | Envio do formulario de contato |

### Formulario de Contato — Request

```
POST /contact
Content-Type: application/x-www-form-urlencoded

name:    string (max: 200, required)
subject: string (max: 300, required)
email:   string (email, required)
message: string (max: 2000, required)
_token:  string (CSRF token)
```

**Resposta de sucesso:**
```json
{ "status": "success", "message": "Message sent successfully!" }
```

**Resposta de erro:**
```json
{ "status": "error", "message": "..." }
```

---

## Rotas de Autenticacao

| Metodo | URI | Descricao |
|---|---|---|
| GET | `/login` | Pagina de login |
| POST | `/login` | Autenticar usuario |
| POST | `/logout` | Encerrar sessao |
| GET | `/register` | Pagina de registro |
| POST | `/register` | Criar novo usuario |
| GET | `/forgot-password` | Solicitacao de reset de senha |
| POST | `/forgot-password` | Enviar e-mail de reset |
| GET | `/reset-password/{token}` | Formulario de nova senha |
| POST | `/reset-password` | Salvar nova senha |
| GET | `/verify-email` | Instrucoes de verificacao |
| GET | `/verify-email/{id}/{hash}` | Confirmar e-mail |
| POST | `/email/verification-notification` | Reenviar e-mail de verificacao |

---

## Rotas Admin (Protegidas)

**Middleware:** `auth`, `verified`  
**Prefixo:** `/admin`

### Dashboard e Perfil

| Metodo | URI | Descricao |
|---|---|---|
| GET | `/dashboard` | Dashboard principal |
| GET | `/profile` | Pagina de perfil |
| PATCH | `/profile` | Atualizar perfil |
| DELETE | `/profile` | Excluir conta |

### Hero / Banner

| Metodo | URI | Descricao |
|---|---|---|
| GET | `/admin/hero` | Listar banners |
| GET | `/admin/hero/create` | Formulario de criacao |
| POST | `/admin/hero` | Salvar novo banner |
| GET | `/admin/hero/{id}/edit` | Formulario de edicao |
| PUT/PATCH | `/admin/hero/{id}` | Atualizar banner |
| DELETE | `/admin/hero/{id}` | Excluir banner |

### Typer Titles (Animacao de Digitacao)

| Metodo | URI | Descricao |
|---|---|---|
| GET | `/admin/typer-title` | Listar titulos |
| GET | `/admin/typer-title/create` | Criar titulo |
| POST | `/admin/typer-title` | Salvar titulo |
| GET | `/admin/typer-title/{id}/edit` | Editar titulo |
| PUT/PATCH | `/admin/typer-title/{id}` | Atualizar titulo |
| DELETE | `/admin/typer-title/{id}` | Excluir titulo |

### Servicos

| Metodo | URI | Descricao |
|---|---|---|
| GET | `/admin/service` | Listar servicos |
| GET | `/admin/service/create` | Criar servico |
| POST | `/admin/service` | Salvar servico |
| GET | `/admin/service/{id}/edit` | Editar servico |
| PUT/PATCH | `/admin/service/{id}` | Atualizar servico |
| DELETE | `/admin/service/{id}` | Excluir servico |

### Sobre

| Metodo | URI | Descricao |
|---|---|---|
| GET | `/admin/about` | Listar |
| GET | `/admin/about/create` | Criar |
| POST | `/admin/about` | Salvar |
| GET | `/admin/about/{id}/edit` | Editar |
| PUT/PATCH | `/admin/about/{id}` | Atualizar |
| DELETE | `/admin/about/{id}` | Excluir |
| GET | `/admin/resume/download` | Download do curriculo |

### Portfolio

| Metodo | URI | Descricao |
|---|---|---|
| GET | `/admin/category` | Categorias de portfolio |
| GET | `/admin/portfolio-item` | Projetos |
| POST | `/admin/category` | Criar categoria |
| POST | `/admin/portfolio-item` | Criar projeto |
| GET/PATCH/DELETE | `/admin/category/{id}` | Gerenciar categoria |
| GET/PATCH/DELETE | `/admin/portfolio-item/{id}` | Gerenciar projeto |
| GET/PATCH | `/admin/portfolio-section-setting` | Config da secao |

### Habilidades

| Metodo | URI | Descricao |
|---|---|---|
| GET | `/admin/skill-item` | Listar habilidades |
| POST | `/admin/skill-item` | Criar habilidade |
| GET/PATCH/DELETE | `/admin/skill-item/{id}` | Gerenciar habilidade |
| GET/PATCH | `/admin/skill-section-setting` | Config da secao |

### Experiencias e Depoimentos

| Metodo | URI | Descricao |
|---|---|---|
| GET | `/admin/experience` | Listar experiencias |
| POST | `/admin/experience` | Criar experiencia |
| GET/PATCH/DELETE | `/admin/experience/{id}` | Gerenciar experiencia |
| GET | `/admin/feedback` | Listar depoimentos |
| POST | `/admin/feedback` | Criar depoimento |
| GET/PATCH/DELETE | `/admin/feedback/{id}` | Gerenciar depoimento |
| GET/PATCH | `/admin/feedback-section-setting` | Config da secao |

### Blog

| Metodo | URI | Descricao |
|---|---|---|
| GET | `/admin/blog-category` | Categorias de blog |
| POST | `/admin/blog-category` | Criar categoria |
| GET/PATCH/DELETE | `/admin/blog-category/{id}` | Gerenciar categoria |
| GET | `/admin/blog` | Listar posts |
| POST | `/admin/blog` | Criar post |
| GET/PATCH/DELETE | `/admin/blog/{id}` | Gerenciar post |
| GET/PATCH | `/admin/blog-section-setting` | Config da secao blog |

### Contato e Rodape

| Metodo | URI | Descricao |
|---|---|---|
| GET/PATCH | `/admin/contact-section-setting` | Config secao contato |
| GET | `/admin/footer-social` | Links sociais |
| GET | `/admin/footer-info` | Info do rodape |
| GET | `/admin/footer-contact-info` | Contatos no rodape |
| GET | `/admin/footer-useful-links` | Links uteis |
| GET | `/admin/footer-help-links` | Links de ajuda |

### Configuracoes Gerais

| Metodo | URI | Descricao |
|---|---|---|
| GET/PATCH | `/admin/general-setting` | Logo, nome do site |
| GET/PATCH | `/admin/seo-setting` | Meta title, description, keywords |

---

## API REST (Sanctum)

**Prefixo:** `/api`  
**Middleware:** `auth:sanctum`  
**Headers necessarios:**
```
Authorization: Bearer {token}
Accept: application/json
```

| Metodo | URI | Descricao |
|---|---|---|
| GET | `/api/user` | Retorna o usuario autenticado |

### Obtendo um Token Sanctum

```bash
# Via Tinker
php artisan tinker

$user = App\Models\User::first();
$token = $user->createToken('nome-do-token')->plainTextToken;
echo $token;
```

### Exemplo de Requisicao

```bash
curl -X GET https://meusite.com/api/user \
  -H "Authorization: Bearer 1|abc123..." \
  -H "Accept: application/json"
```

**Resposta:**
```json
{
  "id": 1,
  "name": "Admin",
  "email": "admin@domain.com",
  "email_verified_at": "2024-01-01T00:00:00.000000Z",
  "created_at": "2024-01-01T00:00:00.000000Z",
  "updated_at": "2024-01-01T00:00:00.000000Z"
}
```

---

## DataTables AJAX

O painel admin usa o **Yajra DataTables** com requisicoes AJAX automaticas. Cada listagem admin faz uma requisicao GET para a propria URI com o parametro `?draw=1`:

```
GET /admin/blog?draw=1&start=0&length=10&search[value]=...
```

**Resposta padrao:**
```json
{
  "draw": 1,
  "recordsTotal": 25,
  "recordsFiltered": 10,
  "data": [...]
}
```
