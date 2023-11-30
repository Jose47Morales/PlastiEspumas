<?php
require_once('Acceso_Datos/dao.php');

class RegistrosRepositorio
{

  private $dao;

  public function __construct()
  {
    $this->dao = new MiDAO();
  }


  public function obtenerFechasAcceso($modelo)
  { 
    $result = $this->dao-> obtenerDatosAcceso($modelo->año,$modelo->mes,$modelo->dia);
    return $result;
  }

  
}

?>