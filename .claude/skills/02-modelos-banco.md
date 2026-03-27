# 02 — Models e Banco de Dados

Documentação completa de todos os models Eloquent, tabelas, campos, relacionamentos e regras de banco do projeto.

---

## Mapa de Relacionamentos

```
Category (1) ──────── (N) PortfolioItem
BlogCategory (1) ────── (N) Blog
User ─────────────────── autenticação (independente dos demais models)
```

Todos os demais models são **independentes** (sem relacionamentos entre si).

---

## Models — Tabela de Referência Rápida

| Model | Tabela | Relacionamentos | Soft Delete | Propósito |
|---|---|---|---|---|
| `User` | `users` | — | Não | Autenticação admin |
| `Hero` | `heroes` | — | **Sim** | Banner principal do site |
| `TyperTitle` | `typer_titles` | — | Não | Títulos para animação Typer.js |
| `Service` | `services` | — | Não | Serviços/especialidades exibidos |
| `About` | `abouts` | — | Não | Conteúdo da seção Sobre |
| `Category` | `categories` | hasMany: PortfolioItem | Não | Categorias do portfólio |
| `PortfolioItem` | `portfolio_items` | belongsTo: Category | Não | Projetos do portfólio |
| `PortfolioSectionSetting` | `portfolio_section_settings` | — | Não | Título/subtítulo da seção Portfólio |
| `SkillSectionSetting` | `skill_section_settings` | — | Não | Título/subtítulo da seção Skills |
| `SkillItem` | `skill_items` | — | Não | Itens de skill com percentual |
| `Experience` | `experiences` | — | Não | Seção de experiência/processo |
| `Feedback` | `feedbacks` | — | Não | Depoimentos de clientes |
| `FeedbackSectionSetting` | `feedback_section_settings` | — | Não | Título/subtítulo da seção Depoimentos |
| `BlogCategory` | `blog_categories` | hasMany: Blog | Não | Categorias do blog |
| `Blog` | `blogs` | belongsTo: BlogCategory | Não | Posts do blog |
| `BlogSectionSetting` | `blog_section_settings` | — | Não | Título/subtítulo da seção Blog |
| `ContactSectionSetting` | `contact_section_settings` | — | Não | Título/subtítulo da seção Contato |
| `FooterSocialLink` | `footer_social_links` | — | Não | Links de redes sociais no rodapé |
| `FooterInfo` | `footer_infos` | — | Não | Texto e copyright do rodapé |
| `FooterContactInfo` | `footer_contact_infos` | — | Não | Endereço/contato no rodapé |
| `FooterUsefulLink` | `footer_useful_links` | — | Não | Links úteis no rodapé |
| `FooterHelpLink` | `footer_help_links` | — | Não | Links de ajuda no rodapé |
| `GeneralSetting` | `general_settings` | — | Não | Configurações gerais (logo, nome) |
| `SeoSetting` | `seo_settings` | — | Não | Configurações de SEO |

---

## Schema Detalhado por Tabela

### `users`
| Campo | Tipo | Notas |
|---|---|---|
| id | bigint unsigned | PK auto-increment |
| name | varchar(255) | |
| email | varchar(255) | unique |
| email_verified_at | timestamp | nullable |
| password | varchar(255) | hash bcrypt |
| remember_token | varchar(100) | nullable |
| created_at, updated_at | timestamp | |

---

### `heroes`
| Campo | Tipo | Notas |
|---|---|---|
| id | bigint unsigned | PK |
| title | string | Título principal do banner |
| sub_title | text | Subtítulo/descrição |
| btn_text | string | Texto do botão CTA |
| btn_url | string | URL do botão CTA |
| image | string | Path da imagem do banner |
| created_at, updated_at | timestamp | |
| deleted_at | timestamp | SoftDeletes |

---

### `typer_titles`
| Campo | Tipo | Notas |
|---|---|---|
| id | bigint unsigned | PK |
| title | string | Título exibido na animação Typer.js |
| created_at, updated_at | timestamp | |

---

### `services`
| Campo | Tipo | Notas |
|---|---|---|
| id | bigint unsigned | PK |
| name | string | Nome do serviço |
| description | text | Descrição |
| created_at, updated_at | timestamp | |

---

### `abouts`
| Campo | Tipo | Notas |
|---|---|---|
| id | bigint unsigned | PK |
| title | string | Título da seção Sobre |
| description | text | Texto descritivo |
| image | string | Foto/imagem da seção |
| resume | string | Path do arquivo de currículo (PDF) |
| created_at, updated_at | timestamp | |

---

### `categories` (Portfólio)
| Campo | Tipo | Notas |
|---|---|---|
| id | bigint unsigned | PK |
| name | string | Nome da categoria |
| slug | string | Slug para filtragem |
| created_at, updated_at | timestamp | |

---

### `portfolio_items`
| Campo | Tipo | Notas |
|---|---|---|
| id | bigint unsigned | PK |
| image | string | Imagem do projeto |
| title | string | Título do projeto |
| category_id | bigint unsigned | FK → categories.id |
| description | text | Descrição detalhada |
| client | string | Nome do cliente |
| website | string | URL do projeto |
| created_at, updated_at | timestamp | |

---

### `*_section_settings` (Portfolio, Skill, Feedback, Blog, Contact)
| Campo | Tipo | Notas |
|---|---|---|
| id | bigint unsigned | PK |
| title | string | Título da seção |
| sub_title | string | Subtítulo da seção |
| created_at, updated_at | timestamp | |

> Cada seção (`portfolio`, `skill`, `feedback`, `blog`, `contact`) tem sua própria tabela `*_section_settings`. Registro único por tabela.

---

### `skill_items`
| Campo | Tipo | Notas |
|---|---|---|
| id | bigint unsigned | PK |
| title | string | Nome da skill |
| percentage | integer | Percentual de 0 a 100 |
| created_at, updated_at | timestamp | |

---

### `experiences`
| Campo | Tipo | Notas |
|---|---|---|
| id | bigint unsigned | PK |
| title | string | Título da experiência |
| description | text | Descrição |
| image | string | Ícone/imagem |
| phone | string | Telefone de contato |
| email | string | E-mail de contato |
| created_at, updated_at | timestamp | |

---

### `feedbacks`
| Campo | Tipo | Notas |
|---|---|---|
| id | bigint unsigned | PK |
| name | string | Nome do cliente |
| rating | integer | Avaliação (1-5) |
| message | text | Depoimento |
| image | string | Foto do cliente |
| created_at, updated_at | timestamp | |

---

### `blog_categories`
| Campo | Tipo | Notas |
|---|---|---|
| id | bigint unsigned | PK |
| name | string | Nome da categoria |
| slug | string | Slug da categoria |
| created_at, updated_at | timestamp | |

---

### `blogs`
| Campo | Tipo | Notas |
|---|---|---|
| id | bigint unsigned | PK |
| image | string | Imagem de capa do post |
| title | string | Título do post |
| category | integer | FK → blog_categories.id |
| description | longtext | Conteúdo do post |
| created_at, updated_at | timestamp | |

---

### `footer_social_links`
| Campo | Tipo | Notas |
|---|---|---|
| id | bigint unsigned | PK |
| icon | string | Classe do ícone (ex: `fab fa-github`) |
| url | string | URL da rede social |
| title | string | Nome da rede |
| created_at, updated_at | timestamp | |

---

### `footer_infos`
| Campo | Tipo | Notas |
|---|---|---|
| id | bigint unsigned | PK |
| info | text | Texto descritivo do rodapé |
| copy_right | string | Texto de copyright |
| powered_by | string | Texto "powered by" |
| created_at, updated_at | timestamp | |

---

### `footer_contact_infos`
| Campo | Tipo | Notas |
|---|---|---|
| id | bigint unsigned | PK |
| address | string | Endereço físico |
| phone | string | Telefone |
| email | string | E-mail de contato |
| created_at, updated_at | timestamp | |

---

### `footer_useful_links` / `footer_help_links`
| Campo | Tipo | Notas |
|---|---|---|
| id | bigint unsigned | PK |
| title | string | Texto do link |
| url | string | URL do link |
| created_at, updated_at | timestamp | |

---

### `general_settings`
| Campo | Tipo | Notas |
|---|---|---|
| id | bigint unsigned | PK |
| setting_key | string | Chave da configuração |
| setting_value | text | Valor da configuração |
| created_at, updated_at | timestamp | |

Exemplo de chaves: `logo`, `favicon`, `site_name`, `site_email`.

---

### `seo_settings`
| Campo | Tipo | Notas |
|---|---|---|
| id | bigint unsigned | PK |
| meta_title | string | Tag `<title>` |
| meta_description | text | Meta description |
| meta_keywords | text | Meta keywords |
| created_at, updated_at | timestamp | |

---

## Relacionamentos no Código

### `Category` → `PortfolioItem`

```php
// app/Models/Category.php
public function portfolioItems(): HasMany
{
    return $this->hasMany(PortfolioItem::class);
}

// app/Models/PortfolioItem.php
public function category(): BelongsTo
{
    return $this->belongsTo(Category::class);
}
```

### `BlogCategory` → `Blog`

```php
// app/Models/BlogCategory.php
public function blogs(): HasMany
{
    return $this->hasMany(Blog::class, 'category');
}

// app/Models/Blog.php
public function blogCategory(): BelongsTo
{
    return $this->belongsTo(BlogCategory::class, 'category');
}
```

---

## Comandos de Banco Úteis

```bash
# Rodar todas as migrations
php artisan migrate

# Reverter e refazer com seeders
php artisan migrate:fresh --seed

# Ver status das migrations
php artisan migrate:status

# Criar nova migration
php artisan make:migration create_nova_tabela_table

# Rollback da última migration
php artisan migrate:rollback
```
