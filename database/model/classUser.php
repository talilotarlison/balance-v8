<?php

  class DadosUser {
    public $id;
    public $nome;
    public $email;
    public $senha;
    public $data;

    public function __construct($id, $nome,$email,$senha, $data) {
      $this->id = $id;
      $this->nome = $nome;
      $this->email = $email;
      $this->senha = $senha;
      $this->data = $data;
    }
    
    public function getNome(){
      return $this -> nome;
    }
    
    public function setNome($nome){
      return $this -> nome = $nome;
    }
  }

  class UpdateUser extends DadosUser{
    public function __construct($id, $nome,$email,$senha, $data){
        parent::__construct($id, $nome,$email,$senha, $data);
      }

    public function getEmail(){
      return $this -> email;
    }

    public function setEmail($email){
      return $this -> email;
    }

    public function atualizarDadosUser() {
    $query = "UPDATE login SET nome='".$this->nome."', email='".$this->email."', senha='".$this->senha."' WHERE id = '".$this->id. "'";
    return $query;
    }

  }

  class InsertUser extends DadosUser{
    public function __construct($id, $nome,$email,$senha, $data){
        parent::__construct($id, $nome,$email,$senha, $data);
      }

    public function getData(){
      return $this -> data;
    }

    public function setData($data){
      return $this -> data;
    }

    public function inserirDadosUser() {
        $query = "INSERT INTO login VALUES(null,'".$this->nome."', '".$this->email."', '".md5($this->senha)."', '".$this->data."',false)";
        return $query;
      }

  }

  class SelectUser{
    public $email;
    public $senha;

    public function __construct($email,$senha){
      $this->email = $email;
      $this->senha = $senha;
    }

    public function getEmail(){
    return $this -> email;
    }

    public function setEmail($email){
      return $this -> email;
    }

    public function selectDadosUser() {
      $query = "SELECT * FROM login WHERE email = '".$this->email."' AND senha = '".md5($this->senha)."'";
      return $query;
    }

  }

?>