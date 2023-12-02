<?php
// Configuración de CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header("HTTP/1.1 200 OK");
    exit();
}

if (isset($_POST['controlador'])) {
    $controller = $_POST['controlador'];
    $accion = $_POST['accion'];

    require 'controladores/Controlador' . $controller . '.php';

    $clase = 'Controlador' . $controller;

    $controlador = new $clase();
    $resultado = $controlador->$accion($_POST); // Pasar los datos del formulario al método accion()

    if (is_array($resultado)) {
        echo json_encode($resultado); // Devolver la respuesta al AJAX)    
    } elseif (is_numeric($resultado)) {
        echo $resultado; // Devolver la respuesta al AJAX
    } else{
        echo "Error en el servidor: No se pudo completar la acción solicitada.";
    }
} elseif (isset($_GET['controlador'])) {
    $controller = $_GET['controlador'];
    $accion = $_GET['accion'];

    // Agrega la siguiente línea para imprimir la ruta construida
    echo 'Ruta para GET: ' . 'controladores/Controlador' . $controller . '.php<br>';

    require 'controladores/' . 'Controlador' . $controller . '.php';

    $clase = 'Controlador' . $controller;

    $controlador = new $clase();
    $controlador->$accion();
}
?>
