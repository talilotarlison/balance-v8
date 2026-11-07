<?php

  class SelectAll{

    public function selectDadosAll() {
      $query = "SELECT * FROM fluxocaixa";
      return $query;
    }

    public function selectDadosDesc() {
      $query = "SELECT * FROM fluxocaixa ORDER BY id DESC";
      return $query;
    }

    public function selectUltimosCinco() {
      $query = "SELECT id, tipo, nome, preco, disc, data FROM fluxocaixa ORDER BY id DESC LIMIT 5";
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
    
    public function selectDadosId() {
      $query = "SELECT * FROM fluxocaixa where id=".$this->id;
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

    public function selectDadosTipo() {
      $query = "SELECT * FROM fluxocaixa where tipo='".$this->tipo."'";
      return $query;
    }

  }

?>
	
