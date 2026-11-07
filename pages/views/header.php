<header>
    <div class="navbar-fixed">
      <nav>
        <div class="nav-wrapper">
          <a href="dashboard.php" class="brand-logo"><span class="brand-mark"><i class="material-icons">account_balance_wallet</i></span><span>Balance</span></a>
          <a href="#" data-target="slide-out" class="sidenav-trigger"><i class="material-icons">menu</i></a>
          <ul class="right hide-on-med-and-down">
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="lancamentos.php">Lançamentos</a></li>
            <li><a href="bancos.php">Bancos</a></li>
            <li><a href="categorias.php">Categorias</a></li>
            <li><a href="buscar.php">Buscar</a></li>
            <li ><a href="#modal-config" class="waves-effect waves-light modal-trigger"><?= ucwords($_SESSION['usuario']);?></a></li>
            <li><i class="material-icons">account_circle</i></li>
            <li><a href="buscar.php"><i class="material-icons">search</i></a></li>
            <li><a href="#modal-config" class="waves-effect waves-light modal-trigger btn-modal"><i
            class="material-icons">view_module</i></a></li>
          </ul>
        </div>
      </nav>
    </div>
    <ul class="sidenav white lighten-2" id="slide-out">
      <li>
        <div class="user-view">
            <div class="background" style="background-color:<?= $_SESSION['temaUser'] == false ? 'rgba(38, 166, 153, 0.69)' : '#303131';?>; height: 100%; width: 100%;"></div>
          <a href="#user"><span class="brand-mark"><i class="material-icons">account_balance_wallet</i></span></a>
          <a href="#name"><span class="white-text name"><?= ucwords($_SESSION['usuario']);?></span></a>
          <a href="#email"><span class="white-text email"><?= $_SESSION['email']; ?></span></a>
        </div>
      </li>
      <li><a href="dashboard.php"><i class="material-icons">dashboard</i>Dashboard</a></li>
      <li><a href="lancamentos.php"><i class="material-icons">equalizer</i>Lançamentos</a></li>
      <li><a href="bancos.php"><i class="material-icons">account_balance</i>Bancos</a></li>
      <li><a href="categorias.php"><i class="material-icons">sell</i>Categorias</a></li>
      <li><a href="#modal-config" class="waves-effect waves-light modal-trigger btn-modal"><i class="material-icons">settings_applications</i>Configurações</a></li>
      <li><a href=""><i class="material-icons">backup</i>Backup</a></li>
      <li>
        <div class="divider"></div>
      </li>
      <li><a class="subheader">Usuario</a></li>
      <li><a class="waves-effect" href="">Dados</a></li>
      <li><a class="waves-effect" href="./login/logout.php">Sair</a></li>
    </ul>
</header>
