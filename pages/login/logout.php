<?php
    // Inicializa a sessão (se ainda não estiver iniciada)
    session_start();
    /** 
    * Realiza qualquer operação necessária na sessão
    * site : https://pt.stackoverflow.com/questions/321542/php-session-destruir-todas-as-sessions-e-manter-apenas-uma-ativa
    **/
    // Destroi todas as sessão
    $_SESSION = [];
    session_destroy();

    // Redireciona para outra página (opcional)
    header('Location: ../../index.php');
    exit;
?>
