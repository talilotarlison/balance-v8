<?php 

    include_once(__DIR__ . '/model/classCredencial.php'); 
         
    # Criança do objeto banco com a credenciais 
    
    $db = new Connection(SERVER,USERNAME,PASSWORD,DB);

    # conexão com banco de dados sem orientação a objeto

    # Cria a conexão
    $conexao = new mysqli(
        $db->getServe(), 
        $db->getUserName(), 
        $db->getPassword(), 
        $db->getDatabase()
    );
    
    # Verifica a conexão
    if ($conexao->connect_errno) {
        throw new RuntimeException('mysqli connection error: ' . $conexao->connect_error);
    }

    // Defina o conjunto de caracteres desejado após estabelecer uma conexão 
    $conexao->set_charset('utf8mb4');

    if ($conexao->errno) {
        throw new RuntimeException('mysqli error: ' . $conexao->error);
    }

    // echo "Conectado com sucesso!";
    // mysqli_close($conexao); // Fechar a conexão quando não for mais necessária

?>