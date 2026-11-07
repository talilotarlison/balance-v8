<?php

	// close modal do site
	class Buscar {
		public $busca;
		public function __construct($busca){
		$this->busca = $busca;
		}
	
		public function buscarPorDados() {
		$query = "SELECT * FROM fluxocaixa WHERE nome LIKE '%".$this->busca."%'";
		return $query;
		}

		public function buscarPorDadosDesc() {
			$query = "SELECT * FROM fluxocaixa WHERE nome LIKE '%".$this->busca."%' ORDER BY id DESC";
			return $query;
		}
	}

?>