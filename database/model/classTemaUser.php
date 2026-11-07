<?php
  // Class tema do usuário
  // @author Talilo Tarlison
  class TemaUser{
    public $id;
    public $tema;

    public function __construct($id,$tema){
      $this->id = $id;
      $this->tema = $tema;
    }

    public function mudarTemaUser() {
      $query = "UPDATE login SET tema='".$this->tema."' WHERE id = '".$this->id. "'";
      return $query;
    }
  }

?>  