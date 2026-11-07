# Projeto de Finanças em PHP Puro

O Balance é um sistema de gerenciamento de finanças pessoais desenvolvido em PHP puro, sem frameworks. Permite registrar receitas, despesas e lançamentos agendados, organizar movimentações por banco e categoria e acompanhar os saldos em um dashboard.

## Funcionalidades

- Cadastro e autenticação de usuários.
- Cadastro de entradas, saídas e lançamentos agendados.
- Edição, visualização e exclusão de lançamentos, com confirmação para excluir.
- Escolha da data do lançamento, banco e categoria.
- Listagem de transações e busca textual.
- Filtros combináveis por tipo de lançamento e tipo de banco.
- Cadastro de bancos/carteiras com tipo, cor, descrição e data de criação.
- Cadastro de categorias de entrada e saída, com cores padronizadas.
- Dashboard com totais, gráfico, calendário, lançamentos recentes, saldos por banco e lançamentos recentes por categoria.
- Tema claro e escuro com preferência salva no perfil.
- Interface responsiva baseada em Materialize.

## Tecnologias

- PHP puro (o código atual usa sintaxe compatível com PHP 8.0 ou superior).
- MySQL/MariaDB por meio da extensão PHP `mysqli`.
- HTML, CSS e JavaScript.
- Materialize CSS/JS 1.0.0, Material Icons, jQuery em algumas páginas e Google Charts, carregados por CDN.

As versões e extensões declaradas no `composer.json` são requisitos de dependência, mas o código contém tipos `mixed`, introduzidos no PHP 8.0. Por isso, use PHP 8.0 ou superior no ambiente de execução.

## Requisitos

- PHP 8.0 ou superior, com extensão `mysqli` habilitada.
- MySQL ou MariaDB.
- Servidor web com suporte a PHP (recomendado: XAMPP, WAMP ou similar).
- Acesso à internet no navegador para carregar as bibliotecas servidas por CDN.

## Instalação local (XAMPP)

1. Clone este repositório ou faça o download do código-fonte.
2. Coloque a pasta do projeto no diretório do servidor web (por exemplo, `htdocs` no XAMPP).
3. Inicie Apache e MySQL/MariaDB pelo painel do XAMPP.
4. Crie o banco de dados `financeiro` no MySQL/MariaDB.
5. Configure o arquivo `.env` na raiz do projeto com as credenciais locais:

   ```dotenv
   DB_HOST=localhost
   DB_NAME=financeiro
   DB_USER=seu_usuario
   DB_PASS=sua_senha
   ```

   O arquivo `.env` é ignorado pelo Git. Não compartilhe nem versione credenciais reais.

6. Importe os scripts SQL na ordem abaixo, em um banco novo ou ambiente de desenvolvimento:

   1. `database/scripts/financeiro.sql` — cria as tabelas-base `login`, `fluxocaixa` e `links`.
   2. `database/scripts/bancos.sql` — cria a tabela de bancos, vinculada a `login`.
   3. `database/scripts/categorias.sql` — cria a tabela de categorias, vinculada a `login`.
   4. `database/scripts/lancamentos_campos.sql` — adiciona banco, categoria e data do lançamento à tabela existente `fluxocaixa`.

   Os dumps podem conter dados de exemplo. Em uma base que já tenha dados, faça e verifique um backup antes da migração. O script `lancamentos_campos.sql` deve ser executado uma única vez e somente se as colunas e chaves ainda não existirem. Não aplique essa migração após importar `database/scripts/fluxocaixa.sql`, pois esse dump já inclui os campos adicionais.

7. Acesse o projeto pelo navegador, por exemplo `http://localhost/balance-v8/`, e crie uma conta pela página de cadastro.

> Os scripts SQL versionados não foram executados automaticamente. Consulte [docs/DATABASE.MD](docs/DATABASE.MD) para detalhes do esquema, dependências e diferenças entre os dumps. Antes de migrar uma base existente, confirme o esquema real e tenha backup restaurável.

## Estrutura do projeto

- `index.php`: tela de login.
- `loginActions.php`, `cadastro.php`, `cadrastroActions.php`: processamento de login e cadastro.
- `pages/`: páginas autenticadas do dashboard, lançamentos, bancos, categorias e busca, além de endpoints de inclusão, edição, exclusão e tema.
- `pages/views/`: componentes de apresentação usados nas páginas, como cabeçalho, tabela, dashboard e formulários.
- `database/conexao.php`: conexão com o banco via `mysqli`.
- `database/model/`: carregamento de ambiente e classes de acesso/consulta legadas.
- `database/scripts/`: criação e migração do esquema e dumps SQL.
- `utils/`: utilitários de calendário, gráficos, mensagens, validação e componentes.
- `assets/`: CSS, JavaScript e imagens do projeto.
- `docs/`: PRD, diagnóstico, status, tarefas, changelog e documentação de banco de dados.
- `.env`: configuração local de conexão (não versionar).

## Uso

1. Acesse a tela de login e entre com sua conta, ou use o link de cadastro para criar uma.
2. Cadastre bancos/carteiras e categorias de entrada e saída.
3. Use o formulário de lançamentos para registrar tipo, nome, valor, data, banco, categoria e descrição.
4. Consulte e filtre lançamentos na página “Lançamentos”; use “Buscar” para localizar pelo nome.
5. Acompanhe os totais, saldos por banco e movimentações recentes no dashboard.
6. Altere o tema pelo menu do usuário.

## Documentação adicional

- [PRD e requisitos do produto](docs/PRD.md)
- [Diagnóstico técnico](docs/DIAGNOSTICO.md)
- [Estado atual do projeto](docs/PROJECT_STATUS.md)
- [Tarefas e pendências](docs/TASK.md)
- [Estrutura e scripts do banco](docs/DATABASE.MD)
- [Histórico de alterações](docs/CHANGLOG.md)

## Segurança e limitações conhecidas

- Use apenas credenciais locais/de desenvolvimento neste setup de exemplo e mantenha `.env` fora do controle de versão.
- Os registros legados de `fluxocaixa` não possuem coluna `usuario_id`; isso limita o isolamento completo das transações por usuário. Consulte `docs/DIAGNOSTICO.md` antes de expor a aplicação em produção.
- O esquema contém `preco FLOAT` e uma coluna legada de data textual; há riscos de precisão monetária e formatos de data. Não altere o esquema sem backup e plano de migração.
- A configuração de segurança e autorização deve ser revisada antes de disponibilizar o sistema na internet.

## Contribuição

Contribuições são bem-vindas. Abra uma issue para descrever a mudança proposta ou envie um pull request com contexto e passos para validação.

## Licença

Consulte o arquivo [LICENSE](LICENSE) para os termos da licença. Observação: o arquivo `composer.json` declara `GPL-3.0`, enquanto `LICENSE` contém a licença MIT; essa divergência precisa ser resolvida pelos responsáveis antes de redistribuir o projeto.
