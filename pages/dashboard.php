<?php 
  # sessao usuario
  session_start();
  
  if (!isset($_SESSION['usuario']) || empty($_SESSION['idUser'])) {
    header('Location: ../index.php');
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
  <title>Balance - Dashboard</title>
  <link rel="stylesheet" href="../assets/css/styles.css?v=20261107-21">
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
</head>
<body class="<?= (int) ($_SESSION['temaUser'] ?? 0) === 1 ? 'theme-dark' : 'theme-light' ?>">
<?php 
  include_once('../database/conexao.php');

  include('../database/model/classSelect.php'); 

?> 
	<!-- HEADER -->
  <?php include('./views/header.php'); ?>
 	 <!-- HEADER -->
  <!-- Dashboard da aplicação-->
   <main>
        <?php include('./views/dashboard.php'); ?>
          <!-- Dashboard da aplicação-->
     
        <section class="dashboard-visuals">
            <div class="dashboard-chart">
              <div id="piechart"></div>
            </div>
            <aside class="dashboard-calendar" aria-label="Calendário do mês">
              <?php include('../utils/calendario.php'); ?>
            </aside>
        </section>
        <?php
          $lembretesPendentesDashboard = [];
          $stmtLembretesPendentes = $conexao->prepare('SELECT id, nome, descricao, data_prevista FROM links WHERE usuario_id = ? AND visto = 0 ORDER BY data_prevista ASC, id DESC LIMIT 6');
          if ($stmtLembretesPendentes) {
            $usuarioDashboardLembretesId = (int) $_SESSION['idUser'];
            $stmtLembretesPendentes->bind_param('i', $usuarioDashboardLembretesId);
            if ($stmtLembretesPendentes->execute()) {
              $stmtLembretesPendentes->bind_result($lembreteDashboardId, $lembreteDashboardNome, $lembreteDashboardDescricao, $lembreteDashboardData);
              while ($stmtLembretesPendentes->fetch()) {
                $lembretesPendentesDashboard[] = ['id' => (int) $lembreteDashboardId, 'nome' => $lembreteDashboardNome, 'descricao' => $lembreteDashboardDescricao, 'data' => $lembreteDashboardData];
              }
            }
            $stmtLembretesPendentes->close();
          }
        ?>
        <section class="dashboard-reminders" aria-labelledby="dashboard-reminders-title">
          <div class="dashboard-reminders-heading"><div><span class="section-eyebrow">Acompanhe seus compromissos</span><h2 id="dashboard-reminders-title">Próximos lembretes</h2></div><a href="lembretes.php">Ver lembretes</a></div>
          <?php if (!$lembretesPendentesDashboard): ?>
            <p class="dashboard-reminders-empty">Você não tem lembretes pendentes.</p>
          <?php else: ?>
            <div class="dashboard-reminders-list">
              <?php foreach ($lembretesPendentesDashboard as $lembreteDashboard): ?>
                <article class="dashboard-reminder-item">
                  <i class="material-icons" aria-hidden="true">notifications_active</i>
                  <div><h3><?= htmlspecialchars($lembreteDashboard['nome'], ENT_QUOTES, 'UTF-8') ?></h3>
                    <?php if (!empty($lembreteDashboard['descricao'])): ?><p><?= htmlspecialchars($lembreteDashboard['descricao'], ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?></div>
                  <time datetime="<?= htmlspecialchars((string) $lembreteDashboard['data'], ENT_QUOTES, 'UTF-8') ?>"><?= $lembreteDashboard['data'] ? date('d/m/Y', strtotime($lembreteDashboard['data'])) : 'Sem data' ?></time>
                </article>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </section>
        <?php include('./views/recentes.php'); ?>
</main> 

 <!-- Modal Add Trigger nova entrada tbn-->
 <a href="#modal-add" class="btn-floating btn-large waves-effect waves-light modal-trigger" aria-label="Adicionar lançamento"><i class="material-icons">add</i></a>
 <!-- Modal Add Trigger nova entrada btn-->
  
   <!-- modal da aplicação-->
    <?php include('./views/modal.php'); ?>
    <!-- modal da aplicação-->
   
	<!-- footer -->
  <?php include('./views/footer.php'); ?>
  <!-- footer -->

  <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
  <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
  
  <?php include("../utils/modal.php");?>
   <?php include("../utils/btnVoltar.php"); ?>
  	<!-- grafico pizza -->
  <?php include('../utils/grafico.php'); ?>
 	<!-- grafico pizza -->
   <?php  
      // mensagem do sistema para o usuario 
      if(isset($_SESSION['mensagem'])){
          include('../utils//mensagem.php');
          unset($_SESSION['mensagem']);
      }
  ?>
</body>

</html>


	
