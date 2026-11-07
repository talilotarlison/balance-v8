<?php

class Delete {
  public $id;

  public function __construct($id) {
    $this->id = $id;
  }
  
  public function getId(){
    return $this -> id;
  }
  
  public function setId($id){
    return $this -> id = $id;
  }

  public function deletaDados() {
    $query = "DELETE FROM fluxocaixa where id=".$this->id;
    return $query;
  }
  
}

?>