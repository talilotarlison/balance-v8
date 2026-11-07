<?php

  class SelectAll{

    public function selectDadosAll($usuarioId) {
      $query = "SELECT * FROM fluxocaixa WHERE usuario_id=" . (int) $usuarioId;
      return $query;
    }

    public function selectDadosDesc($usuarioId) {
      $query = "SELECT * FROM fluxocaixa WHERE usuario_id=" . (int) $usuarioId . " ORDER BY id DESC";
      return $query;
    }

    public function selectUltimosCinco($usuarioId) {
      $query = "SELECT id, tipo, nome, preco, disc, data FROM fluxocaixa WHERE usuario_id=" . (int) $usuarioId . " ORDER BY id DESC LIMIT 5";
      return $query;
    }

  }

  class SelectId {
    public $id;

    public function __construct($id) {
      $this->id = $id;
    }
    
    public function getId(){
      return $this -> tipo;
    }
    
    public function setId($id){
      return $this -> tipo = $tipo;
    }
    
    public function selectDadosId($usuarioId) {
      $query = "SELECT * FROM fluxocaixa WHERE id=" . (int) $this->id . " AND usuario_id=" . (int) $usuarioId;
      return $query;
    }
  }

  class selectTipo extends SelectId{
    public $tipo;
    public function __construct($id, $tipo){
        parent::__construct($id);
              $this->tipo = $tipo;
      }

    public function getTipo(){
      return $this -> tipo;
    }
    
    public function setTipo($tipo){
      return $this -> tipo = $tipo;
    }

    public function selectDadosTipo($usuarioId) {
      $tipoSeguro = addslashes($this->tipo);
      $query = "SELECT * FROM fluxocaixa WHERE usuario_id=" . (int) $usuarioId . " AND tipo='" . $tipoSeguro . "'";
      return $query;
    }

  }

?>
	
