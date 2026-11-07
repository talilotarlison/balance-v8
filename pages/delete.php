<?php
	// sessao de usuario 
	session_start();

	if (!isset($_SESSION['usuario'])) {
		header('Location: index.php');
	} 
	
	// Incluir arquivos
	// Conexão com o banco de dados
	include('../database/conexao.php');
	include('../database/model/classDelete.php'); 
	
	// delete no banco de dados
	// Verifica se o ID foi passado na URL
	if (isset($_GET['id'])) {
		$id = mysqli_real_escape_string($conexao, trim($_GET['id']));
		$meusDados = new Delete($id);
		$query = $meusDados->deletaDados();

		if (mysqli_query($conexao, $query)) {
			$_SESSION['mensagem'] = "Deletado com sucesso!";
		} else {
			$_SESSION['mensagem'] = "Erro ao deletar: " . mysqli_error($conexao);
		}
	} else {
		$_SESSION['mensagem'] = "ID inválido ou não fornecido!";
	}

	header('Location: lancamentos.php');
	exit;
?>
