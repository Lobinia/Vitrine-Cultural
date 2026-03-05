# 🎨 Vitrine Cultural – Jardim Florianópolis

Plataforma web criada para apoiar artistas independentes da Igreja Evangélica Assembleia de Deus – Jardim Florianópolis 2, promovendo sua arte e conectando a comunidade local.

**Projeto de Extensão II** – Análise e Desenvolvimento de Sistemas (Unic Pantanal)

---

## 📸 Screenshots

### Página Inicial
![Página Inicial](images/screenshots/home.png)

### Login Administrativo
![Login Admin](images/screenshots/login.png)

### Dashboard
![Dashboard](images/screenshots/dashboard.png)

### Gerenciar Artistas
![Gerenciar Artistas](images/screenshots/gerenciar-artistas.png)

### Gerenciar Eventos
![Gerenciar Eventos](images/screenshots/gerenciar-eventos.png)

---

## ⚙️ Tecnologias

- **PHP** (back-end)
- **MySQL** (banco de dados)
- **HTML / CSS / JavaScript** (front-end)
- **Font Awesome** (ícones)
- **PDO** (camada de acesso ao banco de dados)
- **Google Fonts** (tipografia – IM Fell DW Pica)

---

## 🚀 Como Rodar

1. **Clone o repositório:**
   ```bash
   git clone https://github.com/seu-usuario/Vitrine-Cultural.git
   ```

2. **Importe o banco de dados:**
   Execute o arquivo `database.sql` no MySQL para criar o banco e as tabelas.

3. **Configure a conexão:**
   Edite o arquivo `config.php` com as credenciais do seu banco de dados:
   ```php
   $db_host = 'localhost';
   $db_name = 'vitrine_cultural';
   $db_user = 'root';
   $db_pass = '';
   ```

4. **Inicie um servidor PHP:**
   ```bash
   php -S localhost:8000
   ```

5. Acesse `http://localhost:8000` no navegador.

---

## 📁 Estrutura do Projeto

```
├── config.php                  # Configuração e conexão PDO com o banco
├── database.sql                # Script DDL/DML de criação do banco e dados iniciais
├── index.php                   # Página principal (site público)
├── processar_cadastro.php      # Processamento do formulário de cadastro público
├── script.js                   # Scripts do front-end (reservado para futuras validações)
├── style.css                   # Estilos globais do site público
├── images/                     # Imagens do projeto
│   └── screenshots/            # Screenshots da aplicação
└── admin/
    ├── index.php               # Dashboard administrativo (totais e resumo)
    ├── login.php               # Página de login com autenticação por sessão
    ├── logout.php              # Destruição de sessão e redirecionamento
    ├── gerenciar_artistas.php  # CRUD completo de artistas (com upload de imagem)
    ├── gerenciar_eventos.php   # CRUD completo de eventos
    └── upload/                 # Diretório de uploads de imagens dos artistas
```

---

## 🔑 Funcionalidades

- **Galeria de Artistas** – Exibição de artistas com foto, área artística e links sociais
- **Agenda Cultural** – Lista de eventos com data e descrição
- **Cadastro de Artistas** – Formulário público para novos artistas se cadastrarem
- **Painel Administrativo** – Gerenciamento de artistas e eventos (com login protegido)

---

## 🔄 Fluxo Completo da Aplicação

### 1. Banco de Dados (`database.sql`)

O ponto de partida é a criação do banco de dados. O script SQL:

1. Cria o banco `vitrine_cultural` (caso não exista).
2. Cria a tabela **`artistas`** com os campos:
   - `id` (PK, auto-incremento)
   - `nome` (VARCHAR 255, obrigatório)
   - `area` (VARCHAR 255, obrigatório – ex: Músico, Pintor)
   - `imagem` (VARCHAR 255, caminho da foto – pode ser nulo)
   - `link_instagram`, `link_youtube`, `link_portfolio` (VARCHAR 255, opcionais)
   - `aprovado` (TINYINT, padrão 1)
   - `data_cadastro` (TIMESTAMP automático)
3. Cria a tabela **`eventos`** com os campos:
   - `id` (PK, auto-incremento)
   - `titulo` (VARCHAR 255, obrigatório)
   - `data_evento` (DATE, obrigatório)
   - `descricao` (TEXT, obrigatório)
   - `data_cadastro` (TIMESTAMP automático)
4. Insere dados iniciais de exemplo (2 artistas e 2 eventos).

---

### 2. Configuração (`config.php`)

Arquivo central de conexão com o banco de dados:

1. Define as variáveis de conexão (`$db_host`, `$db_name`, `$db_user`, `$db_pass`).
2. Cria uma instância **PDO** com charset `utf8mb4`.
3. Configura o modo de erro como `ERRMODE_EXCEPTION` (lança exceções em caso de falha).
4. Define o modo de fetch padrão como `FETCH_ASSOC` (retorna arrays associativos).
5. Em caso de falha, exibe mensagem de erro e encerra a execução.

> **Todos os arquivos PHP** que acessam o banco fazem `require_once 'config.php'` (ou `'../config.php'` na pasta admin) para reutilizar a variável `$pdo`.

---

### 3. Site Público (`index.php`)

Página principal acessada pelo visitante. O fluxo é:

```
Usuário acessa http://localhost:8000
        │
        ▼
   index.php carrega
        │
        ├── require_once 'config.php'  →  conexão PDO disponível
        │
        ├── SELECT * FROM artistas ORDER BY data_cadastro DESC LIMIT 10
        │       → busca os 10 artistas mais recentes
        │
        ├── SELECT * FROM eventos ORDER BY data_evento ASC
        │       → busca todos os eventos ordenados por data
        │
        ▼
   Renderiza o HTML com 5 seções:
```

**Seções da página:**

| Seção | Âncora | Descrição |
|-------|--------|-----------|
| **Sobre o Projeto** | `#sobre` | Texto descritivo sobre a plataforma |
| **Galeria de Artistas** | `#artistas` | Cards com foto, nome, área e botões de redes sociais (Instagram, YouTube, Portfólio) |
| **Agenda Cultural** | `#eventos` | Lista de eventos com título, data (dd/mm/yyyy) e descrição |
| **Cadastre-se como Artista** | `#participe` | Formulário público de cadastro (nome, área, foto, link) |
| **Contato** | `#contato` | Email e créditos no footer |

**Navegação do header:**
- Links âncora para cada seção (`#artistas`, `#eventos`, `#contato`, `#participe`)
- Botão **Admin** → redireciona para `admin/login.php`

**Feedback ao usuário:**
- Se a URL contém `?status=sucesso` → exibe mensagem verde de confirmação
- Se a URL contém `?status=erro` → exibe mensagem vermelha de erro

---

### 4. Cadastro Público de Artista (`processar_cadastro.php`)

Quando o visitante preenche o formulário em `#participe` e clica "Enviar":

```
Formulário (POST + multipart/form-data)
        │
        ▼
processar_cadastro.php
        │
        ├── require_once 'config.php'
        │
        ├── Verifica se o diretório admin/upload/ existe
        │       └── Se não existe → cria com mkdir()
        │
        ├── Valida método POST
        │
        ├── Coleta campos: nome, area, link_portfolio
        │
        ├── Upload de imagem (opcional):
        │       ├── Verifica se arquivo foi enviado sem erro
        │       ├── Valida extensão (jpg, jpeg, png, gif, webp)
        │       ├── Gera nome único com uniqid()
        │       └── Move arquivo para admin/upload/
        │
        ├── Valida campos obrigatórios (nome e area não vazios)
        │
        ├── INSERT INTO artistas (nome, area, imagem, link_portfolio)
        │
        └── Redireciona:
                ├── Sucesso → index.php?status=sucesso#participe
                └── Erro    → index.php?status=erro#participe
```

> **Nota:** O formulário público coleta apenas `link_portfolio`. Os campos `link_instagram` e `link_youtube` ficam como `NULL` e podem ser preenchidos pelo admin depois.

---

### 5. Login Administrativo (`admin/login.php`)

Tela de autenticação para acessar o painel:

```
Usuário acessa admin/login.php
        │
        ├── session_start()
        │
        ├── Se já está logado (sessão ativa)
        │       └── Redireciona para admin/index.php
        │
        ├── Se é POST (formulário enviado):
        │       ├── Coleta usuario e senha
        │       ├── Verifica credenciais:
        │       │       Usuário: admin
        │       │       Senha:   Admin@123#
        │       ├── Sucesso → $_SESSION['admin_logged_in'] = true
        │       │              Redireciona para admin/index.php
        │       └── Falha   → Exibe "Usuário ou senha incorretos."
        │
        └── Renderiza formulário de login
                └── Link "← Voltar ao site" → ../index.php
```

---

### 6. Dashboard Administrativo (`admin/index.php`)

Página inicial do painel admin (protegida por sessão):

```
admin/index.php
        │
        ├── session_start()
        ├── Verifica $_SESSION['admin_logged_in'] === true
        │       └── Se não → redireciona para login.php
        │
        ├── require_once '../config.php'
        │
        ├── SELECT COUNT(*) FROM artistas  →  $totalArtistas
        ├── SELECT COUNT(*) FROM eventos   →  $totalEventos
        │
        └── Renderiza dashboard com:
                ├── Sidebar com navegação (menu lateral fixo)
                ├── Card "Total de Artistas" com contagem
                ├── Card "Eventos Agendados" com contagem
                └── Mensagem de boas-vindas
```

**Menu lateral (sidebar) – presente em todas as páginas admin:**

| Link | Destino |
|------|---------|
| Dashboard | `admin/index.php` |
| Gerenciar Artistas | `admin/gerenciar_artistas.php` |
| Gerenciar Eventos | `admin/gerenciar_eventos.php` |
| Acessar Site Público ↗ | `../index.php` (nova aba) |
| Sair do Sistema | `admin/logout.php` |

---

### 7. Gerenciar Artistas (`admin/gerenciar_artistas.php`)

CRUD completo de artistas (protegido por sessão):

```
admin/gerenciar_artistas.php
        │
        ├── Proteção de sessão (igual ao dashboard)
        ├── require_once '../config.php'
        │
        ├── Se POST com acao = "adicionar":
        │       ├── Coleta: nome, area, link_instagram, link_youtube, link_portfolio
        │       ├── Upload de imagem:
        │       │       ├── Valida extensão (jpg, jpeg, png, gif, webp)
        │       │       ├── Gera nome único com uniqid()
        │       │       ├── Move para admin/upload/
        │       │       └── Salva caminho como "admin/upload/nome.ext"
        │       └── INSERT INTO artistas (nome, area, imagem, link_instagram, link_youtube, link_portfolio)
        │
        ├── Se POST com acao = "excluir":
        │       ├── Busca artista pelo ID (SELECT imagem)
        │       ├── Se tem imagem → deleta o arquivo físico com unlink()
        │       └── DELETE FROM artistas WHERE id = ?
        │
        ├── SELECT * FROM artistas ORDER BY data_cadastro DESC
        │
        └── Renderiza:
                ├── Mensagem de feedback (sucesso/erro)
                ├── Formulário de adição (todos os campos + upload)
                └── Tabela de artistas cadastrados (foto miniatura, nome, área, botão excluir)
```

> **Diferença do cadastro público:** O admin pode preencher `link_instagram`, `link_youtube` e `link_portfolio` individualmente. O cadastro público oferece apenas um campo genérico de link.

---

### 8. Gerenciar Eventos (`admin/gerenciar_eventos.php`)

CRUD completo de eventos (protegido por sessão):

```
admin/gerenciar_eventos.php
        │
        ├── Proteção de sessão (igual ao dashboard)
        ├── require_once '../config.php'
        │
        ├── Se POST com acao = "adicionar":
        │       ├── Coleta: titulo, data_evento, descricao
        │       └── INSERT INTO eventos (titulo, data_evento, descricao)
        │
        ├── Se POST com acao = "excluir":
        │       └── DELETE FROM eventos WHERE id = ?
        │
        ├── SELECT * FROM eventos ORDER BY data_evento DESC
        │
        └── Renderiza:
                ├── Mensagem de feedback (sucesso/erro)
                ├── Formulário de adição (título, data, descrição via textarea)
                └── Tabela de eventos (data formatada dd/mm/yyyy, título, descrição, botão excluir)
```

---

### 9. Logout (`admin/logout.php`)

```
admin/logout.php
        │
        ├── session_start()
        ├── session_destroy()  →  destrói todos os dados da sessão
        └── header("Location: login.php")  →  redireciona para o login
```

---

## 🗺️ Mapa de Navegação Completo

```
┌─────────────────────────────────────────────────────────────┐
│                     SITE PÚBLICO                            │
│                                                             │
│   index.php                                                 │
│   ├── #sobre       → Sobre o Projeto                       │
│   ├── #artistas    → Galeria de Artistas (cards do BD)      │
│   ├── #eventos     → Agenda Cultural (lista do BD)          │
│   ├── #participe   → Formulário de Cadastro                 │
│   │       └── POST → processar_cadastro.php                 │
│   │                       └── Redireciona → index.php       │
│   ├── #contato     → Email e créditos                       │
│   └── Admin (btn)  → admin/login.php                        │
│                                                             │
└─────────────────────────────────────────────────────────────┘
                          │
                          ▼
┌─────────────────────────────────────────────────────────────┐
│                   PAINEL ADMINISTRATIVO                      │
│                                                             │
│   admin/login.php                                           │
│   └── Autenticação → admin/index.php (Dashboard)            │
│                         │                                   │
│                         ├── admin/gerenciar_artistas.php     │
│                         │       ├── Adicionar artista        │
│                         │       └── Excluir artista          │
│                         │                                   │
│                         ├── admin/gerenciar_eventos.php      │
│                         │       ├── Adicionar evento         │
│                         │       └── Excluir evento           │
│                         │                                   │
│                         ├── Site Público ↗ (nova aba)        │
│                         │                                   │
│                         └── admin/logout.php → login.php     │
│                                                             │
└─────────────────────────────────────────────────────────────┘
```

---

## 🔐 Segurança

| Recurso | Implementação |
|---------|---------------|
| **Autenticação** | Sessão PHP (`$_SESSION['admin_logged_in']`) |
| **Proteção de rotas** | Verificação de sessão no topo de cada página admin |
| **SQL Injection** | Prepared Statements (PDO com `?` placeholders) |
| **XSS** | `htmlspecialchars()` em todas as saídas de dados do BD |
| **Upload seguro** | Validação de extensão + nome único com `uniqid()` |

---

## 📊 Modelo de Dados

```
┌──────────────────────────┐       ┌──────────────────────────┐
│        artistas          │       │         eventos          │
├──────────────────────────┤       ├──────────────────────────┤
│ id (PK, AUTO_INCREMENT)  │       │ id (PK, AUTO_INCREMENT)  │
│ nome (VARCHAR 255)       │       │ titulo (VARCHAR 255)     │
│ area (VARCHAR 255)       │       │ data_evento (DATE)       │
│ imagem (VARCHAR 255)     │       │ descricao (TEXT)         │
│ link_instagram (VARCHAR)  │       │ data_cadastro (TIMESTAMP)│
│ link_youtube (VARCHAR)    │       └──────────────────────────┘
│ link_portfolio (VARCHAR)  │
│ aprovado (TINYINT, def 1)│
│ data_cadastro (TIMESTAMP)│
└──────────────────────────┘
```

---

## 👩‍💻 Autora

Desenvolvido por **Maya** – Aluna de ADS – Unic Pantanal