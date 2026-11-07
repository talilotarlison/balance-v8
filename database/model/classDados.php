<?php

  class Dados {
    public $id;
    public $tipo;
    public $nome;
    public $preco;
    public $descricao;
    public $data;
    public $usuarioId;

    public function __construct($id, $tipo,$nome,$preco,$descricao, $data, $usuarioId = 0) {
      $this->id = $id;
      $this->tipo = $tipo;
      $this->nome = $nome;
      $this->preco = $preco;
      $this->descricao = $descricao;
      $this->data = $data;
      $this->usuarioId = (int) $usuarioId;
    }
    
    public function getNome(){
      return $this -> nome;
    }
    
    public function setNome($nome){
      return $this -> nome = $nome;
    }
  }

  class Update extends Dados{
    public function __construct($id, $tipo,$nome,$preco,$descricao, $data, $usuarioId = 0){
        parent::__construct($id, $tipo,$nome,$preco,$descricao, $data, $usuarioId);
      }

    public function getTipo(){
      return $this -> tipo;
    }

    public function setTipo($tipo){
      return $this -> tipo;
    }

    public function atualizarDados() {
    if ($this->usuarioId < 1) return 'SELECT 1 WHERE 1 = 0';
    $query = "UPDATE fluxocaixa SET tipo='".ucfirst($this->tipo)."', nome='".ucfirst($this->nome)."', preco='".$this->preco."', disc='".ucfirst($this->descricao)."' WHERE id = '".(int) $this->id."' AND usuario_id = '".$this->usuarioId."'";
    return $query;
    }

  }

  class Insert extends Dados{
    public function __construct($id, $tipo,$nome,$preco,$descricao, $data, $usuarioId = 0){
        parent::__construct($id, $tipo,$nome,$preco,$descricao, $data, $usuarioId);
      }

    public function getPreco(){
      return $this -> preco;
    }

    public function setPreco($preco){
      return $this -> preco;
    }

    public function inserirDados() {
        if ($this->usuarioId < 1) return 'SELECT 1 WHERE 1 = 0';
        $query = "INSERT INTO fluxocaixa (usuario_id, tipo, nome, preco, disc, data) VALUES ('".$this->usuarioId."','".ucfirst($this->tipo)."', '".ucfirst($this->nome)."', '".$this->preco."', '".ucfirst($this->descricao)."', '".$this->data."')";
        return $query;
      }


  }

?>  
