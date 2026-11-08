# Deployment do Projeto Balance

## Visão geral

Este documento descreve como executar o projeto Balance em ambiente local e, quando necessário, em ambiente de produção ou homologação.

O sistema foi desenvolvido em PHP puro e depende de:

- PHP 8.0 ou superior
- MySQL/MariaDB
- servidor web compatível com PHP (XAMPP, WAMP, Apache, Nginx com PHP-FPM)
- acesso ao diretório do projeto e ao banco de dados

## Requisitos mínimos

### Servidor web

- Apache ou equivalente com suporte a PHP
- Permissão para leitura e escrita do diretório do projeto
- URL acessível localmente, por exemplo:

```text
http://localhost/balance-v8/
```

### Banco de dados

- MySQL ou MariaDB
- Banco configurado com nome `financeiro` ou outro nome informado no ambiente
- Usuário com permissões de leitura, escrita, atualização e exclusão no schema do projeto

### PHP

- Versão recomendada: 8.0+
- Extensão `mysqli` habilitada
- Configuração correta de `date.timezone` no `php.ini`, quando necessário

## Configuração do ambiente

### 1. Clonar ou copiar o projeto

Coloque a pasta do projeto no diretório de hospedagem local, por exemplo:

```text
C:\xampp\htdocs\balance-v8
```

### 2. Preparar arquivo de ambiente

Crie um arquivo `.env` na raiz do projeto com as variáveis de conexão do banco. O conteúdo deve seguir o padrão abaixo:

```dotenv
DB_HOST=localhost
DB_NAME=financeiro
DB_USER=seu_usuario
DB_PASS=sua_senha
```

Importante:

- esse arquivo é local e não deve ser versionado;
- não compartilhe credenciais reais em repositórios públicos;
- mantenha backups das credenciais da aplicação.

### 3. Criar o banco

Crie o banco no MySQL/MariaDB antes de iniciar o projeto.

Exemplo:

```sql
CREATE DATABASE financeiro;
```

### 4. Importar os scripts SQL

Os scripts em `database/scripts/` precisam ser aplicados conforme o estado do banco.

Ordem sugerida:

1. `database/scripts/financeiro.sql`
2. `database/scripts/bancos.sql`
3. `database/scripts/categorias.sql`
4. `database/scripts/lancamentos_campos.sql`
5. `database/scripts/lancamentos_usuario.sql`
6. `database/scripts/lembretes.sql`
7. `database/scripts/log_sistema.sql`

Observações:

- alguns scripts são migratórios e devem ser aplicados apenas uma vez;
- bases legadas podem exigir ajustes manuais antes da migração;
- sempre faça backup antes de aplicar em ambiente existente.

## Configuração da aplicação

### 1. Verificar conexão

A aplicação usa `database/conexao.php` para abrir a conexão com o banco. O arquivo deve carregar corretamente as variáveis do `.env` e permitir conexão com MySQL/MariaDB.

### 2. Validar permissões

Certifique-se de que o processo web tenha acesso ao diretório do projeto e que arquivos temporários ou uploads não bloqueiem a execução.

### 3. Verificar permissões de sessão

A aplicação usa sessão do PHP para identificar o usuário autenticado. O ambiente deve ter:

- `session.save_path` configurado corretamente;
- cookies habilitados no navegador;
- suporte ao PHP sem bloqueios de sessão.

## Execução local

### XAMPP / WAMP

1. Inicie Apache e MySQL.
2. Coloque o projeto em `htdocs` ou pasta equivalente.
3. Acesse a URL do projeto no navegador.
4. Crie a conta inicial e configure o banco conforme necessário.

Exemplo:

```text
http://localhost/balance-v8/
```

## Execução em produção ou homologação

Antes de publicar em produção, a aplicação deve passar por revisão de segurança e operação.

Checklist mínimo:

- proteger acesso ao `.env` e ao diretório de configuração;
- habilitar HTTPS;
- revisar autenticação, autorização e sessão;
- implementar backup automatizado do banco;
- atualizar e validar credenciais de acesso ao MySQL;
- revisar logs e erros de aplicação;
- confirmar que arquivos sensíveis não ficam públicos;
- revisar tratamento de entrada e saída para evitar XSS e SQL injection.

## Variáveis de ambiente esperadas

A aplicação pode depender das seguintes variáveis:

```dotenv
DB_HOST=localhost
DB_NAME=financeiro
DB_USER=seu_usuario
DB_PASS=sua_senha
```

Se houver outras configurações no futuro, elas devem ser documentadas e mantidas em `.env` ou em arquivo equivalente local, sem versionamento.

## Boas práticas de implantação

- sempre fazer backup antes de alterar esquema do banco;
- usar ambiente separado para testes antes da produção;
- manter logs e monitoramento do servidor;
- revisar migrações SQL antes de aplicá-las em produção;
- validar todos os fluxos principais após o deploy: login, cadastro, lançamento, edição, exclusão e dashboard;
- confirmar que as permissões por usuário continuam isoladas.

## Riscos conhecidos relacionados ao deploy

- migrações de banco não executadas em instalações legadas;
- dados antigos sem `usuario_id` ou sem status consistente;
- uso de `FLOAT` para valores monetários;
- uso de dados de data em formatos legados;
- falta de revisão de segurança antes de publicar a aplicação na internet.

## Checklist final de implantação

- [ ] PHP instalado e funcionando
- [ ] extensão `mysqli` habilitada
- [ ] banco MySQL/MariaDB criado
- [ ] scripts SQL importados corretamente
- [ ] `.env` configurado localmente
- [ ] projeto acessível via navegador
- [ ] login e cadastro funcionando
- [ ] dashboard e lançamentos carregando corretamente
- [ ] backup do banco confirmado
- [ ] revisão de segurança concluída

## Conclusão

O Balance é um projeto simples, compatível com infraestrutura local e fácil de implantar em ambiente PHP/MySQL. Para uso em produção, é recomendável revisar segurança, favorecer backups regulares e validar a consistência do esquema antes de alterar dados em produção.
