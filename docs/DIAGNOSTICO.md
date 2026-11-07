# Diagnóstico técnico do Balance

**Data da análise:** 7 de novembro de 2026  
**Escopo:** leitura integral de `PRD.md`, estrutura versionada, código PHP/JS/CSS, configuração de dependências e scripts SQL. Nenhum arquivo de aplicação foi alterado. Este documento registra o estado observado no repositório; não foi feita conexão ao banco em execução nem teste funcional.

## 1. Resumo executivo

O Balance é uma aplicação procedural em PHP puro, com páginas PHP servidas diretamente, `mysqli` e MariaDB/MySQL. A interface usa Materialize CSS/JS 1.0.0, Material Icons, jQuery 3.2.1 (carregado em algumas telas) e Google Charts por CDN. O `.env` seleciona o banco `financeiro`; suas credenciais não são reproduzidas aqui.

Já existem login/cadastro, preferência de tema por usuário, dashboard agregado, lançamentos de entrada/saída/agendados, listagem, busca textual, visualização, edição, exclusão com confirmação e mensagem de retorno. O alcance dos registros, porém, não é isolado por usuário: `fluxocaixa` não tem coluna de proprietário e suas operações não filtram por sessão. Isso torna a integridade e privacidade dos dados o risco prioritário antes de evoluir recursos.

O PRD propõe uma modernização incremental, preservação das tecnologias e proteção de dados. A arquitetura permite evoluir sem framework, mas os scripts SQL, o README e o código não descrevem um único estado coerente. Antes de alteração de banco será necessário identificar a instalação real e planejar backup e compatibilidade; os dumps existentes são históricos e não provam o esquema atualmente implantado.

## 2. Estrutura observada

| Caminho | Papel observado |
|---|---|
| `index.php`, `loginActions.php`, `cadastro.php`, `cadrastroActions.php` | Entrada, autenticação e cadastro. O nome `cadrastroActions.php` contém grafia inconsistente. |
| `pages/` | Páginas diretas de dashboard, lançamentos, busca, operações CRUD, tema e logout. |
| `pages/views/` | Trechos de apresentação compartilhados: cabeçalho, dashboard, tabela, modais, formulários, rodapé e visualização. |
| `database/conexao.php` | Inicializa `mysqli`, charset e credenciais carregadas de `.env`. |
| `database/model/` | Classes que montam SQL, carregador de ambiente e modelos simples de sessão/dados. |
| `database/scripts/` | Cinco scripts SQL, incluindo dumps de `financeiro`, `cadastros` e `cadastro`. |
| `utils/` | Validação/escape, data, sinal de despesa, mensagens, gráficos, calendário, modal e controle de edição. |
| `assets/css/styles.css`, `assets/js/app.js` | Estilos próprios e inicialização Materialize. O JS local não aparece incluído nas páginas vistas. |
| `docs/` | PRD e changelog com grafia divergente (`CHANGLOG.md`/`CHANGELOG.md`). |

Não há `src/`, testes, migrações, `.htaccess`, `package.json`, lockfile Composer, diretório `vendor` ou diretório `node_modules` no inventário versionado. `composer.json` define autoload PSR-4 para `src/`, inexistente, e não lista bibliotecas externas.

## 3. Tecnologias e bibliotecas

- **Backend:** PHP puro; `composer.json` declara PHP `>=7.4` e `ext-mysqli`. O código contém `mixed` (compatível desde PHP 8), então a restrição declarada não representa necessariamente a versão mínima real. Dumps foram gerados com PHP 8.2.4, dado histórico, não confirmação do runtime.
- **Banco/conector:** MySQL/MariaDB através de `mysqli` orientado a objetos na conexão e chamadas procedurais nas páginas.
- **Frontend:** HTML, CSS e JavaScript sem framework; Materialize CSS/JS 1.0.0 via CDN; Material Icons via Google Fonts; jQuery 3.2.1 via CDN em algumas páginas; Google Charts `current` para gráfico circular.
- **Configuração:** `.env` próprio, ignorado pelo Git; `classLoadEnv.php` o carrega manualmente. Nenhuma biblioteca de exportação, testes ou migração está declarada.
- **Localização:** interface em português, valores formatados em BRL na listagem/dashboard.

## 4. Banco de dados e modelo

O nome efetivamente configurado no `.env` é `financeiro`. A conexão seleciona host, usuário, senha e banco por `DB_HOST`, `DB_USER`, `DB_PASS`, `DB_NAME` e define conexão `utf8mb4`. Isso identifica a configuração local, mas não comprova que o banco está disponível nem que seu conteúdo corresponde a algum dump.

### Tabelas relevantes inferidas pelos scripts

| Tabela | Colunas visíveis | Uso/limitações |
|---|---|---|
| `fluxocaixa` | `id`, `tipo`, `nome`, `preco FLOAT`, `disc`, `data` | Lançamentos; sem `user_id`, categoria, moeda, status normalizado ou data nativa. `preco` guarda despesas negativas e entradas positivas. Data textual, com formatos divergentes nos exemplos. |
| `login` | `id`, `nome`, `email`, `senha`, `data`, `tema` | Usuário e tema. Dumps contêm hashes MD5 demonstrativos/antigos; implementação grava e compara MD5. |
| `links` | `id`, `nome`, `url`, `visto` | Presente nos dumps `financeiro`, mas não aparece no fluxo da aplicação analisado. |

As tabelas usam InnoDB e chaves primárias nos dumps relevantes, mas não há relacionamento entre `fluxocaixa` e `login`. O script `financeiro.sql` cria as três tabelas e `tema`; `database.sql` também define estrutura parecida, mas não mostra inserções e tem cabeçalhos incompletos. `fluxocaixa.sql` só contém a tabela de lançamentos. Outros scripts são bases didáticas de cursos/alunos e não correspondem ao domínio financeiro.

Há divergência de collation/charset: `fluxocaixa` aparece como `latin1`; `login`/`links` como `utf8`; conexão abre `utf8mb4`. `preco FLOAT` não é apropriado para precisão monetária exata e datas como `tinytext` impedem filtros/ordenação cronológica confiáveis sem normalização.

## 5. Funcionalidades existentes

### Usuários e navegação

- Cadastro de nome, email e senha com confirmação no navegador e comparação no backend.
- Login por email e senha e sessão com id, nome, email e tema.
- Logout e redirecionamentos por verificações de sessão em parte das páginas.
- Tema claro/escuro salvo em `login.tema` e alternado por link GET.
- Navegação Materialize com sidenav, links de Dashboard, Lançamentos e Buscar; modal de configuração e modal de novo lançamento.

### Movimentações e resumo

- Inclusão de `Entrada`, `Saida` e `Agendado`; saída recebe valor negativo e demais tipos valor absoluto.
- Listagem decrescente por `id`, com valores, descrição curta, data e ações de abrir/excluir/editar/ver.
- Busca parcial por nome, ordenada por id decrescente.
- Visualização de item, formulário de edição e exclusão por link GET com confirmação Materialize.
- Dashboard calcula somas de entradas, saídas, agendados e saldo, e compartilha valores com o gráfico.
- Gráfico de pizza com entradas, valor absoluto de saídas e agendados usando Google Charts.
- Toasts de feedback após operações, usando estado de sessão.

### Já existente em relação ao PRD

O PRD não deve ser tratado como lista de tudo que falta. Login/sessão, cadastro, CRUD básico, busca simples, confirmação de exclusão, quatro indicadores agregados, gráfico, tema claro/escuro, layout Materialize responsivo básico e documentação existem. Precisam de revisão de segurança, consistência, precisão, cobertura e UX antes de serem considerados conformes.

## 6. Identidade visual e CSS

A identidade atual é baseada no azul Material (`#2196f3`), fundo claro lavanda (`#e8eaf6`), cards verdes/amarelos/vermelhos/azul claro e modo escuro cinza (`#303131`). A interface usa Materialize, Material Icons, grid responsivo `s/m/l`, botões, cards, sidenav, modais e tabelas responsivas. O CSS próprio é curto e contém margem/padding globais, logo, sidenav, botão flutuante, cards métricos, largura da tabela e bolhas de status.

### Pontos para melhoria visual

- Não há tokens CSS centralizados; cores e raio aparecem repetidos em CSS, estilos inline e PHP.
- Tema é aplicado parcialmente por estilos inline, sem tokens/contraste global coerente.
- Formulários e cabeçalho usam diversos estilos inline; há textos e rótulos inconsistentes e erros de digitação.
- Cards têm altura e sombras fortes fixas; não há escala tipográfica/espacial definida.
- Rodapé com links placeholder e ano fixo; modais de configuração/adição acumulam responsabilidades.
- Ícones Material Icons já fornecem padrão útil, embora haja uso de ícones em botões sem rótulo e redundâncias na navegação.
- Responsividade básica vem do grid e `responsive-table`; não há evidência de teste em telas pequenas, e tabela ainda pode exigir rolagem.
- `styles.css` contém regras globais como `.container div { border-radius: 3px !important; }`, que podem afetar componentes de forma ampla.

## 7. Comparação com requisitos do PRD

| Requisito | Estado observado | Lacuna/decisão necessária |
|---|---|---|
| Preservar PHP puro, banco e bibliotecas | Compatível em intenção | Manter Materialize/Google Charts e PHP; confirmar versão instalada e evitar trocar sem justificativa. |
| Design System/tokens | Ausente | Centralizar variáveis mantendo azul, lavanda e modo escuro existentes. |
| Cards financeiros | Parcial | Indicadores existem, mas são totais históricos; não há filtro de período nem rótulo/ícone/indicador confiável. |
| Gráficos | Parcial | Só pizza agregado; sem série temporal, filtros, tooltip customizado, estado vazio/validação de valores. |
| Transações recentes | Parcial | Lista geral por id desc; sem componente separado de recentes ou link diferenciado. |
| CRUD de transações | Parcial | Inclusão/edição/exclusão existem; faltam validações robustas, CSRF, isolamento de proprietário e tratamento consistente de falhas. |
| Pesquisa/filtros/ordenação/paginação | Parcial | Busca apenas por descrição/nome; sem filtros combinados, período ou ordenação configurável/paginação. |
| Categorias | Ausente | Não há tabela/campo/CRUD de categorias. Precisa de desenho de migração e preservação. |
| Relatórios/CSV | Ausente | Sem página de relatório ou exportação. |
| Recorrência/orçamentos/metas | Ausente | São evoluções posteriores, requerem modelo de domínio e processos seguros contra duplicidade. |
| Backup/restauração | Ausente na funcionalidade | Há link “Backup” vazio; planejar cópia/validação antes de qualquer migração. |
| Responsividade/UX | Parcial | Grid e componentes Materialize; feedback básico; faltam estados vazios melhores, prevenção de duplo envio, carregamento, navegação acessível e validação móvel. |
| Segurança | Não conforme em pontos críticos | SQL concatenado, MD5, ausência de CSRF, escape de saída inconsistente e falta de autorização por proprietário. |
| Organização | Parcial | Views/utils existem, mas modelos montam SQL por concatenação; lógica de consulta/cálculo fica na apresentação. |

## 8. Inconsistências, riscos e evidências

### Críticos antes de evoluir dados

1. **Isolamento entre usuários:** `fluxocaixa` não tem dono e todas as consultas/listagens/resumos/busca/alteração/exclusão operam globalmente. Qualquer sessão pode potencialmente ver ou alterar dados de outros usuários.
2. **SQL concatenado:** classes `Insert`, `Update`, `Delete`, `Buscar`, `SelectUser`, `TemaUser` e `SelectId` montam strings SQL. Há escapes em alguns chamadores, mas abordagem inconsistente e não substitui prepared statements.
3. **Senha MD5:** cadastro/login usam `md5()`, sem hash moderno e sem fluxo de migração/re-hash visível.
4. **Autorização irregular:** `pages/tema.php`, `pages/update.php` e `pages/delete.php` não fazem checagem de sessão equivalente à tela; operações destrutivas/alterações usam GET/POST sem token CSRF e não confirmam propriedade do registro.
5. **Saída não escapada:** nomes, descrições, dados de sessão e feedback são impressos diretamente em HTML. `htmlspecialchars()` em entrada não é escapamento contextual e armazenamento de HTML escapado altera os dados.
6. **Precisão/semântica financeira:** `FLOAT`, sinal implícito, tipo `Agendado` misturado nos totais e datas textuais causam risco de totais e relatórios incorretos. A data não é atualizada na edição (campo enviado difere de `data`).

### Inconsistências operacionais/documentais

- README diz SQLite e menciona `phpliteadmin.config.php`/`includes`, incompatíveis com o código atual e ausentes da árvore.
- `composer.json` não tem dependências, declara autoload PSR-4 para diretório ausente e a versão PHP `>=7.4` conflita com uso de `mixed` em função.
- Existem dumps com bancos e esquemas diferentes; `database/scripts/cadastro.sql` e `cadastros.sql` são conteúdo didático não relacionado ao app.
- A listagem usa o nome de tabela de lançamentos global; dumps não incluem coluna para usuário nem categoria.
- `pages/atualizar.php` usa caminho de CSS relativo que pode falhar em rota sob `/pages`; comentário/redirect em endpoints variam e alguns não interrompem execução depois de redirecionar.
- `utils/getHoras.php` mistura `date_default_timezone_set` e construtor `DateTime` com identificador de timezone como data em dois métodos distintos; formatos persistidos variam.
- `utils/grafico.php` injeta valores de sessão em JavaScript sem validação/normalização; dashboard não limpa sessões de métricas se resultado vazio e calcula percentuais de forma pouco informativa.
- `utils/mensagem.php` injeta sessão dentro de HTML JavaScript, sem escaping contextual.
- `pages/views/modal.php` contém campos repetidos de id em páginas diferentes e perfil que apresenta horário atual como “Último Login”.
- Menu inclui links vazios/placeholder, “Backup” sem implementação e rodapé com conteúdo genérico.
- `app.js` repete parte de inicialização existente em `utils/modal.php`; o arquivo não apareceu incluído nas páginas analisadas. Modal/sidenav são inicializados em múltiplos pontos.
- `pages/views/table.php` imprime modal dentro de `<tbody>` e trunca nomes/descrições acrescentando reticências mesmo quando curtos.

## 9. Componentes prioritários para melhoria

1. **Camada de acesso a dados e autorização:** centralizar prepared statements e associar cada movimento ao usuário antes de ampliar qualquer funcionalidade.
2. **Autenticação/sessão:** hash de senha seguro com migração gradual do legado; validação de entrada; regeneração e encerramento de sessão; feedback sem saída antes de redirect.
3. **Modelo financeiro:** decidir representação de valor, tipo, categoria e datas; converter apenas com plano de backup, mapeamento e reversibilidade.
4. **Formulários e CRUD:** validação backend, mensagens consistentes, CSRF, evitar duplicidade, atualizar resumo após operação e testar edição/exclusão.
5. **Dashboard e consultas:** agregações no banco com filtro por período e tratamento de agendados separado; remover percentuais sem referência significativa.
6. **Tabela e busca:** consultas filtráveis, ordenação segura por lista permitida, paginação e estados vazios; escapar dados somente na saída.
7. **Design compartilhado:** cabeçalho, navegação, modal, cards e campos com tokens CSS; reduzir estilos inline sem refazer a identidade.
8. **Tema:** dois temas com variáveis e preferência persistida usando operação autenticada e método apropriado.

## 10. Plano recomendado por fases

### Fase 1 — Confirmar ambiente e proteger dados

- Confirmar PHP/MariaDB ativos, banco selecionado e esquema real sem expor segredos.
- Produzir backup verificável e registrar procedimento de restauração antes de qualquer alteração de esquema.
- Definir estratégia de propriedade de lançamentos e compatibilidade dos registros legados (não atribuir silenciosamente dados a um usuário).
- Priorizar autorização por registro, prepared statements, CSRF e armazenamento/validação seguros.

### Fase 2 — Estabilizar operações e cálculos

- Corrigir validação servidor, escaping de saída e respostas/erros.
- Definir semântica para `Agendado`, convenção de sinal, valor monetário e data.
- Ajustar dashboard e CRUD com cobertura de filtros e cenários sem dados.

### Fase 3 — Design System e UX

- Introduzir tokens com paleta atual, escalas tipográfica/espacial, estados de foco/erro/sucesso e tema claro/escuro.
- Harmonizar navegação, cards, formulários, tabelas, modais e mobile.

### Fase 4 — Busca, filtros e relatórios

- Combinar pesquisa, tipo, período e categoria conforme modelo acordado; paginação e ordenação; CSV filtrado.
- Melhorar gráficos com períodos e estados sem dados usando biblioteca existente.

### Fase 5 — Categorias e recursos adicionais

- Categorias primeiro; depois orçamento, metas e recorrência mediante modelo, regras contra duplicidade e migrações planejadas.
- Backup/exportação operacional; restauração apenas com validação e confirmação.

### Fase 6 — Validação

- Testes de regressão de login, isolamento, CRUD e cálculos; inspeção responsiva e de console/logs; documentação e verificação de integridade do backup.

## 11. Limites e próximos passos

- A análise não executou PHP, não abriu navegador, não consultou MariaDB e não aplicou testes. Afirmações sobre o banco estão limitadas ao nome configurado e aos arquivos SQL versionados.
- Os dados de exemplo nos dumps podem conter registros antigos/demonstrativos; não foram tratados como inventário do banco real.
- Esta etapa não altera nenhum arquivo de aplicação. O documento é a entrega documental solicitada em `docs/`.
- Antes de uma fase com mudanças, confirmar banco/schema real e estratégia de backup, conforme a regra de segurança do próprio PRD.

## 12. Arquivos de referência lidos

`PRD.md`; `README.md`; `CHANGELOG.md`; `composer.json`; `.gitignore`; arquivos PHP em raiz, `pages/`, `pages/views/`, `database/`, `database/model/` e `utils/`; `assets/css/styles.css`; `assets/js/app.js`; scripts em `database/scripts/`. O `.env` foi consultado apenas para identificar o nome do banco; valores secretos não foram incluídos neste relatório.
