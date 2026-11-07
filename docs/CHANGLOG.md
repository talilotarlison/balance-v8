# Changelog — Balance

## 2026-11-07 — Lembretes, logs e dados individuais por usuário

- Criada a página de lembretes e adicionada a apresentação dos lembretes no dashboard.
- Criada uma tabela para registrar logs do sistema.
- O sistema suporta vários usuários, mantendo os dados de cada um individualizados.

## 2026-11-07 — Exclusão segura de bancos e categorias

- Adicionados botões de exclusão nos cartões de bancos e categorias, com confirmação antes do envio.
- Os botões agora exibem somente o ícone da lixeira; nomes acessíveis permanecem nos atributos `aria-label` e `title`.
- As operações usam POST e token CSRF, verificam que o item pertence ao usuário e executam em transação.
- A exclusão é bloqueada quando existe lançamento associado; a página informa o motivo e preserva o registro.
- Atualizados os estilos das ações nos cartões e a versão de cache do CSS das páginas.
- Não foram executados testes.

## 2026-11-07 — Categorias para lançamentos agendados

- Adicionado o tipo `Agendado` ao cadastro e à listagem de categorias, com cor âmbar e ícone de calendário.
- Formulários de inclusão e edição filtram e exigem categoria correspondente também para lançamentos agendados; o servidor valida tipo, usuário e categoria.
- Dashboard identifica categorias agendadas junto às categorias de entradas e saídas.
- Atualizado o script de criação de categorias e criado `database/scripts/categorias_agendado.sql` para atualizar tabelas existentes e recuperar tipos vazios gerados por tentativas anteriores em modo SQL não estrito. O script não foi executado.
- O formulário verifica se a coluna `tipo` aceita `Agendado` e orienta a aplicar a migração antes de salvar, evitando novas categorias com tipo vazio.

## 2026-11-07 — Atualização do README

- Mantidas as seções originais de funcionalidades, requisitos, instalação, estrutura, uso, contribuição e licença, atualizando-as para o ambiente real PHP + MySQL/MariaDB.
- Acrescentados configuração `.env`, ordem de importação dos scripts, funcionalidades atuais, links para documentação e limitações conhecidas.
- Registradas as divergências existentes entre a versão PHP declarada e a sintaxe usada, e entre `composer.json` e `LICENSE`.

## 2026-11-07 — Preenchimento da documentação do projeto

- Documentados o modelo de dados, relacionamentos conhecidos e ordem de uso dos scripts SQL em `DATABASE.MD`.
- Registrados o estado observado do código, tecnologias, funcionalidades recentes e riscos em `PROJECT_STATUS.md`.
- Criado backlog técnico alinhado ao PRD em `TASK.md`, separando implementação observada de validação pendente.
- Os documentos descrevem o repositório e os scripts; não representam inspeção do banco ativo nem execução de testes.

## 2026-11-07 — Filtros de lançamentos

- Adicionados filtros combináveis por tipo de lançamento (entrada, saída e agendado) e tipo de banco.
- Os tipos de banco disponíveis são carregados dos bancos cadastrados para o usuário; a filtragem usa consulta preparada.
- A tabela agora identifica o banco associado e mantém filtros aplicados via parâmetros GET.
- Corrigida a quantidade de tipos declarada no `bind_param` para corresponder aos cinco parâmetros da consulta.
- Não foram executados testes.

## 2026-11-07 — Resumo de saldos e categorias no dashboard

- Adicionado o saldo líquido individual de cada banco, com cores definidas no cadastro do banco.
- O saldo consolidado considera entradas menos saídas de todos os bancos do usuário; lançamentos agendados não entram no dinheiro disponível.
- O dashboard agora lista até seis lançamentos mais recentes em cada categoria do usuário, identificando banco, valor e data.
- Atualizados estilos responsivos e a versão de cache do CSS do dashboard.
- Mantida a seção original “Últimos lançamentos” (cinco registros gerais) acima dos novos resumos.
- Não foram executados testes; nenhum script SQL ou dado do banco foi alterado.

## 2026-11-07 — Edição e visualização completas dos lançamentos

- O formulário de edição agora oferece os mesmos campos do cadastro: tipo, nome, valor, data do lançamento, banco, categoria e descrição.
- Bancos e categorias são carregados da base e limitados ao usuário autenticado; categorias são filtradas conforme entrada ou saída.
- A edição valida os dados e as associações antes de salvar, mantendo a data original de criação do registro.
- A visualização exibe todos os campos disponíveis, incluindo data do lançamento, banco, categoria, data de criação e descrição.
- Registros legados sem data, banco ou categoria mostram esses dados como não informados/não associados; ao editar, os campos obrigatórios devem ser preenchidos.
- Atualizada a versão de cache do CSS nas telas relacionadas. Não foram executados testes nem aplicado SQL ao banco nesta alteração.

## 2026-11-07 — Atualização do dump fluxocaixa

- Atualizado `database/scripts/fluxocaixa.sql` com as colunas de banco, categoria e data do lançamento, além dos índices e chaves estrangeiras.
- Atualizados os `INSERT` de exemplo para explicitar os novos campos como `NULL`, preservando o significado dos dados legados sem inventar associações.
- Documentados os pré-requisitos para execução: tabelas `bancos` e `categorias` devem existir antes deste dump.
- Registrado que tabelas existentes devem usar a migração `lancamentos_campos.sql`; o dump completo já inclui as colunas e não deve ser seguido pela migração.

## 2026-11-07 — Banco, categoria e data no formulário de lançamento

- Acrescentados ao formulário: data do lançamento, banco e categoria, carregados das tabelas do usuário autenticado.
- Categorias disponíveis são filtradas conforme Entrada ou Saída; para Agendado, categoria fica opcional.
- O endpoint valida token CSRF, data, tipo e propriedade do banco/categoria e grava os vínculos com consulta preparada.
- A listagem principal e os resultados da busca passam a exibir a data de lançamento escolhida; registros legados usam a data antiga.
- Criado `database/scripts/lancamentos_campos.sql` para adicionar data de lançamento, banco e categoria com chaves estrangeiras. Execute depois de `bancos.sql` e `categorias.sql`; os campos legados ficam nulos.
- Incluídos atalhos para cadastrar bancos/categorias quando não houver opções e mantido o retorno à página atual após trocar o tema.

## 2026-11-07 — Cores fixas e modais nas páginas Bancos/Categorias

- Bloqueada a edição manual da cor nas categorias; o tipo Entrada mantém verde e Saída mantém vermelho, e a cor do cartão é atribuída no servidor.
- Incluído aviso sobre as cores padronizadas, com texto preto no tema escuro.
- Incluído o componente de configurações/perfil nas páginas Bancos e Categorias e inicializada a biblioteca de modais, corrigindo a abertura pelo nome do usuário.

## 2026-11-07 — Cadastro de categorias de entrada e saída

- Adicionado link “Categorias” ao cabeçalho e ao menu lateral, logo após “Bancos”.
- Criada página para cadastrar nome, tipo (Entrada/Saída) e cor; o tipo define a cor inicial verde ou vermelha, que pode ser personalizada.
- Categorias são listadas em grupos distintos de entradas e saídas, em cartões com a cor selecionada e data de inclusão.
- Os registros são vinculados ao usuário autenticado; formulário usa consultas preparadas e token CSRF.
- Criado `database/scripts/categorias.sql` com tabela, índice e vínculo ao usuário; o script aguarda execução manual no banco `financeiro`.

## 2026-11-07 — Renomeação da página de lançamentos

- Renomeado `pages/home.php` para `pages/lancamentos.php`.
- Atualizados links do cabeçalho e menu lateral, ações de retorno após cadastro/edição/exclusão, links do dashboard e lista de páginas permitidas para retorno após troca de tema.
- Atualizadas as referências nos documentos de changelog.
- Verificada a ausência de referências restantes a `home.php` no projeto.

## 2026-11-07 — Cadastro e listagem de bancos

- Adicionado link “Bancos” ao cabeçalho e ao menu lateral, após “Lançamentos”.
- Criada página autenticada para registrar nome, tipo de conta, cor escolhida e descrição; a data de criação é gravada automaticamente pelo MySQL.
- Adicionada listagem por usuário em cartões que usam a cor selecionada, com contraste de texto calculado para cada cor.
- Incluídos validação de dados, consultas preparadas e token CSRF no formulário.
- Criado `database/scripts/bancos.sql`, com tabela `bancos`, vínculo ao usuário e chave estrangeira para `login`.
- O script SQL foi criado para execução manual no banco `financeiro`; não foi executado nesta alteração.

## 2026-11-07 — Validação de senha no cadastro

- Cadastro exige senha com pelo menos oito caracteres, contendo letras e números; a regra aceita somente caracteres alfanuméricos.
- Aplicada validação tanto no formulário quanto no endpoint PHP, para não depender apenas do navegador.
- Exibida mensagem de erro quando a senha não atende ao formato ou quando a confirmação diverge.

## 2026-11-07 — Destaque suave para a mensagem de suporte

- Aplicada uma tonalidade vermelho suave à orientação de suporte para senha esquecida, com ajuste próprio para o tema escuro.

## 2026-11-07 — Orientação de suporte na tela de login

- Incluída no final do formulário a mensagem para usuários que esqueceram a senha entrarem em contato com o suporte.

## 2026-11-07 — Link de login na página de cadastro

- Adicionada a mensagem “Já possui cadastro? Faça login” abaixo das ações do formulário.
- O link direciona para `index.php`, tela de login.

## 2026-11-07 — Mensagem de cadastro na tela de login

- Adicionada a frase “Não possui uma conta ainda? Crie agora:” acima do link “Cadastre-se”.
- Aplicado estilo discreto e centralizado para manter a hierarquia do formulário.

## 2026-11-07 — Texto dos campos de visualização no tema escuro

- Campos somente leitura no modal “Visualização do Item” agora exibem texto branco e rótulos claros no tema escuro.
- Atualizada a versão do CSS referenciada nas páginas para evitar cache antigo.

## 2026-11-07 — Persistência e aplicação do tema escuro

- A troca de tema agora usa o ID autenticado da sessão e uma instrução preparada para salvar a preferência no banco; o estado da sessão só muda após sucesso.
- O botão de tema não envia mais ID/estado controlados pela URL e o retorno é limitado às páginas internas permitidas.
- As páginas autenticadas marcam explicitamente o tema no elemento `html` e `body`, substituindo a detecção indireta por estilo inline.
- Aplicado o fundo escuro também ao documento, sidenav, superfícies brancas do Materialize, modais e conteúdo da página atrás do modal.
- Atualizada a versão do CSS referenciada nas páginas.

## 2026-11-07 — Contraste do painel de perfil no tema escuro

- Identificadas com classes próprias as áreas do avatar e dos dados do perfil no modal de configurações.
- Aplicados fundo escuro, borda e texto claro diretamente a essas áreas para manter a leitura de nome, email, horário e status.
- Atualizada a versão do CSS nas páginas para carregar a correção sem cache antigo.

## 2026-11-07 — Modal de perfil no tema escuro

- Ajustados fundo e cores do modal de configurações/perfil e do rodapé do modal para acompanhar a preferência escura do usuário.
- Ajustados o painel de informações e os textos do modal para manter contraste no tema escuro.
- Atualizada a versão referenciada do CSS nas páginas para invalidar o cache antigo.

## 2026-11-07 — Hover legível no cabeçalho escuro

- No tema escuro, links do cabeçalho e atalho de configurações agora usam fundo azul mais escuro e texto branco ao passar o mouse ou receber foco pelo teclado.
- Atualizada a versão referenciada do CSS nas páginas para evitar que o navegador mantenha as regras antigas em cache.

## 2026-11-07 — Últimos lançamentos no dashboard

- Adicionada uma seção abaixo do gráfico e do calendário com até cinco lançamentos, ordenados pelo identificador mais recente.
- Cada linha mostra nome, tipo, data e valor; cores e ícones distinguem entradas, saídas e agendados.
- Incluído estado vazio e link para a lista completa de lançamentos.
- A consulta foi adicionada ao modelo de seleção existente e os textos apresentados nessa seção são escapados para HTML.

## 2026-11-07 — Layout do calendário no dashboard

- Reorganizado gráfico e calendário em uma grade com largura limitada e espaçamento consistente.
- Em telas menores, os blocos passam a ficar empilhados; calendário e tabela usam largura fluida sem estourar a viewport.
- Abreviados os nomes dos dias no cabeçalho do calendário, preservando os nomes completos em dicas de acessibilidade.
- Removidos atributos de apresentação da tabela do calendário e aplicada a identidade visual atual ao dia corrente.
- Corrigidas as aspas excedentes nos caminhos de inclusão do calendário e do gráfico.

## 2026-11-07 — Painel compacto na busca

- Reduzidos altura e espaçamentos do painel do formulário de busca.
- Agrupados campo e ações dentro de uma superfície própria, com layout horizontal em telas largas e empilhado em telas pequenas.
- Aumentado o espaço entre o painel e a tabela de resultados para separar visualmente as duas áreas.
- Atualizada a versão do CSS na tela de busca para refletir as mudanças sem depender do cache anterior.

## 2026-11-07 — Cores distintas para ações da listagem

- Excluir: vermelho para indicar ação destrutiva.
- Editar: azul para a ação principal de alteração.
- Visualizar: amarelo com texto escuro para facilitar a identificação e manter contraste.
- Aplicado o mesmo padrão nas ações da tabela de lançamentos e nos resultados da busca.
- Adicionada atualização de versão na URL do CSS e regras de maior especificidade para evitar cache antigo e sobrescrita pelas classes do Materialize.

## 2026-11-07 — Identidade visual e padronização de ações

### Identidade visual

- Centralizadas as cores de interface, superfícies, texto, bordas, estados e arredondamentos em variáveis CSS em `assets/css/styles.css`.
- Mantida a identidade azul do Balance, agora combinada com fundo azul acinzentado, superfícies claras e cores semânticas para sucesso, alerta e erro.
- Refinados navegação, cards financeiros, formulários, tabelas, modais, rodapé e estados responsivos.
- Mantidos os temas claro e escuro existentes, integrados aos tokens visuais.
- Corrigido o caminho da folha de estilos na página de edição.

### Botões e ações

- Ação principal: azul padrão para entrar, cadastrar, buscar, salvar, editar e adicionar.
- Ação secundária: azul suave para voltar, cancelar, visualizar e opções auxiliares.
- Ação destrutiva: vermelho uniforme para excluir e sair.
- Ação positiva: verde reservado a confirmações positivas quando necessário.
- Removidas classes de cor Materialize conflitantes de botões para que cada ação siga o padrão definido no CSS.
- Padronizados cantos, peso tipográfico, sombras, estados hover e foco visível. Botão flutuante de adicionar mantém a cor principal e recebe rótulo acessível.

### Marca e favicon

- Criado `assets/img/favicon.svg` com fundo em gradiente azul e o mesmo símbolo de carteira usado junto ao nome Balance no cabeçalho e nas telas de acesso.
- Incluída a referência ao favicon e a cor do tema do navegador nas páginas de login, cadastro, dashboard, lançamentos, busca e edição.

### Arquivos alterados

- `assets/css/styles.css`
- `assets/img/favicon.svg`
- `index.php` e `cadastro.php`
- `pages/dashboard.php`, `pages/lancamentos.php`, `pages/buscar.php` e `pages/atualizar.php`
- `pages/views/header.php`, `pages/views/modal.php`, `pages/views/table.php`, `pages/views/atualizar.php` e `pages/views/view.php`
- `docs/CHANGLOG.md`

### Escopo e validação

- Foram feitas alterações de apresentação e classes visuais. Os fluxos PHP, consultas, autenticação, registros e estrutura do banco não foram modificados nesta entrega.
- A revisão de cores cobriu as páginas e componentes encontrados na estrutura do projeto. Não foram executados testes nem validação visual em navegador nesta etapa.
