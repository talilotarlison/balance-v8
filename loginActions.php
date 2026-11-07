
<?php 
	include('./database/conexao.php');
	include('./database/model/classUser.php'); 
	session_start();

	#Verifica se o usuario ja esta logado
	$credentials = [
		'email' => $_POST['email'],
		'senha' => $_POST['senha']
	];

	if (isset($credentials['email']) && isset($credentials['senha'])) {
		$emailVerificado = mysqli_real_escape_string($conexao, trim($credentials['email']));
		$senhaVerificado = mysqli_real_escape_string($conexao, trim($credentials['senha']));

		$usuario = new SelectUser(
			$emailVerificado, 
			$senhaVerificado
		);
		
		$query = $usuario->selectDadosUser();
		$resultado = mysqli_query($conexao, $query);
		
		// Verifica se encontrou algum registro
		if (mysqli_num_rows($resultado) > 0) {
			unset($_SESSION['erroLogin']);
			echo "Login válido!"; // Usuário encontrado
			
			// coleta dos dados do usuario
			$dadosUserLogin = mysqli_fetch_assoc($resultado);
			$_SESSION['idUser'] = $dadosUserLogin['id'];
			$_SESSION['usuario'] = $dadosUserLogin['nome'];
			$_SESSION['email'] = $dadosUserLogin['email'];
			$_SESSION['temaUser'] = $dadosUserLogin['tema'];
			header('Location: ./pages/dashboard.php');
		} else {
			echo "Login inválido!"; // Usuário não encontrado
			header('Location: ./index.php');
			$_SESSION['erroLogin'] = "Senha ou Email incorreto!";
		}
	}
			
?>

