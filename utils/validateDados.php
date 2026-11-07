<?php
	// Função genérica para validar dados
	function validateData(array $data, object $conexao): array {
		$validatedData = [];
		foreach ($data as $key => $value) {
			$validatedData[$key] = validaDadoEntrada($conexao, $value);
		}
		return $validatedData;
	}

	// Validar dados do usuário
	function validateUser(array $data, object $conexao): array {
		return validateData($data, $conexao);
	}

	// valida cada dado de entrada
	function validaDadoEntrada(object $conexao, mixed $dado):mixed{
		$dadoVerificado = mysqli_real_escape_string($conexao, trim($dado));// limpar dados malicioso da string e evita sql injection
		$dadoVerificado = htmlspecialchars($dadoVerificado);// evita xss enviado pelo usuario
		return $dadoVerificado;
	}

?>