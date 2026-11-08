# AGENTS.md — Instruções Gerais do Projeto

## Visão geral

Este repositório contém o projeto Balance, uma aplicação web de controle financeiro pessoal em PHP puro, com banco MySQL/MariaDB e interface em HTML/CSS/JavaScript com Materialize.

- Linguagem principal: PHP 8.0+
- Banco de dados: MySQL/MariaDB via `mysqli`
- Estrutura: páginas server-side, módulos em `database/`, utilitários em `utils/` e assets front-end em `assets/`
- Objetivo: registrar receitas, despesas, lançamentos agendados, bancos, categorias, lembretes e relatórios por usuário

## Regras de trabalho

### 1) Manter a arquitetura atual

- Preserve o estilo procedural e a organização atual do projeto.
- Não introduza frameworks PHP/JS nem reescreva a aplicação para outra arquitetura sem necessidade.
- Prefira ajustes locais e compatíveis com o padrão já usado no projeto.
- Reaproveite utilitários e classes existentes antes de criar novos módulos semelhantes.

### 2) Compatibilidade e ambiente

- Considere o projeto compatível com PHP 8.0 ou superior.
- O código deve continuar funcionando em ambiente local com Apache + MySQL/MariaDB (ex.: XAMPP/WAMP).
- Evite usar recursos de versões muito recentes que quebrariam a compatibilidade do ambiente de desenvolvimento.
- O arquivo `.env` é local e não deve ser versionado; nunca commitar credenciais reais.

### 3) Segurança

- Sempre respeitar a separação por usuário autenticado (`usuario_id` ou equivalente).
- Não expor dados de outro usuário em listagens, consultas ou dashboards.
- Validar entradas recebidas por POST/GET antes de processar.
- Tratar dados de saída com escaping para evitar problemas de renderização e XSS.
- Reforçar autenticação e autorização em páginas e endpoints sensíveis.
- Antes de alterar fluxos de login, cadastro, lembretes, lançamentos ou bancos, verificar impacto na autorização e na integridade dos dados.

### 4) Banco de dados e migrações

- O projeto possui scripts SQL em `database/scripts/` e migrações específicas para campos e ownership de registros.
- Não execute migrações em massa sem backup do banco atual.
- Antes de alterar o esquema, confira se o ambiente já contém colunas ou tabelas exigidas; evite duplicar alterações.
- Os scripts de migração devem ser aplicados uma única vez, somente quando necessário.
- Registros legados sem proprietário devem permanecer tratados com cuidado; não atribuir dados antigos em lote sem validação.

### 5) Código e manutenção

- Mantenha nomes de variáveis, funções e arquivos em português quando já forem usados no projeto.
- Preserve a convenção das páginas e dos módulos já existentes.
- Quando alterar comportamento de módulos cruciais (login, cadastro, dashboard, lançamentos, categorias, bancos, lembretes), revisar os impactos em telas e ações relacionadas.
- Atualize a documentação diretamente afetada quando a mudança alterar configuração, fluxo ou estrutura do banco.

### 6) Validação

- Para mudanças em PHP, prefira validar com syntax check (`php -l`) nos arquivos alterados.
- Quando possível, teste o fluxo em ambiente local com as páginas principais e verificar se a alteração não quebrou a navegação ou a persistência.
- Não há suíte automatizada robusta declarada no projeto, portanto a validação deve priorizar checagens de compatibilidade, sintaxe e comportamento manual local.

## Estrutura principal do projeto

- `index.php` — tela de login
- `loginActions.php` — autenticação
- `cadastro.php` e `cadrastroActions.php` — fluxo de cadastro
- `pages/` — páginas autenticadas e ações de CRUD
- `pages/views/` — componentes visuais reutilizáveis
- `database/conexao.php` — conexão com o banco
- `database/model/` — classes e acesso a dados
- `database/scripts/` — criação e migração do esquema SQL
- `utils/` — helpers e validadores
- `assets/` — CSS, JS e imagens
- `docs/` — documentação de produto, banco, status e tarefas

## Diretrizes para contribuições

- Faça alterações pequenas e focadas.
- Evite refatorações amplas sem justificação clara.
- Preserve comportamento existente em áreas não relacionadas.
- Registre no README, docs ou changelog qualquer mudança relevante no uso, setup ou estrutura do projeto.
- Documente alterações de banco e migração antes de aplicar mudanças em produção.

## Observações importantes

- O projeto usa `mysqli`; qualquer mudança para outra extensão ou biblioteca deve ser deliberada e compatível com o ambiente.
- O uso de `FLOAT` e datas textuais legado exige cuidado e revisão antes de migrações de integridade financeira.
- Em bases existentes, use migrações e validações manuais antes de assumir que o banco já está em um estado consistente.

## Resumo da política do agente

Ao trabalhar neste repositório, priorize:

1. compatibilidade com a arquitetura atual;
2. segurança e isolamento de dados por usuário;
3. mudanças pequenas e bem testadas;
4. manutenção da documentação relevante;
5. cuidado com banco de dados e migrações existentes.
