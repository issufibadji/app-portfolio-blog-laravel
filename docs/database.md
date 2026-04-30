# Banco de Dados

## Visao Geral

O projeto utiliza **MySQL** com **28 migrations** gerenciando o schema. Todas as tabelas usam o driver InnoDB com suporte a transacoes e foreign keys.

**Banco padrao:** `portfolio_web_laravel`

---

## Diagrama de Relacionamentos (ERD simplificado)

```
┌──────────────┐         ┌──────────────────┐
│   categories │◄────────┤  portfolio_items  │
│──────────────│  1   N  │──────────────────│
│ id           │         │ id               │
│ name         │         │ title            │
│ slug         │         │ image            │
│ timestamps   │         │ category_id (FK) │
└──────────────┘         │ description      │
                         │ client           │
                         │ website          │
                         │ timestamps       │
                         └──────────────────┘

┌──────────────────┐      ┌──────────┐
│  blog_categories │◄─────┤  blogs   │
│──────────────────│ 1  N │──────────│
│ id               │      │ id       │
│ name             │      │ title    │
│ slug             │      │ image    │
│ timestamps       │      │ category │ (FK -> blog_categories.id)
└──────────────────┘      │ description│
                          │ timestamps │
                          └────────────┘

┌───────┐
│ users │  (admin — autenticacao local)
│───────│
│ id    │
│ name  │
│ email │
│ password│
│ email_verified_at│
│ timestamps│
└───────┘
```

---

## Schema Completo por Tabela

### Conteudo Principal

#### `heroes`
| Coluna | Tipo | Descricao |
|---|---|---|
| id | bigint UNSIGNED PK | |
| title | string | Titulo principal do banner |
| sub_title | string | Subtitulo |
| btn_text | string | Texto do botao CTA |
| btn_url | string | URL do botao CTA |
| image | string | Caminho da imagem do hero |
| deleted_at | timestamp NULL | Soft delete |
| created_at | timestamp | |
| updated_at | timestamp | |

#### `abouts`
| Coluna | Tipo | Descricao |
|---|---|---|
| id | bigint UNSIGNED PK | |
| title | string | Titulo da secao sobre |
| description | text | Texto de apresentacao |
| image | string | Foto de perfil |
| resume | string | Caminho do arquivo de curriculo |
| created_at | timestamp | |
| updated_at | timestamp | |

#### `services`
| Coluna | Tipo | Descricao |
|---|---|---|
| id | bigint UNSIGNED PK | |
| name | string | Nome do servico |
| description | text | Descricao do servico |
| created_at | timestamp | |
| updated_at | timestamp | |

#### `categories`
| Coluna | Tipo | Descricao |
|---|---|---|
| id | bigint UNSIGNED PK | |
| name | string | Nome da categoria |
| slug | string | Slug unico |
| created_at | timestamp | |
| updated_at | timestamp | |

#### `portfolio_items`
| Coluna | Tipo | Descricao |
|---|---|---|
| id | bigint UNSIGNED PK | |
| image | string | Imagem de capa do projeto |
| title | string | Titulo do projeto |
| category_id | bigint UNSIGNED FK | Referencia `categories.id` |
| description | text | Descricao do projeto |
| client | string NULL | Nome do cliente |
| website | string NULL | URL do projeto |
| created_at | timestamp | |
| updated_at | timestamp | |

#### `skill_items`
| Coluna | Tipo | Descricao |
|---|---|---|
| id | bigint UNSIGNED PK | |
| name | string | Nome da habilidade |
| percent | integer | Percentual de dominio (0-100) |
| created_at | timestamp | |
| updated_at | timestamp | |

#### `experiences`
| Coluna | Tipo | Descricao |
|---|---|---|
| id | bigint UNSIGNED PK | |
| image | string | Logo/imagem da empresa |
| title | string | Cargo / titulo da experiencia |
| description | text | Descricao das atividades |
| phone | string NULL | Telefone de contato |
| email | string NULL | E-mail de contato |
| created_at | timestamp | |
| updated_at | timestamp | |

#### `feedback`
| Coluna | Tipo | Descricao |
|---|---|---|
| id | bigint UNSIGNED PK | |
| name | string | Nome do cliente |
| position | string | Cargo do cliente |
| description | text | Depoimento |
| created_at | timestamp | |
| updated_at | timestamp | |

#### `typer_titles`
| Coluna | Tipo | Descricao |
|---|---|---|
| id | bigint UNSIGNED PK | |
| title | string | Texto para animacao de digitacao |
| created_at | timestamp | |
| updated_at | timestamp | |

### Blog

#### `blog_categories`
| Coluna | Tipo | Descricao |
|---|---|---|
| id | bigint UNSIGNED PK | |
| name | string | Nome da categoria |
| slug | string | Slug unico |
| created_at | timestamp | |
| updated_at | timestamp | |

#### `blogs`
| Coluna | Tipo | Descricao |
|---|---|---|
| id | bigint UNSIGNED PK | |
| image | string | Imagem de capa do post |
| title | string | Titulo do post |
| category | bigint UNSIGNED FK | Referencia `blog_categories.id` |
| description | longtext | Conteudo HTML do post |
| created_at | timestamp | |
| updated_at | timestamp | |

### Configuracoes de Secao

Cada tabela de configuracao armazena **um unico registro** com os metadados de exibicao da secao correspondente.

#### `portfolio_section_settings`
| Coluna | Tipo | Descricao |
|---|---|---|
| id | bigint UNSIGNED PK | |
| title | string | Titulo da secao portfolio |
| sub_title | string | Subtitulo |
| created_at | timestamp | |
| updated_at | timestamp | |

> Mesmo padrao para: `skill_section_settings`, `blog_section_settings`, `feedback_section_settings`, `contact_section_settings`

#### `general_settings`
| Coluna | Tipo | Descricao |
|---|---|---|
| id | bigint UNSIGNED PK | |
| logo | string | Caminho do logotipo |
| site_name | string | Nome do site |
| created_at | timestamp | |
| updated_at | timestamp | |

#### `seo_settings`
| Coluna | Tipo | Descricao |
|---|---|---|
| id | bigint UNSIGNED PK | |
| meta_title | string | Titulo para SEO |
| meta_description | text | Descricao meta |
| meta_keywords | text | Palavras-chave |
| created_at | timestamp | |
| updated_at | timestamp | |

### Rodape

#### `footer_social_links`
| Coluna | Tipo | Descricao |
|---|---|---|
| id | bigint UNSIGNED PK | |
| icon | string | Classe do icone (FontAwesome, etc.) |
| url | string | URL da rede social |
| created_at | timestamp | |
| updated_at | timestamp | |

#### `footer_infos`
| Coluna | Tipo | Descricao |
|---|---|---|
| id | bigint UNSIGNED PK | |
| description | text | Texto de apresentacao no rodape |
| created_at | timestamp | |
| updated_at | timestamp | |

#### `footer_contact_infos`
| Coluna | Tipo | Descricao |
|---|---|---|
| id | bigint UNSIGNED PK | |
| icon | string | Icone de contato |
| text | string | Texto de contato |
| created_at | timestamp | |
| updated_at | timestamp | |

#### `footer_useful_links` / `footer_help_links`
| Coluna | Tipo | Descricao |
|---|---|---|
| id | bigint UNSIGNED PK | |
| name | string | Nome do link |
| url | string | URL do link |
| created_at | timestamp | |
| updated_at | timestamp | |

### Sistema

#### `users`
| Coluna | Tipo | Descricao |
|---|---|---|
| id | bigint UNSIGNED PK | |
| name | string | Nome do usuario |
| email | string UNIQUE | E-mail de login |
| email_verified_at | timestamp NULL | Data de verificacao |
| password | string | Hash bcrypt da senha |
| remember_token | string NULL | Token "lembrar-me" |
| created_at | timestamp | |
| updated_at | timestamp | |

#### `personal_access_tokens` (Sanctum)
| Coluna | Tipo | Descricao |
|---|---|---|
| id | bigint UNSIGNED PK | |
| tokenable_type | string | Tipo do modelo (polymorphic) |
| tokenable_id | bigint UNSIGNED | ID do modelo |
| name | string | Nome do token |
| token | string(64) UNIQUE | Hash do token |
| abilities | text NULL | Permissoes JSON |
| last_used_at | timestamp NULL | |
| expires_at | timestamp NULL | |
| created_at | timestamp | |
| updated_at | timestamp | |

#### `password_reset_tokens`
| Coluna | Tipo | Descricao |
|---|---|---|
| email | string PK | |
| token | string | Token de reset |
| created_at | timestamp NULL | |

#### `failed_jobs`
| Coluna | Tipo | Descricao |
|---|---|---|
| id | bigint UNSIGNED PK | |
| uuid | string UNIQUE | Identificador unico |
| connection | text | Conexao da fila |
| queue | text | Nome da fila |
| payload | longtext | Dados do job |
| exception | longtext | Excecao ocorrida |
| failed_at | timestamp | |

#### `sessions`
| Coluna | Tipo | Descricao |
|---|---|---|
| id | varchar(255) PK | |
| user_id | bigint UNSIGNED NULL | FK users |
| ip_address | varchar(45) NULL | |
| user_agent | text NULL | |
| payload | longtext | |
| last_activity | integer INDEX | |

---

## Comandos Uteis

```bash
# Executar todas as migrations
php artisan migrate

# Reverter todas as migrations
php artisan migrate:rollback

# Resetar e recriar o banco
php artisan migrate:fresh

# Resetar com dados de seed
php artisan migrate:fresh --seed

# Ver status das migrations
php artisan migrate:status

# Executar apenas os seeders
php artisan db:seed
```

---

## Seeders Disponiveis

| Seeder | Descricao |
|---|---|
| `UserSeeder` | Cria usuario admin padrao |
| `HeroSeeder` | Dados iniciais do banner |
| `ServiceSeeder` | Servicos de exemplo |
| `AboutSeeder` | Dados de apresentacao |
| `PortfolioSectionSettingsSeeder` | Config da secao portfolio |
| `SkillItemsSeeder` | Habilidades de exemplo |
| `SkillSectionSettingsSeeder` | Config da secao habilidades |
| `ExperienceSeeder` | Experiencias de exemplo |
| `FeedbackSectionSettingsSeeder` | Config da secao depoimentos |
| `FeedBackSeeder` | Depoimentos de exemplo |
| `BlogCategoriesSeeder` | Categorias de blog |
| `BlogSeeder` | Posts de blog de exemplo |
| `BlogSectionSettingsSeeder` | Config da secao blog |
| `GeneralSettingsSeeder` | Configuracoes gerais do site |
| `FooterInfosSeeder` | Informacoes do rodape |
| `FooterContactInfoSeeder` | Contatos do rodape |
| `FooterSocialLinksSeeder` | Links sociais do rodape |
