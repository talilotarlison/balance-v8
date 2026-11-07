<?php
  // Incluir arquivos
  include('../database/conexao.php');
  include_once('../database/model/classBuscar.php'); 
  // sessao de usuario 
  session_start();
  
  if (!isset($_SESSION['usuario'])) {
    header('Location: index.php');
  } 

  include('../database/model/classSelect.php'); 
?>

<!DOCTYPE html>
<html lang="pt-br" class="<?= (int) ($_SESSION['temaUser'] ?? 0) === 1 ? 'theme-dark' : 'theme-light' ?>">
<head>
  <meta charset="UTF-8" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="theme-color" content="#246bfe">
  <link rel="icon" type="image/svg+xml" href="../assets/img/favicon.svg">
  <title>Balance - Buscar Lançamento</title>
  <link rel="stylesheet" href="../assets/css/styles.css?v=20261107-17">
</head>
<body class="<?= (int) ($_SESSION['temaUser'] ?? 0) === 1 ? 'theme-dark' : 'theme-light' ?>">

  <!-- HEADER -->
  <?php include('./views/header.php'); ?>
  <!-- HEADER -->

  <!-- view da aplicação-->
  <?php include('./views/view.php'); ?>
  <!-- view da aplicação-->

  <!-- modal da aplicação-->
  <?php include('./views/modal.php'); ?>
  <!-- modal da aplicação-->

  <!-- Formulario buscar dados -->
  <main>
  <section class="container search-panel-wrap">
      <div id="buscar" class="search-panel">
        <h4>Buscar lançamentos</h4>
        <form method="POST" action="buscar.php" class="search-form">
          <div class="input-field search-input">
            <i class="material-icons prefix">search</i>
            <input placeholder="Digite o nome do lançamento" id="busca" type="text" class="validate" name="busca" required>
            <label for="busca">Buscar por nome</label>
          </div>
          <div class="search-actions">
            <a class="btn btn-secondary" onclick="bntVoltar();">
              <i class="material-icons left">arrow_back</i>Voltar
            </a>
            <button type="submit" class="btn">
              <i class="material-icons left">search</i>Buscar
            </button>
          </div>
        </form>
      </div>
    </section>
    <!-- Formulario buscar dados -->

    <!-- Formulario exibe dados-->
    <section class="container search-results">
      <table class='striped centered responsive-table'>
        <thead>
          <tr>
            <th>Status</th>
            <th>Item Nome</th>
            <th>Item Preço</th>
            <th>Descrição</th>
            <th>Data</th>
            <th>Operação</th>
          </tr>
        </thead>

        <tbody>
        <?php 
          $busca = $_POST["busca"] ?? " ";
          $buscaVerificada = mysqli_real_escape_string($conexao, trim($busca)); 
          $buscarDados = new Buscar($buscaVerificada);
          $query = $buscarDados->buscarPorDadosDesc();
          $resultadoBusca = mysqli_query($conexao, $query);
          $row = mysqli_num_rows($resultadoBusca);

          if ($row === 0) {
        ?>
            <tr><td colspan="6">Nenhum lançamento encontrado no sistema!</td></tr>
        <?php 
          } else {
            while ($linha = mysqli_fetch_assoc($resultadoBusca)) {
        ?>
            <tr>
              <td>
                <?php
                  if ($linha['tipo'] == 'Saida') {
                    echo "<div class='code-status'><span class='saida code-tipe'></span>" . $linha['tipo'] . "</div>";
                  } elseif ($linha['tipo'] == 'Entrada') {
                    echo "<div class='code-status'><span class='entrada code-tipe'></span>" . $linha['tipo'] . "</div>";
                  } elseif ($linha['tipo'] == 'Agendado') {
                    echo "<div class='code-status'><span class='agendado code-tipe'></span>" . $linha['tipo'] . "</div>";
                  }
                ?>
              </td>
              <td><?= substr($linha['nome'], 0, 20) . "..." ?></td>
              <td>R$ <?= number_format($linha['preco'], 2, ',', ' ') ?></td>
              <td><?= substr($linha['disc'], 0, 20) . "..." ?></td>
              <td><?= !empty($linha['data_lancamento']) ? date('d/m/Y', strtotime($linha['data_lancamento'])) : htmlspecialchars((string) $linha['data'], ENT_QUOTES, 'UTF-8') ?></td>
              <td>
                <a href="#modal<?= $linha['id'] ?>" class="waves-effect btn btn-danger modal-trigger">
                  <i class="material-icons">delete</i>
                </a>
                <a href="./atualizar.php?id=<?= $linha['id'] ?>" class="btn btn-edit waves-effect">
                  <i class="material-icons">edit</i>
                </a>
                <a href="?view=<?= $linha['id'] ?>" class="btn btn-view waves-effect">
                  <i class="material-icons">visibility</i>
                </a>
              </td>
            </tr>

            <!-- Modal de exclusão -->
            <div id="modal<?= $linha['id'] ?>" class="modal">
              <div class="modal-content">
                <h4>Excluir item: <?= $linha['nome'] ?></h4>
                <p>Tem certeza que quer excluir?</p>
              </div>
              <div class="modal-footer">
                <a href="./delete.php?id=<?= $linha['id'] ?>" class="modal-close waves-effect btn btn-danger">Aceitar</a>
                <a href="#!" class="modal-close waves-effect btn-flat btn-secondary">Cancelar</a>
              </div>
            </div>
        <?php 
            }
          }
        ?>
        </tbody>
      </table>
    </section> 
  </main>

 <!-- Modal Add Trigger nova entrada tbn-->
 <a href="#modal-add" class="btn-floating btn-large waves-effect waves-light modal-trigger" aria-label="Adicionar lançamento"><i class="material-icons">add</i></a>
 <!-- Modal Add Trigger nova entrada btn-->
  
  <!-- Formulario exibe dados-->
  <!-- modal da aplicação-->
  <!-- footer -->
  <?php include('./views/footer.php'); ?>
  <!-- footer -->

  <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
  <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>

  <?php include("../utils/modal.php"); ?>
  <?php include("../utils/btnVoltar.php"); ?>

  <?php  
      // mensagem do sistema para o usuario 
      if(isset($_SESSION['mensagem'])){
          include('../utils/mensagem.php');
          unset($_SESSION['mensagem']);
      }
  ?>
</body>

</html>
