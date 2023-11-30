<?php
require_once('Acceso_Datos/dao.php');

class LoginRepositorio
{

  private $dao;

  public function __construct()
  {
    $this->dao = new MiDAO();
  }


  public function registrarUsuario($modelo)
  {
    $result = $this->dao->registrarUsuario($modelo->username, $modelo->password);
    return $result;
  }

  public function Inicio($modelo)
  {
    $result = $this->dao->verificarCredenciales($modelo->username, $modelo->password);
    return $result;
  }

}

?>