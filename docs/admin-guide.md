# Guia do Painel Administrativo

## Acesso

| Campo | Valor |
|---|---|
| URL | `http://seusite.com/login` |
| E-mail padrao | `admin@domain.com` |
| Senha padrao | `password` |

> Altere a senha imediatamente apos o primeiro login em producao.

---

## Visao Geral do Painel

Apos o login, voce sera redirecionado para `/dashboard`. O menu lateral (sidebar) organiza todas as secoes gerenciaveis do site.

```
Dashboard
├── Hero / Banner
├── Typer Titles
├── Servicos
├── Sobre / About
├── Portfolio
│   ├── Categorias
│   ├── Projetos
│   └── Configuracoes da Secao
├── Habilidades
│   ├── Itens de Habilidade
│   └── Configuracoes da Secao
├── Experiencias
├── Depoimentos (Feedback)
│   └── Configuracoes da Secao
├── Blog
│   ├── Categorias
│   ├── Posts
│   └── Configuracoes da Secao
├── Contato
│   └── Configuracoes da Secao
├── Rodape
│   ├── Redes Sociais
│   ├── Informacoes
│   ├── Contato
│   ├── Links Uteis
│   └── Links de Ajuda
└── Configuracoes
    ├── Configuracoes Gerais
    └── SEO
```

---

## Gerenciamento de Conteudo

### Hero / Banner

O banner e a primeira secao que os visitantes veem. Voce pode configurar:

- **Titulo** — Headline principal
- **Subtitulo** — Texto secundario
- **Texto do botao CTA** — Ex: "Ver Projetos"
- **URL do botao CTA** — Link de destino
- **Imagem de fundo** — Foto ou ilustracao do banner

> Suporta **soft delete** — registros excluidos podem ser recuperados se necessario.

### Typer Titles (Animacao de Digitacao)

Textos que aparecem com efeito de digitacao dinamica no hero. Voce pode adicionar multiplos titulos que serao exibidos em rotacao:

- Ex: "Desenvolvedor Web", "Laravel Developer", "UI/UX Designer"

### Secao Sobre (About)

- **Titulo** — Cabecalho da secao
- **Descricao** — Texto de apresentacao (suporta HTML basico)
- **Foto** — Imagem de perfil
- **Curriculo** — Upload de arquivo PDF para download pelos visitantes

O curriculo pode ser baixado diretamente pelo admin em:  
`/admin/resume/download`

### Servicos

Lista de servicos oferecidos. Cada servico tem:
- Nome
- Descricao

### Portfolio

**Categorias de Portfolio:**
- Crie categorias antes de adicionar projetos
- Cada categoria tem nome e slug (URL amigavel)

**Projetos:**
- Imagem de capa
- Titulo do projeto
- Categoria (selecione da lista)
- Descricao detalhada
- Cliente (opcional)
- URL do projeto (opcional)

**Configuracoes da Secao:**
- Titulo e subtitulo da secao portfolio na homepage

### Habilidades

Cada habilidade tem:
- Nome (ex: "PHP", "Laravel", "Vue.js")
- Percentual de dominio (0-100) — exibido como barra de progresso

### Experiencias

Historico de experiencias profissionais ou academicas:
- Imagem/logo da empresa
- Titulo (cargo + empresa)
- Descricao das atividades
- Telefone (opcional)
- E-mail (opcional)

### Depoimentos (Feedback)

Depoimentos de clientes ou colegas:
- Nome da pessoa
- Cargo/posicao
- Texto do depoimento

### Blog

**Categorias de Blog:**
- Crie categorias antes de adicionar posts
- Cada categoria tem nome e slug

**Posts:**
- Imagem de capa
- Titulo
- Categoria
- Conteudo HTML completo

**Navegacao entre posts:**
A pagina de detalhes do blog exibe automaticamente links para o post anterior e o proximo.

---

## Configuracoes do Rodape

### Redes Sociais
Para cada rede social, configure:
- **Icone** — Classe CSS do icone (ex: `fab fa-github`, `fab fa-linkedin`)
- **URL** — Link para o perfil

### Informacoes do Rodape
Texto de apresentacao que aparece na coluna principal do rodape.

### Contato no Rodape
Pares de icone + texto:
- Ex: icone de telefone + numero
- Ex: icone de e-mail + endereco

### Links Uteis e Links de Ajuda
Listas de links em colunas separadas no rodape.

---

## Configuracoes Gerais

### Configuracoes Gerais (`/admin/general-setting`)
- **Logo** — Imagem do logotipo do site
- **Nome do Site** — Nome exibido em titulos e cabecalhos

### SEO (`/admin/seo-setting`)
- **Meta Title** — Titulo para mecanismos de busca
- **Meta Description** — Descricao para mecanismos de busca
- **Meta Keywords** — Palavras-chave separadas por virgula

---

## Upload de Imagens

Todos os campos de imagem seguem o mesmo comportamento:

1. Selecione um arquivo (JPEG, PNG, GIF, WebP recomendados)
2. Ao salvar, o arquivo e movido para `public/uploads/`
3. Ao editar um registro com imagem existente, a imagem antiga e **automaticamente deletada** e substituida pela nova

**Caminho de armazenamento:** `public/uploads/`

---

## Notificacoes Toast

Todas as acoes bem-sucedidas ou com erro exibem uma notificacao toast no canto da tela (via Yoeunes Toastr):

- **Verde** — Sucesso (criacao, atualizacao, exclusao)
- **Vermelho** — Erro
- **Amarelo** — Aviso

---

## Perfil do Administrador

Acesse `/profile` para:
- Atualizar nome e e-mail
- Alterar senha
- Excluir a conta (use com cautela — irreversivel)

---

## Perguntas Frequentes

**Como adicionar um novo usuario admin?**  
No momento, o sistema suporta apenas autenticacao via Laravel Breeze. Novos usuarios podem ser criados em `/register` (se habilitado) ou via Tinker:
```bash
php artisan tinker
App\Models\User::factory()->create(['email' => 'novo@admin.com', 'password' => bcrypt('senha')]);
```

**Como redefinir a senha pelo terminal?**
```bash
php artisan tinker
$user = App\Models\User::where('email', 'admin@domain.com')->first();
$user->password = bcrypt('nova_senha');
$user->save();
```

**Os uploads estao falhando, o que fazer?**
```bash
# Verificar permissoes
chmod -R 755 storage/
chmod -R 755 public/uploads/

# Recriar o link simbolico
php artisan storage:link
```

**Como limpar o cache do admin?**
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
```
