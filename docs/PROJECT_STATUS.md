# Estado atual do projeto Balance

**Atualizado em:** 7 de novembro de 2026  
**Base:** leitura dos arquivos versionados. O banco ativo e os fluxos em navegador não foram verificados nesta atualização documental.

## Resumo

Balance é um sistema de controle financeiro pessoal em PHP procedural, com páginas renderizadas no servidor e persistência MySQL/MariaDB via `mysqli`. A interface utiliza Materialize CSS/JS, Material Icons, JavaScript/CSS próprios e Google Charts. O código foi evoluído incrementalmente: além de login, dashboard e CRUD de lançamentos, há cadastro de bancos e categorias, datas de lançamento, tema escuro, filtros e saldos por banco.

## Funcionalidades observadas no código

| Área | Estado observado |
|---|---|
| Acesso | Cadastro e login de usuário, logout e sessões. O mecanismo de senha deve ser revisado contra os hashes antigos; ver riscos abaixo. |
| Tema | Alternância claro/escuro com preferência armazenada no perfil e estilos nos componentes principais. |
| Dashboard | Indicadores históricos de entradas, saídas e agendados, gráfico de pizza, calendário, cinco lançamentos recentes, saldo por banco e saldo consolidado líquido. |
| Lançamentos | Inclusão, edição, visualização, exclusão com confirmação e listagem. Campos atuais do formulário: tipo, nome, valor, data, banco, categoria e descrição. |
| Filtros | Na listagem há filtro por tipo (`Entrada`, `Saida`, `Agendado`) e tipo de banco; também existe página de busca textual. |
| Bancos | Cadastro autenticado de nome, tipo, cor, descrição e data de criação; listagem em cartões com a cor selecionada. |
| Categorias | Cadastro/listagem por usuário; tipo Entrada/Saída determina cor padronizada. |
| Interface | Layout Materialize responsivo, estilos centralizados em `assets/css/styles.css`, favicon SVG, ações visualmente diferenciadas e componentes em `pages/views/`. |
| Documentação | PRD, diagnóstico técnico, changelog e guias do banco, estado e tarefas. Há dois nomes de changelog (`CHANGLOG.md` e `CHANGELOG.md` na raiz); o arquivo atualizado pela aplicação é `docs/CHANGLOG.md`. |

## Estrutura principal

- `index.php`, `loginActions.php`, `cadastro.php`, `cadrastroActions.php`: acesso e cadastro.
- `pages/`: dashboard, lançamentos, busca, bancos, categorias, tema e endpoints CRUD.
- `pages/views/`: trechos de apresentação reutilizados nas páginas.
- `database/conexao.php`: conexão `mysqli`; carregamento de ambiente/modelos em `database/model/`.
- `database/scripts/`: dumps legados, criação de bancos/categorias e migração dos campos do lançamento.
- `utils/`: calendário, gráficos, mensagens, validação, tema/modal e dados auxiliares.
- `assets/`: CSS, JS e imagem de favicon.
- `docs/`: PRD, diagnóstico, changelog, status, tarefas e documentação do esquema.

## Tecnologias e dependências

- PHP puro; `composer.json` declara PHP `>=7.4` e `ext-mysqli`.
- MySQL/MariaDB através de `mysqli`.
- Materialize 1.0.0 e Material Icons servidos por CDN; jQuery 3.2.1 em algumas páginas.
- Google Charts carregado por CDN no dashboard.
- Nenhum framework PHP/JS ou suíte de testes aparece declarada no `composer.json`/estrutura observada.

## Alterações funcionais recentes registradas

- Formulário de lançamento passou a receber data financeira, banco e categoria; edição e visualização contemplam esses campos.
- Dumps/migração SQL passaram a declarar banco, categoria e data do lançamento.
- Dashboard ganhou até seis itens recentes por categoria, saldo individual por banco e consolidado; a seção original de cinco lançamentos gerais permanece acima dos novos resumos.
- Listagem passou a combinar filtros por tipo de lançamento e tipo de banco.
- Regras visuais para botões, tema escuro, favicon e telas de acesso foram atualizadas.

Veja o histórico detalhado em `CHANGLOG.md`.

## Riscos e limitações conhecidos

1. **Isolamento de lançamentos:** `fluxocaixa` não tem `usuario_id`. A listagem e operações antigas podem consultar por ID ou trazer o conjunto de lançamentos sem restringir a sessão. Associação com bancos/categorias não substitui uma regra de propriedade completa.
2. **Integridade monetária:** `preco` é `FLOAT`, e as saídas são representadas por sinal negativo. Qualquer migração para `DECIMAL` deve atualizar inserção, edição, gráficos, cálculos e relatórios conjuntamente.
3. **Datas antigas:** `data` é texto com formatos legados. `data_lancamento` é uma coluna `DATE` anulável e não é preenchida para registros antigos sem migração de dados validada.
4. **Segurança:** verificar autorização por usuário nos endpoints, CSRF em todos os fluxos, escaping na saída e proteção de sessão. O diagnóstico técnico descreve achados e recomendações em mais detalhe.
5. **Scripts divergentes:** dumps antigos e atuais têm diferentes esquemas/objetivos; `fluxocaixa.sql` não deve ser seguido pela migração que adiciona os mesmos campos.
6. **README:** ainda descreve SQLite e arquivos/estrutura que não correspondem ao conector `mysqli` observado. Atualizar o guia de instalação em tarefa própria.
7. **Validação operacional:** não há evidência nesta documentação de execução dos scripts SQL, testes automatizados ou revisão final em navegador.

## Referências

- Requisitos: `PRD.md`.
- Levantamento de riscos e comparação com o PRD: `DIAGNOSTICO.md`.
- Modelo e scripts de dados: `DATABASE.MD`.
- Histórico de alterações: `CHANGLOG.md`.
- Pendências: `TASK.md`.
