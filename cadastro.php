<?php
    // sessao usuario
    session_start();
    
    if (isset($_SESSION['usuario'])) {
      header('Location: ./pages/dashboard.php');
    } 

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="theme-color" content="#246bfe">
    <link rel="icon" type="image/svg+xml" href="./assets/img/favicon.svg">
    <title>Balance - Cadastro</title>
    <link rel="stylesheet" href="./assets/css/styles.css?v=20261107-16">
</head>

<body>
    <section>
        <div style="margin-top: 5vh; display: flex; justify-content: center;">
            <form action="cadrastroActions.php" method="post" class="container z-depth-3" style="border-radius: 10px; padding: 40px; max-width: 600px; background-color: #ffffff;">
                <h4 style="display:flex; align-items:center; justify-content:center; gap:12px; text-align: center; font-weight: bold; margin-bottom: 30px;"><span class="brand-mark"><i class="material-icons">account_balance_wallet</i></span>Cadastro Balance</h4>
                <div class="row" style="margin-bottom: 20px;">
                    <div class="input-field col s12">
                        <i class="material-icons prefix">account_circle</i>
                        <input id="nome" type="text" class="validate" placeholder="Nome" name="nome" required>
                    </div>
                </div>
                <div class="row" style="margin-bottom: 20px;">
                    <div class="input-field col s12">
                        <i class="material-icons prefix">email</i>
                        <input id="email" type="email" class="validate" placeholder="Email" name="email" required>
                    </div>
                </div>
                <div class="row" style="margin-bottom: 20px;">
                    <div class="input-field col s12">
                        <i class="material-icons prefix">lock</i>
                        <input id="senha" type="password" class="validate" placeholder="Senha" name="senha" minlength="8" pattern="(?=.*[A-Za-z])(?=.*[0-9])[A-Za-z0-9]{8,}" title="Use no mínimo 8 caracteres, com letras e números." autocomplete="new-password" required>
                        <span class="helper-text">Use no mínimo 8 caracteres, com letras e números.</span>
                    </div>
                </div>
                <div class="row" style="margin-bottom: 20px;">
                    <div class="input-field col s12">
                        <i class="material-icons prefix">lock_outline</i>
                        <input id="senhaConfirme" type="password" class="validate" placeholder="Confirmar Senha" name="senhaConfirme" autocomplete="new-password" required>
                    </div>
                </div>
                <?php
                    if (!empty($_SESSION['senhaInvalida'])) {
                        echo "<p style='color: red; text-align: center;'>" . $_SESSION['senhaInvalida'] . "</p>";
                    } elseif (!empty($_SESSION['cadastro'])) {
                        echo "<p style='color: green; text-align: center;'>" . $_SESSION['cadastro'] . "</p>";
                    }
                ?>
                <div class="row" style="margin-top: 30px; text-align: center;">
                    <button class="btn waves-effect waves-light" type="submit" style="margin-right: 10px;">Cadastrar</button>
                    <a href="index.php" class="btn-flat btn-ghost waves-effect">Voltar</a>
                </div>
                <p class="signup-prompt center-align">Já possui cadastro? <a href="index.php">Faça login</a></p>
            </form>
        </div>
    </section>
</body>

</html>
