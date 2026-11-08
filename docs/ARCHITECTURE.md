# Arquitetura do Projeto Balance

## Visão geral

O Balance é uma aplicação web de gestão financeira pessoal desenvolvida em PHP puro, sem framework, com foco em simplicidade, compatibilidade com infraestrutura local e manutenção incremental.

A estrutura foi organizada em camadas bem simples:

- Front-end: HTML, CSS, JavaScript e templates em `pages/views/`
- Backend: scripts PHP procedurais em raiz e em `pages/`
- Persistência: MySQL/MariaDB via `mysqli`
- Dados auxiliares: classes model em `database/model/` e scripts SQL em `database/scripts/`
- Utilitários: funções e helpers em `utils/`

## Objetivo do sistema

O projeto permite:

- cadastro e autenticação de usuários;
- registro de receitas, despesas e lançamentos agendados;
- organização por banco e categoria;
- listagem, busca, edição e exclusão de registros;
- acompanhamento financeiro por dashboard;
- lembretes e tema pessoal.

## Estrutura de diretórios

```text
balance-v8/
├── index.php
├── loginActions.php
├── cadastro.php
├── cadrastroActions.php
├── composer.json
├── README.md
├── AGENTS.md
├── assets/
│   ├── css/
│   ├── img/
│   └── js/
├── database/
│   ├── conexao.php
│   ├── model/
│   └── scripts/
├── docs/
│   ├── ARCHITECTURE.md
│   ├── DATABASE.MD
│   ├── DIAGNOSTICO.md
│   ├── PRD.md
│   ├── PROJECT_STATUS.md
│   └── TASKS.md
├── pages/
│   ├── views/
│   ├── adicionar.php
│   ├── atualizar.php
│   ├── bancos.php
│   ├── buscar.php
│   ├── categorias.php
│   ├── dashboard.php
│   ├── delete.php
│   ├── lancamentos.php
│   ├── lembretes.php
│   ├── tema.php
│   ├── update.php
│   └── login/
├── utils/
│   ├── btnVoltar.php
│   ├── calendario.php
│   ├── getHoras.php
│   ├── grafico.php
│   ├── mensagem.php
│   ├── modal.php
│   ├── precoNegativo.php
│   ├── updateController.php
│   └── validateDados.php
└── .env (local, não versionado)
```

## Padrão arquitetural

### 1. Camada de apresentação

Os arquivos em `pages/` e `pages/views/` são responsáveis pela renderização das telas e pelo mapeamento de ações do usuário.

Exemplos:

- `pages/dashboard.php`: dashboard financeiro
- `pages/lancamentos.php`: listagem e filtros
- `pages/bancos.php`: cadastro de bancos/carteiras
- `pages/categorias.php`: categorias por tipo
- `pages/lembretes.php`: lembretes do usuário

Os componentes de interface reutilizáveis ficam em `pages/views/`, como cabeçalhos, tabelas, modais e blocos do dashboard.

### 2. Camada de aplicação e regras de negócio

A lógica de processamento está espalhada em scripts PHP de fluxo, com ações de login, cadastro, atualização, busca e exclusão.

Principais pontos:

- autenticação e sessão em `loginActions.php` e arquivos da pasta `pages/login/`
- cadastro em `cadastro.php` e `cadrastroActions.php`
- inclusão/edição de lançamentos em páginas e helpers relacionados
- filtros e consulta por usuário em páginas de listagem/consulta

### 3. Camada de acesso a dados

A conexão com o banco é centralizada em `database/conexao.php`.

A camada de modelo em `database/model/` concentra classes e utilitários para:

- carregamento de ambiente
- leitura de dados
- busca
- deletar registros
- recursos específicos de usuário, tema e credenciais

Essa camada é legada e foi evoluída ao longo do projeto; o código atual ainda preserva padrões antigos, mas continua funcional em ambiente local.

### 4. Camada de persistência

O banco é MySQL/MariaDB, acessado via `mysqli`.

Os scripts SQL em `database/scripts/` definem:

- criação de tabelas base;
- migrações de campos;
- vínculo de dados ao usuário autenticado;
- estrutura de categorias, bancos, lembretes e log do sistema.

## Fluxo principal de execução

### Login e autenticação

1. O usuário acessa a aplicação via `index.php`.
2. As credenciais são validadas em `loginActions.php`.
3. Em caso de sucesso, a sessão do usuário é criada.
4. A aplicação filtra consultas e ações pelo usuário autenticado.

### Cadastro de usuários

1. O usuário entra na página de cadastro.
2. Dados básicos são enviados para `cadrastroActions.php`.
3. O sistema valida os campos e grava os dados no banco.
4. A conta fica pronta para login.

### Lançamentos financeiros

1. Usuário acessa a tela de lançamentos.
2. Define tipo, nome, valor, data, banco, categoria e descrição.
3. O registro é persistido com vínculo ao usuário da sessão.
4. A listagem e o dashboard consultam somente os dados do usuário autenticado.

### Dashboard e relatórios

O dashboard reúne:

- saldo por banco;
- totais gerais;
- entradas e saídas;
- lançamentos recentes;
- categorias com movimento recente;
- gráfico de distribuição e calendário.

## Regras de negócio observadas

- O sistema é multusuário, mas funcionalmente isolado por conta.
- Consulta e ações sobre dados financeiros devem respeitar o `usuario_id` da sessão.
- O módulo de lembretes usa a tabela `links` e também exige vínculo ao usuário.
- O mecanismo de tema salva a preferência do usuário em perfil.
- Existem migrações históricas para campos como `usuario_id`, `banco_id`, `categoria_id` e `data_lancamento`.

## Benefícios da arquitetura atual

- Simplicidade de entendimento e manutenção.
- Fácil implantação em ambiente PHP local.
- Baixo acoplamento entre módulos do projeto.
- Compatibilidade com infraestrutura simples de hospedagem.
- Boa aderência ao padrão do código legado do projeto.

## Riscos e pontos de atenção

- A arquitetura procedural facilita mudança rápida, mas exige disciplina na organização e validação.
- Há uso de `FLOAT` para valores monetários, o que exige atenção para precisão.
- Registros legados com dados incompletos podem exigir migração manual.
- A segurança deve ser revisada em cada endpoint sensível, especialmente em autenticação, autorização e validação de entrada.
- O projeto ainda depende de migrações SQL manuais em instalações existentes.

## Diretrizes para manutenção

- Manter a separação por usuário em todos os acessos de dados.
- Reaproveitar helpers e classes existentes antes de criar novas abstrações.
- Evitar reescrita ampla sem necessidade.
- Atualizar documentação quando houver alteração de fluxo, banco ou configuração.
- Validar sintaxe e comportamento em ambiente local antes de publicar mudanças.

## Conclusão

A arquitetura atual do Balance é funcional, direta e adequada ao contexto de um sistema PHP puro em ambiente local. A evolução do projeto deve continuar priorizando compatibilidade, simplicidade operacional e segurança de dados, sem perder a identidade já construída pelo sistema.
