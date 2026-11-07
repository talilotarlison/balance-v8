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
| Dashboard | Indicadores históricos de entradas, saídas e agendados, gráfico de pizza, calendário, cinco lançamentos gerais recentes, saldo líquido por banco e consolidado, além de até seis lançamentos recentes em cada categoria (Entrada, Saida e Agendado). |
| Lançamentos | Inclusão, edição, visualização, exclusão com confirmação e listagem. Campos atuais do formulário: tipo, nome, valor, data, banco, categoria e descrição. |
| Isolamento por usuário | Inclusão grava `usuario_id` da sessão; listagem, busca, leitura, edição, exclusão e resumos do dashboard filtram pelo usuário autenticado. A migração SQL em bases existentes ainda precisa ser aplicada. Lançamentos antigos ficam sem proprietário e não são exibidos até associação manual validada. |
| Filtros | Na listagem há filtros combináveis por tipo (`Entrada`, `Saida`, `Agendado`) e banco individual, identificado pelo nome; também existe página de busca textual. |
| Bancos | Cadastro autenticado de nome, tipo, cor, descrição e data de criação; listagem em cartões com a cor selecionada. Há botão de exclusão somente com ícone de lixeira; a operação exige confirmação e é recusada se houver lançamentos associados. |
| Categorias | Cadastro/listagem por usuário; tipos Entrada, Saída e Agendado determinam cores padronizadas. Lançamentos agendados aceitam categoria própria. Botão de exclusão com confirmação; bloqueia quando a categoria já tem lançamentos. |
| Lembretes | Página no menu após Categorias, vinculada à tabela `links`; cadastro e listagem com ID, nome e data prevista, email/telefone/URL opcionais, status concluído/reabrir, estilo riscado para concluídos e exclusão por botão de lixeira. As ações são vinculadas ao usuário autenticado. |
| Interface | Layout Materialize responsivo, estilos centralizados em `assets/css/styles.css`, favicon SVG, ações visualmente diferenciadas, botões de exclusão dos cartões apenas com ícone e componentes em `pages/views/`. |
| Documentação | PRD, diagnóstico técnico, changelog e guias do banco, estado e tarefas. Há dois nomes de changelog (`docs/CHANGLOG.md` e `CHANGELOG.md` na raiz); o histórico de alterações desta implementação é mantido em `docs/CHANGLOG.md`. |

## Estrutura principal

- `index.php`, `loginActions.php`, `cadastro.php`, `cadrastroActions.php`: acesso e cadastro.
- `pages/`: dashboard, lançamentos, busca, bancos, categorias, tema e endpoints CRUD.
- `pages/views/`: trechos de apresentação reutilizados nas páginas.
- `database/conexao.php`: conexão `mysqli`; carregamento de ambiente/modelos em `database/model/`.
- `database/scripts/`: dumps legados, criação de bancos/categorias e migrações dos campos e proprietário do lançamento.
- `utils/`: calendário, gráficos, mensagens, validação, tema/modal e dados auxiliares.
- `assets/`: CSS, JS e imagem de favicon.
- `docs/`: PRD, diagnóstico, changelog, status, tarefas e documentação do esquema.

## Tecnologias e dependências

- PHP puro; `composer.json` declara PHP `>=7.4` e `ext-mysqli`, mas o código usa o tipo `mixed` e requer PHP 8.0 ou superior na prática.
- MySQL/MariaDB através de `mysqli`.
- Materialize 1.0.0 e Material Icons servidos por CDN; jQuery 3.2.1 em algumas páginas.
- Google Charts carregado por CDN no dashboard.
- Nenhum framework PHP/JS ou suíte de testes aparece declarada no `composer.json`/estrutura observada.

## Alterações funcionais recentes registradas

- Formulário de lançamento passou a receber data financeira, banco e categoria; edição e visualização contemplam esses campos.
- Dumps/migração SQL passaram a declarar banco, categoria e data do lançamento.
- Dashboard ganhou até seis itens recentes por categoria, saldo individual por banco e consolidado; a seção original de cinco lançamentos gerais permanece acima dos novos resumos.
- Listagem passou a combinar filtros por tipo de lançamento e banco individual, selecionado pelo nome e consultado pelo ID.
- Categorias passaram a aceitar lançamentos agendados. `categorias.sql` inclui o novo tipo; `categorias_agendado.sql` migra bancos existentes e recupera valores de tipo vazios oriundos de tentativas antigas em modo não estrito. Essa migração ainda precisa ser executada manualmente no banco ativo.
- Bancos e categorias ganharam exclusão por POST/CSRF, confirmação, verificação de propriedade e bloqueio transacional quando há lançamento associado; os botões exibem somente a lixeira.
- Criado o módulo de lembretes usando `links`, com conclusão reversível e migração `database/scripts/lembretes.sql`; a migração SQL deve ser aplicada manualmente e registros antigos sem dono permanecem sem atribuição automática.
- Adicionada exclusão de lembretes com confirmação, POST, CSRF e verificação do usuário proprietário.
- Adicionado `usuario_id` aos lançamentos: inclusão grava o dono autenticado e as leituras, alterações, exclusões, busca e consultas do dashboard são limitadas ao dono. A migração `database/scripts/lancamentos_usuario.sql` ainda deve ser aplicada manualmente em bases existentes; dados históricos ficam sem dono e não são atribuídos automaticamente.
- Regras visuais para botões, tema escuro, favicon e telas de acesso foram atualizadas.

Veja o histórico detalhado em `CHANGLOG.md`.

## Riscos e limitações conhecidos

1. **Migração de lançamentos:** o código exige `fluxocaixa.usuario_id`; aplique `lancamentos_usuario.sql` uma vez em bases existentes antes de usar a versão atualizada. Registros históricos sem proprietário não aparecem e exigem validação antes da associação manual. Os métodos antigos em `database/model/classDados.php`, `classBuscar.php` e `classDelete.php` são legados; não os use em novos fluxos.
2. **Integridade monetária:** `preco` é `FLOAT`, e as saídas são representadas por sinal negativo. Qualquer migração para `DECIMAL` deve atualizar inserção, edição, gráficos, cálculos e relatórios conjuntamente.
3. **Datas antigas:** `data` é texto com formatos legados. `data_lancamento` é uma coluna `DATE` anulável e não é preenchida para registros antigos sem migração de dados validada.
4. **Segurança:** verificar autorização por usuário nos endpoints, CSRF em todos os fluxos, escaping na saída e proteção de sessão. O diagnóstico técnico descreve achados e recomendações em mais detalhe.
5. **Scripts divergentes:** dumps antigos e atuais têm diferentes esquemas/objetivos; `fluxocaixa.sql` não deve ser seguido pela migração que adiciona os mesmos campos.
6. **README/licença:** o README agora descreve MySQL/MariaDB e a configuração vigente, mas registra a divergência de licença entre `composer.json` (GPL-3.0) e `LICENSE` (MIT), que ainda precisa ser resolvida.
7. **Migração de categorias:** o banco em execução pode continuar com o enum antigo e valores vazios até que `categorias_agendado.sql` seja executado; o código não aplica SQL automaticamente.
8. **Validação operacional:** não há evidência nesta documentação de execução dos scripts SQL, testes automatizados ou revisão final em navegador. A migração de proprietário está versionada, mas não aplicada ao banco ativo.

## Referências

- Requisitos: `PRD.md`.
- Levantamento de riscos e comparação com o PRD: `DIAGNOSTICO.md`.
- Modelo e scripts de dados: `DATABASE.MD`.
- Histórico de alterações: `CHANGLOG.md`.
- Pendências: `TASK.md`.
