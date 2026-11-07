<?php
    // sessao usuario 
    session_start();
      
    if (!isset($_SESSION['usuario']) || empty($_SESSION['idUser'])) {
        header('Location: /login');
        exit;
    } 
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
  <title>Balance - Atualizar Dados</title>
  <link rel="stylesheet" href="../assets/css/styles.css?v=20261107-17">
</head>

  <body class="<?= (int) ($_SESSION['temaUser'] ?? 0) === 1 ? 'theme-dark' : 'theme-light' ?>">

  <?php 
    include('../database/conexao.php');
    include('../database/model/classSelect.php'); 
    #Update no banco de dados 
    include('../utils/updateController.php'); 

  ?>
	<!-- Header -->
  <?php include('./views/header.php'); ?>
 	 <!-- Header -->

 	<!-- Formulario Atualzar dados -->
  <?php include('./views/atualizar.php'); ?>
 	 <!-- Formulario Atualzar dados-->

   <!-- modal da aplicação-->
    <?php include('./views/modal.php'); ?>
    <!-- modal da aplicação-->

	<!-- footer -->
  <?php include('./views/footer.php'); ?>
  <!-- footer -->

  <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
  <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
  
  <?php include("../utils/modal.php"); ?>
    <?php include("../utils/btnVoltar.php"); ?>
</body>

</html>


	
