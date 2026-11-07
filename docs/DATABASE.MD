# Banco de dados do Balance

**Atualizado em:** 7 de novembro de 2026  
**Fonte:** código e scripts versionados em `database/`. O banco ativo não foi consultado nesta documentação.

## Configuração de conexão

A conexão é aberta em `database/conexao.php` com `mysqli`. Os valores são carregados de variáveis de ambiente (`DB_HOST`, `DB_USER`, `DB_PASS` e `DB_NAME`) pelo código em `database/model/`. O banco indicado no ambiente do projeto é `financeiro`; os valores de credenciais não devem ser copiados para a documentação ou para o Git. Após conectar, o charset é configurado como `utf8mb4`.

## Estrutura usada pela aplicação

### `login`

Tabela de contas de usuário, conforme os dumps SQL:

| Coluna | Tipo observado | Uso |
|---|---|---|
| `id` | `INT`, chave primária e auto incremento nos dumps | Identificador do usuário; referenciado pelos cadastros de bancos e categorias. |
| `nome` | `VARCHAR(255)` | Nome exibido no perfil. |
| `email` | `VARCHAR(255)` | Identificação usada no login. |
| `senha` | `VARCHAR(255)` | Credencial persistida. O mecanismo atual deve ser conferido em `loginActions.php`/`cadrastroActions.php`; os dados antigos podem usar MD5. |
| `data` | `TINYTEXT` | Data registrada no cadastro nos dumps antigos. |
| `tema` | `TINYINT(1)`, anulável | Preferência de tema claro/escuro. |

### `fluxocaixa`

| Coluna | Tipo observado | Uso |
|---|---|---|
| `id` | `INT`, chave primária e auto incremento | Identificador do lançamento. |
| `tipo` | `VARCHAR(255)` | `Entrada`, `Saida` ou `Agendado`. |
| `nome` | `VARCHAR(255)` | Nome/identificação do lançamento. |
| `preco` | `FLOAT` | Valor; as saídas são gravadas negativas e entradas positivas nos fluxos atuais. `FLOAT` não é adequado para precisão monetária exata. |
| `disc` | `VARCHAR(255)` | Descrição. |
| `data` | `TINYTEXT`, anulável nos dumps | Data/hora de criação legada, com formatos variados nos exemplos. |
| `banco_id` | `INT`, anulável | Referência ao banco selecionado. |
| `categoria_id` | `INT`, anulável | Referência à categoria selecionada. |
| `data_lancamento` | `DATE`, anulável | Data financeira escolhida no formulário. |

Índices e FKs de `banco_id` e `categoria_id` são definidos em `fluxocaixa.sql` e `lancamentos_campos.sql`. A exclusão de banco/categoria deixa o respectivo campo do lançamento nulo (`ON DELETE SET NULL`).

### `bancos`

Criada por `database/scripts/bancos.sql`:

| Coluna | Tipo | Uso |
|---|---|---|
| `id` | `INT AUTO_INCREMENT` | Identificador. |
| `usuario_id` | `INT NOT NULL` | Proprietário, FK para `login.id`. |
| `nome` | `VARCHAR(120)` | Nome do banco/carteira. |
| `tipo` | `VARCHAR(40)` | Tipo escolhido, como conta corrente, poupança, carteira ou dinheiro. |
| `cor` | `CHAR(7)` | Cor hexadecimal do cartão. |
| `descricao` | `VARCHAR(1000)`, anulável | Observação opcional. |
| `criado_em` | `TIMESTAMP DEFAULT CURRENT_TIMESTAMP` | Data/hora de criação. |

### `categorias`

Criada por `database/scripts/categorias.sql`:

| Coluna | Tipo | Uso |
|---|---|---|
| `id` | `INT AUTO_INCREMENT` | Identificador. |
| `usuario_id` | `INT NOT NULL` | Proprietário, FK para `login.id`. |
| `nome` | `VARCHAR(120)` | Nome da categoria. |
| `tipo` | `ENUM('Entrada', 'Saida')` | Define o grupo e a cor visual padronizada. |
| `cor` | `CHAR(7)` | Cor da categoria definida pela regra da aplicação. |
| `criado_em` | `TIMESTAMP DEFAULT CURRENT_TIMESTAMP` | Data/hora de criação. |

### `links`

Aparece nos dumps antigos `financeiro.sql`/`database.sql`, mas não foi identificada como parte dos fluxos atuais do Balance. Não deve ser removida sem verificar instalações e usos locais.

## Relacionamentos conhecidos

```text
login (1) ─── (N) bancos
login (1) ─── (N) categorias
bancos (1) ─── (N) fluxocaixa [banco_id anulável]
categorias (1) ─── (N) fluxocaixa [categoria_id anulável]
```

**Limite importante:** `fluxocaixa` não possui `usuario_id`. O código usa banco/categoria para algumas associações e cálculos, mas diversas consultas históricas ainda leem lançamentos sem filtrar por usuário. O relacionamento atual não garante isolamento completo dos dados financeiros por usuário. Esse problema deve ser resolvido com estratégia explícita de propriedade e migração; não atribua registros legados automaticamente sem validação.

## Scripts SQL e ordem de uso

Os scripts são arquivos de referência e não foram executados por esta documentação. Faça backup validado antes de qualquer alteração em uma instalação existente.

- `database/scripts/financeiro.sql`: dump legado do banco `financeiro`, com tabelas tradicionais, incluindo `login` e `fluxocaixa` antigos.
- `database/scripts/database.sql`: estrutura/dump histórico semelhante; confira o conteúdo integral antes de usar, pois pode não corresponder às versões recentes.
- `database/scripts/bancos.sql`: cria a tabela `bancos`; requer o banco e a tabela `login`.
- `database/scripts/categorias.sql`: cria a tabela `categorias`; requer o banco e a tabela `login`.
- `database/scripts/lancamentos_campos.sql`: migração única para uma tabela `fluxocaixa` existente. Executar depois de `bancos.sql` e `categorias.sql`; adiciona as três colunas, índices e FKs. Não execute duas vezes sem reverter/verificar o esquema.
- `database/scripts/fluxocaixa.sql`: cria a tabela de lançamentos com os campos novos e insere dados de exemplo; requer bancos/categorias já criados. É dump completo para cenário controlado, não migração para tabela existente.
- `database/scripts/cadastro.sql` e `cadastros.sql`: scripts didáticos/legados sem relação direta com o domínio financeiro; não usar como instalação do Balance sem inspeção.

### Atualizar uma instalação que já tem `fluxocaixa`

1. Faça e verifique backup do banco.
2. Confirme que `login` e `fluxocaixa` correspondem às colunas esperadas pelo código.
3. Execute `bancos.sql` e `categorias.sql` se as tabelas ainda não existirem.
4. Execute `lancamentos_campos.sql` uma única vez.
5. Valide FKs, índices e os formulários com dados de teste em ambiente separado antes de usar em produção.

### Instalação nova

Use um dump base que crie `financeiro`, `login` e a tabela antiga `fluxocaixa` (por exemplo, o dump `financeiro.sql`, após conferir seu conteúdo e compatibilidade), depois crie `bancos` e `categorias` e aplique `lancamentos_campos.sql`. Alternativamente, o dump `fluxocaixa.sql` pode criar os lançamentos com campos novos, mas ainda pressupõe que banco, categorias e tabela `login` existam. Não combine a criação dos campos do dump completo com a migração dos mesmos campos.

## Pontos de atenção

- Os dumps têm datas de geração antigas e não representam necessariamente o banco ativo.
- Os scripts apresentam diferenças de charset/collation entre tabelas (`latin1`, `utf8` e `utf8mb4`); qualquer conversão exige backup e revisão de caracteres.
- `preco FLOAT` pode produzir erros de arredondamento; considerar migração controlada para `DECIMAL`, após revisar todos os cálculos e consumidores.
- `data` guarda texto em formatos heterogêneos; não use-a como base cronológica confiável sem normalização. Para data do lançamento, a coluna apropriada é `data_lancamento`.
- As associações são anuláveis para preservar registros anteriores à introdução de banco/categoria/data.
- Nenhuma instrução deste documento autoriza executar SQL automaticamente. Confirme ambiente, backup e objetivo antes de aplicar alterações.
