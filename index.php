<?php
    # sesao usuario
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
    <title>Balance - Login</title>
    <link rel="stylesheet" href="./assets/css/styles.css?v=20261107-16">
</head>

<body>
    <section>
        <!-- Modal Structure editar item -->
        <div style='margin-top: 10vh; display: flex; justify-content: center; align-items: center;'>
            <form action='loginActions.php' method="post" class="container z-depth-3" style='padding: 50px; max-width: 500px; background-color: #ffffff;'>
                <h4 style="display:flex; align-items:center; justify-content:center; gap:12px; text-align: center; font-weight: bold; margin-bottom: 30px;"><span class="brand-mark"><i class="material-icons">account_balance_wallet</i></span>Login Balance</h4>
                <div class="row" style="max-width: 400px; margin: 0 auto;">
                    <div class="input-field">
                        <i class="material-icons prefix">email</i>
                        <input id="email" type="email" name="email" class="validate" placeholder="Email:" required>
                    </div>
                    <div class="input-field">
                        <i class="material-icons prefix">lock</i>
                        <input id="password" type="password" name="senha" class="validate" placeholder="Senha" required>
                    </div>
                    <?php
                        // sessao do usuario
                        if (!empty($_SESSION['erroLogin'])) {
                            echo "<p style='color: red; text-align: center;'>" . $_SESSION['erroLogin'] . "</p>";
                        }
                    ?>
                    <div class="center-align" style="margin-top: 20px;">
                        <button class="btn waves-effect waves-light" type="submit" style="width: 100%; margin-bottom: 10px;">Entrar</button>
                        <p class="signup-prompt support-prompt">Esqueceu a senha? Entre em contato com o <a href="mailto:suporte@balance.com" class="red-text text-darken-2" style="text-decoration: underline;"><strong>suporte</strong></a>.</p>
                        <p class="signup-prompt">Não possui uma conta ainda? Crie agora.</p>
                        <a href='cadastro.php' class="btn-flat btn-ghost">Cadastre-se</a>
                    </div>
                </div>
            </form>
        </div>
    </section>
</body>

</html>
