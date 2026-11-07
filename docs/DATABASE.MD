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
| `usuario_id` | `INT`, anulável | Dono do lançamento, FK para `login.id`. É anulável para preservar os registros legados até que seu proprietário seja confirmado. Os lançamentos novos recebem o ID da sessão autenticada. |
| `tipo` | `VARCHAR(255)` | `Entrada`, `Saida` ou `Agendado`. |
| `nome` | `VARCHAR(255)` | Nome/identificação do lançamento. |
| `preco` | `FLOAT` | Valor; as saídas são gravadas negativas e entradas positivas nos fluxos atuais. `FLOAT` não é adequado para precisão monetária exata. |
| `disc` | `VARCHAR(255)` | Descrição. |
| `data` | `TINYTEXT`, anulável nos dumps | Data/hora de criação legada, com formatos variados nos exemplos. |
| `banco_id` | `INT`, anulável | Referência ao banco selecionado. |
| `categoria_id` | `INT`, anulável | Referência à categoria selecionada. |
| `data_lancamento` | `DATE`, anulável | Data financeira escolhida no formulário. |

Índices e FKs de `usuario_id`, `banco_id` e `categoria_id` são definidos em `fluxocaixa.sql`, `lancamentos_campos.sql` e `lancamentos_usuario.sql`. A exclusão do usuário, banco ou categoria deixa o respectivo campo do lançamento nulo (`ON DELETE SET NULL`). O código novo filtra as operações de lançamentos pelo `usuario_id` da sessão.

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
| `tipo` | `ENUM('Entrada', 'Saida', 'Agendado')` | Define o grupo e a cor visual padronizada. |
| `cor` | `CHAR(7)` | Cor da categoria definida pela regra da aplicação. |
| `criado_em` | `TIMESTAMP DEFAULT CURRENT_TIMESTAMP` | Data/hora de criação. |

### `links` (links de lembretes)

A tabela legada `links` agora é usada pelo módulo de lembretes. `database/scripts/lembretes.sql` migra sua estrutura existente:

| Coluna | Tipo após migração | Uso |
|---|---|---|
| `id` | `INT AUTO_INCREMENT`, chave primária | Identificador do lembrete. |
| `usuario_id` | `INT`, anulável, FK para `login.id` | Dono do lembrete; novos registros recebem o usuário autenticado. Registros antigos permanecem nulos até serem atribuídos manualmente com segurança. |
| `nome` | `VARCHAR(255)` | Título/nome obrigatório. |
| `descricao` | `TEXT`, anulável | Descrição opcional do lembrete. |
| `email` | `VARCHAR(255)`, anulável | Contato opcional. |
| `telefone` | `VARCHAR(30)`, anulável | Contato opcional. |
| `url` | `VARCHAR(255)` | URL opcional; a aplicação grava string vazia quando não informada para manter compatibilidade com a coluna legada `NOT NULL`. |
| `data_prevista` | `DATE`, anulável para preservar registros antigos | Data prevista; obrigatória nos novos lembretes. |
| `visto` | `TINYINT(1) NOT NULL DEFAULT 0` | `0` pendente, `1` concluído; valores textuais antigos são convertidos na migração. |

O módulo não exibe lembretes legados com `usuario_id` nulo, para não compartilhar dados entre usuários sem conhecer seu proprietário. O script `lembretes.sql` deve ser feito backup e executado uma única vez; não foi executado por esta implementação.

### `log_sistema`

Criada por `database/scripts/log_sistema.sql`. Armazena um evento para cada login autenticado:

| Coluna | Tipo | Uso |
|---|---|---|
| `id` | `BIGINT UNSIGNED AUTO_INCREMENT` | Identificador do evento. |
| `usuario_id` | `INT NOT NULL` | Usuário autenticado, FK para `login.id`. |
| `data_hora` | `DATETIME DEFAULT CURRENT_TIMESTAMP` | Data e hora do acesso, conforme relógio/configuração MySQL. |

`loginActions.php` grava o acesso após validar as credenciais. Execute o script antes de implantar o código atualizado.

## Relacionamentos conhecidos

```text
login (1) ─── (N) bancos
login (1) ─── (N) categorias
login (1) ─── (N) fluxocaixa [usuario_id anulável nos registros legados]
login (1) ─── (N) links/lembretes [usuario_id anulável nos registros legados]
login (1) ─── (N) log_sistema
bancos (1) ─── (N) fluxocaixa [banco_id anulável]
categorias (1) ─── (N) fluxocaixa [categoria_id anulável]
```

**Migração de propriedade:** em uma base existente, `lancamentos_usuario.sql` adiciona `usuario_id` e sua FK. Os registros existentes permanecem com `NULL`, pois não há informação confiável para atribuí-los. A aplicação filtra lançamentos pelo proprietário autenticado; portanto, esses registros não aparecem até que seu dono seja confirmado e atualizado manualmente. Não atribua automaticamente todos os registros a uma conta.

## Scripts SQL e ordem de uso

Os scripts são arquivos de referência e não foram executados por esta documentação. Faça backup validado antes de qualquer alteração em uma instalação existente.

- `database/scripts/financeiro.sql`: dump legado do banco `financeiro`, com tabelas tradicionais, incluindo `login` e `fluxocaixa` antigos.
- `database/scripts/database.sql`: estrutura/dump histórico semelhante; confira o conteúdo integral antes de usar, pois pode não corresponder às versões recentes.
- `database/scripts/bancos.sql`: cria a tabela `bancos`; requer o banco e a tabela `login`.
- `database/scripts/categorias.sql`: cria a tabela `categorias`; requer o banco e a tabela `login`.
- `database/scripts/categorias_agendado.sql`: migração para tabela `categorias` já existente, ampliando o ENUM para aceitar `Agendado`; após conferir que os registros de tipo vazio vieram dessas tentativas, o script também os converte para `Agendado`. Executar uma única vez após backup. Bases novas usam o enum atualizado de `categorias.sql`.
- `database/scripts/lembretes.sql`: migra a tabela legada `links` para o módulo de lembretes; adiciona dono, email, telefone e data prevista, converte `visto` para estado binário e garante chave primária/auto incremento. Executar uma vez após backup.
- `database/scripts/lancamentos_campos.sql`: migração única para uma tabela `fluxocaixa` existente. Executar depois de `bancos.sql` e `categorias.sql`; adiciona as três colunas, índices e FKs. Não execute duas vezes sem reverter/verificar o esquema.
- `database/scripts/lancamentos_usuario.sql`: migração única para uma tabela `fluxocaixa` existente; adiciona `usuario_id`, índice e FK para `login`. Requer backup e deve ser aplicada depois das migrações de bancos/categorias/campos. Registros antigos ficam sem proprietário até associação manual validada.
- `database/scripts/fluxocaixa.sql`: cria a tabela de lançamentos com os campos novos, incluindo `usuario_id`, e insere exemplos sem dono (`NULL`); requer `login`, bancos e categorias já criados. É dump completo para cenário controlado, não migração para tabela existente.
- `database/scripts/cadastro.sql` e `cadastros.sql`: scripts didáticos/legados sem relação direta com o domínio financeiro; não usar como instalação do Balance sem inspeção.

### Atualizar uma instalação que já tem `fluxocaixa`

1. Faça e verifique backup do banco.
2. Confirme que `login` e `fluxocaixa` correspondem às colunas esperadas pelo código.
3. Execute `bancos.sql` e `categorias.sql` se as tabelas ainda não existirem; se `categorias` já existir, aplique `categorias_agendado.sql` para habilitar o novo tipo.
4. Execute `lancamentos_campos.sql` uma única vez se as novas colunas/relacionamentos ainda não existirem.
5. Execute `lancamentos_usuario.sql` uma única vez para habilitar a separação de lançamentos por conta.
6. Valide FKs, índices e os formulários com dados de teste em ambiente separado antes de usar em produção. Associe registros legados a usuários somente depois de confirmar a titularidade.

### Instalação nova

Use um dump base que crie `financeiro`, `login` e a tabela antiga `fluxocaixa` (por exemplo, `financeiro.sql`, após conferir compatibilidade), depois crie `bancos` e `categorias` e aplique `lancamentos_campos.sql` e `lancamentos_usuario.sql`. Alternativamente, o dump `fluxocaixa.sql` cria a tabela com os campos atuais, incluindo `usuario_id`, mas pressupõe que `login`, bancos e categorias existam. Não combine a criação dos campos do dump completo com as migrações correspondentes.

## Pontos de atenção

- Os dumps têm datas de geração antigas e não representam necessariamente o banco ativo.
- Os scripts apresentam diferenças de charset/collation entre tabelas (`latin1`, `utf8` e `utf8mb4`); qualquer conversão exige backup e revisão de caracteres.
- `preco FLOAT` pode produzir erros de arredondamento; considerar migração controlada para `DECIMAL`, após revisar todos os cálculos e consumidores.
- `data` guarda texto em formatos heterogêneos; não use-a como base cronológica confiável sem normalização. Para data do lançamento, a coluna apropriada é `data_lancamento`.
- As associações são anuláveis para preservar registros anteriores à introdução de banco/categoria/data.
- Nenhuma instrução deste documento autoriza executar SQL automaticamente. Confirme ambiente, backup e objetivo antes de aplicar alterações.
