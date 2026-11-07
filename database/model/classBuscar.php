<?php

	// close modal do site
	class Buscar {
		public $busca;
		public $usuarioId;
		public function __construct($busca, $usuarioId = 0){
		$this->busca = $busca;
		$this->usuarioId = (int) $usuarioId;
		}
	
		public function buscarPorDados() {
		$query = "SELECT * FROM fluxocaixa WHERE usuario_id=".$this->usuarioId." AND nome LIKE '%".addslashes($this->busca)."%'";
		return $query;
		}

		public function buscarPorDadosDesc() {
			$query = "SELECT * FROM fluxocaixa WHERE usuario_id=".$this->usuarioId." AND nome LIKE '%".addslashes($this->busca)."%' ORDER BY id DESC";
			return $query;
		}
	}

?>
