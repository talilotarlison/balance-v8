
<?php 
	// sessao de usuario
	session_start();
	include('../database/conexao.php');

	if (empty($_SESSION['idUser'])) {
		header('Location: ../index.php');
		exit;
	}

	$novoTema = ((int) ($_SESSION['temaUser'] ?? 0) === 0) ? 1 : 0;
	$idUsuario = (int) $_SESSION['idUser'];
	$stmt = $conexao->prepare('UPDATE login SET tema = ? WHERE id = ?');
	$stmt->bind_param('ii', $novoTema, $idUsuario);

	if ($stmt->execute() && $stmt->affected_rows >= 0) {
		$_SESSION['temaUser'] = $novoTema;
		$_SESSION['mensagem'] = $novoTema === 1 ? 'Tema escuro ativado.' : 'Tema claro ativado.';
	} else {
		$_SESSION['mensagem'] = 'Não foi possível salvar a preferência de tema.';
	}

	$destino = $_SERVER['HTTP_REFERER'] ?? 'dashboard.php';
	$caminhoDestino = parse_url($destino, PHP_URL_PATH);
	$destinoLocal = is_string($caminhoDestino) ? basename($caminhoDestino) : 'dashboard.php';
	$paginasPermitidas = ['dashboard.php', 'lancamentos.php', 'buscar.php', 'atualizar.php', 'bancos.php', 'categorias.php'];
	if (!in_array($destinoLocal, $paginasPermitidas, true)) {
		$destinoLocal = 'dashboard.php';
	}
	header('Location: ' . $destinoLocal);
	exit;
?>

