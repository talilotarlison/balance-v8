<?php

  class Dados {
    public $id;
    public $tipo;
    public $nome;
    public $preco;
    public $descricao;
    public $data;

    public function __construct($id, $tipo,$nome,$preco,$descricao, $data) {
      $this->id = $id;
      $this->tipo = $tipo;
      $this->nome = $nome;
      $this->preco = $preco;
      $this->descricao = $descricao;
      $this->data = $data;
    }
    
    public function getNome(){
      return $this -> nome;
    }
    
    public function setNome($nome){
      return $this -> nome = $nome;
    }
  }

  class Update extends Dados{
    public function __construct($id, $tipo,$nome,$preco,$descricao, $data){
        parent::__construct($id, $tipo,$nome,$preco,$descricao, $data);
      }

    public function getTipo(){
      return $this -> tipo;
    }

    public function setTipo($tipo){
      return $this -> tipo;
    }

    public function atualizarDados() {
    $query = "UPDATE fluxocaixa SET tipo='".ucfirst($this->tipo)."', nome='".ucfirst($this->nome)."', preco='".$this->preco."', disc='".ucfirst($this->descricao)."' WHERE id = '".$this->id. "'";
    return $query;
    }

  }

  class Insert extends Dados{
    public function __construct($id, $tipo,$nome,$preco,$descricao, $data){
        parent::__construct($id, $tipo,$nome,$preco,$descricao, $data);
      }

    public function getPreco(){
      return $this -> preco;
    }

    public function setPreco($preco){
      return $this -> preco;
    }

    public function inserirDados() {
        $query = "INSERT INTO fluxocaixa VALUES(null,'".ucfirst($this->tipo)."', '".ucfirst($this->nome)."', '".$this->preco."', '".ucfirst($this->descricao)."', '".$this->data."')";
        return $query;
      }


  }

?>  