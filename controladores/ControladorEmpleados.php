<?php
session_start();
require_once('modelos/EmpleadosModelo.php');
require_once('repocitorios/EmpleadosRepositorio.php');

class ControladorEmpleados
{

    private $modeloEmpreados;

    private $EmpleadosRepo;


    public function __construct()
    {
        $this->modeloEmpreados = new EmpleadosModelo();
        $this->EmpleadosRepo = new EmpleadosRepositorio();
    }

    public function mostrarEmpleado($datosFormulario)
    {

        $usuario = $_SESSION['identificacionI'];
        $this->modeloEmpreados->identificacion = $usuario;
        $respuesta = $this->EmpleadosRepo->mostrarEmpleado($this->modeloEmpreados);
        return $respuesta;
    }
    public function Cargo($datosFormulario)
    {
        $usuario = $_SESSION['identificacionI'];
        $this->modeloEmpreados->identificacion = $usuario;
        $huella = $this->EmpleadosRepo->mostrarEmpleado($this->modeloEmpreados);

        $this->modeloEmpreados->huella = $huella;
        $this->modeloEmpreados->limit = $datosFormulario["limit"];
        $this->modeloEmpreados->offset = $datosFormulario["offset"];
        $respuesta = $this->EmpleadosRepo->Registro($this->modeloEmpreados);
        return $respuesta;
    }
    public function Registro($datosFormulario)
    {
      
        $Huella = $_SESSION['huellaI'];
        $this->modeloEmpreados->huella = $Huella;
        $this->modeloEmpreados->limit = $datosFormulario["limit"];
        $this->modeloEmpreados->offset = $datosFormulario["offset"];
        $respuesta = $this->EmpleadosRepo->Registro($this->modeloEmpreados);
        return $respuesta;
       
    }
 
    public function mostrarEmpleadoAdmin($datosFormulario)
    {

        $id = $_SESSION['identificacionU'];
        $this->modeloEmpreados->identificacion = $id;
        $respuesta = $this->EmpleadosRepo->mostrarEmpleado($this->modeloEmpreados);
        return $respuesta;
    }

    public function RegistroAdmin($datosFormulario)
    {

        $Huella = $_SESSION['huellaU'];
        $this->modeloEmpreados->huella = $Huella;
        $this->modeloEmpreados->limit = $datosFormulario["limit"];
        $this->modeloEmpreados->offset = $datosFormulario["offset"];
        $respuesta = $this->EmpleadosRepo->Registro($this->modeloEmpreados);
        return $respuesta;
    }
    public function obtenerCargosUnicos()
    {
        $respuesta = $this->EmpleadosRepo->obtenerCargosUnicos();
        return $respuesta;
    }

    public function MostrarEmpleadosCargo($datosFormulario)
    {

        $this->modeloEmpreados->cargo = $_SESSION['cargoU'];
        $this->modeloEmpreados->limit = $datosFormulario["limit"];
        $this->modeloEmpreados->offset = $datosFormulario["offset"];
        $respuesta = $this->EmpleadosRepo->MostrarEmpleadosCargo($this->modeloEmpreados);
        return $respuesta;
    }




    public function vistaTrabajadores($datosFormulario)
    {
        echo "Trabajadores.html";
    }

    public function InformacionUsuario($datosFormulario)
    {
        echo "InformacionUsuario.html";
    }

    public function Hoy($datosFormulario)
    {
        echo "Entradas.html";
    }
    public function setCargo($datosFormulario)
    {
        $_SESSION['cargoU'] = $datosFormulario['cargo'];

        echo $_SESSION['cargoU'];
    }

    public function setHuellaID($datosFormulario)
    {
        $_SESSION['huellaU'] = $datosFormulario['huella'];
        echo $_SESSION['huellaU'];
    }
    public function setIDE($datosFormulario)
    {
        $_SESSION['identificacionU'] = $datosFormulario['identificacion'];
        echo $_SESSION['identificacion'];
    }

 

    public function obtenerCargo($datosFormulario)
    {
        $this->modeloEmpreados->identificacion = $datosFormulario["usuario"];
        $respuesta = $this->EmpleadosRepo->obtenerCargo($this->modeloEmpreados);
        return $respuesta;
    }

    public function obtenerRegistrosAntesDe8AM($datosFormulario){
        $this->modeloEmpreados-> date_time= $datosFormulario["fecha"];
        $obtenerRegistrosAntesDe8AM=$this->EmpleadosRepo->obtenerRegistrosAntesDe8AM($this->modeloEmpreados); 
   return $obtenerRegistrosAntesDe8AM;
}

   public function obtenerRegistrosDespuesDe8AM($datosFormulario){
    $this->modeloEmpreados-> date_time= $datosFormulario["fecha"];
    $obtenerRegistrosDespuesDe8AM=$this->EmpleadosRepo->obtenerRegistrosDespuesDe8AM($this->modeloEmpreados); 
    return $obtenerRegistrosDespuesDe8AM;
}

}
?>
