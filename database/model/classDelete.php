<?php

class Delete {
  public $id;
  public $usuarioId;

  public function __construct($id, $usuarioId = 0) {
    $this->id = $id;
    $this->usuarioId = (int) $usuarioId;
  }
  
  public function getId(){
    return $this -> id;
  }
  
  public function setId($id){
    return $this -> id = $id;
  }

  public function deletaDados() {
    if ($this->usuarioId < 1) return 'SELECT 1 WHERE 1 = 0';
    $query = "DELETE FROM fluxocaixa WHERE id=".(int) $this->id." AND usuario_id=".$this->usuarioId;
    return $query;
  }
  
}

?>
