
<?php 
    session_start();

    include('./database/conexao.php');

    include('./utils/getHoras.php');

    include('./utils/validateDados.php'); 
    
    include('./database/model/classUser.php'); 

    #Inserir dados na tabela de usuario
    $dadosUsuario = [
        'id' => null,
        'nome' => $_POST['nome'] ?? '',
        'email' => $_POST['email'] ?? '',
        'senha' => $_POST['senha'] ?? '',
        'senhaConfirme' => $_POST['senhaConfirme'] ?? '',
        'data' => Horas::getHoraData()
    ];

    if ($dadosUsuario['senha'] !== $dadosUsuario['senhaConfirme']) {
        $_SESSION['senhaInvalida'] = 'As senhas não coincidem. Tente novamente.';
        header('Location: cadastro.php');
        exit;
    }

    if (!preg_match('/^(?=.*[A-Za-z])(?=.*[0-9])[A-Za-z0-9]{8,}$/', $dadosUsuario['senha'])) {
        $_SESSION['senhaInvalida'] = 'A senha deve ter no mínimo 8 caracteres, com letras e números.';
        header('Location: cadastro.php');
        exit;
    }

    unset($_SESSION['senhaInvalida'], $_SESSION['cadastro']);
    $user = validateUser($dadosUsuario, $conexao);
    $novoUsuario = new InsertUser(
        $user['id'],
        $user['nome'],
        $user['email'],
        $user['senha'],
        $dadosUsuario['data']
    );
    $resultado = mysqli_query($conexao, $novoUsuario->inserirDadosUser());

    $_SESSION['cadastro'] = $resultado
        ? 'Cadastro realizado com sucesso!'
        : 'Não foi possível concluir o cadastro. Tente novamente.';
    header('Location: cadastro.php');
    exit;
?>
