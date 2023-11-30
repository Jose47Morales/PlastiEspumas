<?php
require_once('modelos/LogsModelo.php');
require_once('repocitorios/RegistrosRepositorio.php');
 
class ControladorRegistros
{

    private $modeloRegistro;
  
    private $RegistroRepo;

    public function __construct()
    {
        $this->modeloRegistro = new LogsModelo();
        $this->RegistroRepo = new RegistrosRepositorio();
    }
    
    public function obtenerFechasAcceso($datosFormulario)
    {   
        $this->modeloRegistro->año=$datosFormulario['año'] ;
        $this->modeloRegistro-> mes =$datosFormulario["mes"];
        $this->modeloRegistro-> dia =$datosFormulario["dia"];
        $respuesta = $this->RegistroRepo->obtenerFechasAcceso($this->modeloRegistro);
        return $respuesta;
    }

}

?>