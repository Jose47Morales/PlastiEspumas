<?php
require_once('Acceso_Datos/dao.php');

class EmpleadosRepositorio
{

  private $dao;

  public function __construct()
  { 
    $this->dao = new MiDAO();
  }



public function mostrarEmpleado($id){ 
    $result = $this->dao->mostrarEmpleado($id->identificacion);   
    return $result;
}

public function Registro($modelo){ 
    $result = $this->dao->Registro($modelo->huella,$modelo->limit,$modelo->offset);  
    return $result;
}

public function obtenerCargosUnicos(){ 
  $result = $this->dao->obtenerCargosUnicos();  
  return $result;
}

public function obtenerCargo($id){ 
  $result = $this->dao->obtenerCargo($id->identificacion);  
  return $result;
}
public function MostrarEmpleadosCargo($modelo){ 
  $result = $this->dao->MostrarEmpleadosCargo($modelo->cargo,$modelo->limit,$modelo->offset);  
  return $result;
}

public function mostrarEmpleadoHuella($id){
$result =$this->mostrarEmpleadoHuella($id->identificacion);  
return  $result;
}

public function obtenerRegistrosAntesDe8AM($id){
  $result =$this->dao->obtenerRegistrosAntesDe8AM($id->date_time);  
  return  $result;
  }

  public function obtenerRegistrosDespuesDe8AM($id){
    $result =$this->dao->obtenerRegistrosDespuesDe8AM($id->date_time);  
    return  $result;
    }
}

?>