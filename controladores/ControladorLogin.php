<?php
require_once('modelos/LoginModelo.php');
require_once('repocitorios/LoginRepositorio.php');
 
class ControladorLogin
{

    private $modeloLogin;

    private $LoginRepo;

    public function __construct()
    {
        $this->modeloLogin = new LoginModelo();
        $this->LoginRepo = new LoginRepositorio();
    }

    public function registrarUsuario($datosFormulario)
    {
        $this->modeloLogin->username =$datosFormulario["usuario"];
        $this->modeloLogin->password =$datosFormulario["password"];
        $respuesta = $this->LoginRepo->registrarUsuario($this->modeloLogin);
        echo $respuesta;
    }
    public function IniciarSession($datosFormulario)
    {
        $this->modeloLogin->username =$datosFormulario["usuario"];
        $this->modeloLogin->password =$datosFormulario["password"];
        $respuesta = $this->LoginRepo->Inicio($this->modeloLogin);   
        return $respuesta;
    }

    public function CargoUsuarioInicio()
    {
      session_start();
      $cargo = $_SESSION['cargoI'];
      echo $cargo;
    }
  
    public function UsuarioInicio()
    {
      session_start();
      $usuario = $_SESSION['identificacionI'];
      echo $usuario;
    }
  
    public function cerrarSession(){
      session_start();
      session_destroy();
    }

}

?>