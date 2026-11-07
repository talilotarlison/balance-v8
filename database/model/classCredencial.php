<?php
   /** 
    * Conexão com banco de dados com orientação a objeto
    * Autor: Talilo Tarlison
    * Descrição: Classe de conexão com o banco de dados, onde é possível criar uma conexão com o banco de dados MySQL usando PHP orientado a objetos.
    * Data: 2023-10-04
    * 
    * Referências:
    * https://www.dio.me/articles/conexao-com-o-banco-de-dados-com-php-mysqli
    * SQL e Sessao do site
    * https://cursos.alura.com.br/forum/topico-coluna-definida-como-boolean-ou-tinyint-1-aceita-outros-n-uacute-meros-24641
    * https://pt.stackoverflow.com/questions/321542/php-session-destruir-todas-as-sessions-e-manter-apenas-uma-ativa
    */ 

   // Inclui o arquivo de configuração do ambiente
   // Verifica se o arquivo de configuração do ambiente existe
   include_once(__DIR__ . '/classLoadEnv.php');

   // Adjust the path to point to the root of the project
   $rootPath = dirname(__DIR__, 2); // Navigate two levels up to the project root
   $envPath = $rootPath . '/.env';
   // Uso da class env loadEnv
   Env::loadEnv($envPath);

  
   // Acesse as variáveis
   // de ambiente usando getenv() ou $_ENV

   define('SERVER', getenv('DB_HOST'));
   define('USERNAME', getenv('DB_USER'));
   define('PASSWORD', getenv('DB_PASS'));
   define('DB', getenv('DB_NAME'));

   
   class Connection {
      public $server;  // Servidor do banco de dados (normalmente "localhost" para servidores locais)
      public $username; // Nome de usuário do banco de dados
      public $password; // Senha do banco de dados
      public $db; // Nome do banco de dados que estamos usando

      public function __construct($server,$username,$password,$db) {
      $this->server = $server;
      $this->username = $username;
      $this->password =  $password;
      $this->db =  $db;
      }

      public function getServe(){
         return $this->server;
      }

      public function getUserName(){
         return $this->username;
      }

      public function getDatabase(){
         return $this->db;
      }

      public function getPassword(){
         return $this->password;
      }
   }

?>