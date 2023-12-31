<?php
session_start();
if($_SESSION['identificacionI']){
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido</title>

    <style>
     body {
        margin: 0;
        font-family: Arial, sans-serif;
        background-color: #575a61c0;
    }
    .contenedor-principal {
        display: flex;
        justify-content: space-between;
        margin: 5px;
    }

    #izquierdo {
        width: 300px;
        max-width: 300px; /* Establece un ancho máximo */
        box-sizing: border-box;
        padding: 20px;
    }

    #derecho {
        flex: 1;
        width: 65%; /* Puedes ajustar el ancho según tus necesidades */
        box-sizing: border-box;
        padding: 5px;
    }
    .containerbienvenida {
        max-width: 300px;
        margin: 20px auto;
        text-align: center;
        background-color: #fff;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .izquierdo-container {
        float: left;
        width: 300px;
        /* Ancho deseado del contenedor izquierdo */
        border-right: 1px solid #ddd;
        
        /* Borde para separar visualmente del contenedor derecho */
    }

    #registrosContainer {
        display: grid;
        flex-wrap: wrap;
        grid-template-columns: repeat(3, 1fr);
        /* Permite que los elementos se envuelvan en una nueva línea si no caben */
        justify-content: space-between;
        /* Espacio entre los elementos */
    }
    .tarjeta-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
    }

    .tarjeta {
        width: 130px;
        box-sizing: border-box;
        border: 2px solid blue;
        padding: 5px;
        margin: 10px;
        border-radius: 5px;
        background-color: #c8c7c7;
    }

    .tarjeta img {
       
        width: 80%;
        height: auto;
        border-radius: 5px;
    }

   
    #paginacionContainer button {
        background-color: #3498db;
        color: #fff;
        padding: 10px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    #paginacionContainer button:hover {
        background-color: #2980b9;
    }

    /* Estilos específicos para la nueva vista */
.nueva-vista .regresar {
    /* Ajusta los estilos según sea necesario */
    font-size: 14px;
    padding: 8px 16px;
}

.contenedor-principal {
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    align-items: flex-start; /* Alinea los elementos en la parte superior */
}

.izquierdo-container {
    width: 300px;
   
}

@media (max-width: 750px){
    html{
        font-size: 12px;
    }

    containerbienvenida{
        transform: scale(0.8);
    }

    #registrosContainer{
        display: grid;
        grid-template-columns: repeat(2, 1fr);
    }
}
@media (max-width: 580px) {
            html {
                font-size: 10px;
            }

            button, select, #datepicker{
                transform: scale(0.8) !important;
                font-size: 11px;
            }

            containerbienvenida{
                transform: scale(0.6);
            }

    .izquierdo-container{
        border-right: none;
        border-bottom: 1px solid #ddd
    }

    .contenedor-principal{
        display: flex;
        flex-direction: column;
        align-items: start;
    }
            
        }


</style>

</head>

<body>

    <div class="contenedor-principal">
        
        <div id="izquierdo" class="izquierdo-container">
            <div class="nueva-vista">
                <button class="regresar" onclick="IrATrabajadores()">Regresar a trabajadores</button>
            </div>

            <div class="containerbienvenida">
                <h1>Empleado</h1>              
                <div id="empleadosContainer"></div>
                
            </div>
        </div>
   
        <div id="derecho" class="derecho-container">         
          <h1>Registros de Asistencia</h1>
            <!-- Agrega estos elementos para mostrar los controles de paginación -->
            <div id="paginacionContainer" class="paginacion-container">
                <button id="anterior">Anterior</button>
                <span id="paginaActual">Página 1</span>
                <button id="siguiente">Siguiente</button>
            </div>
    
            <div id="registrosContainer" class="catalog-container"></div>
        </div>
    </div>


    <script src="librerias/jquery-3.6.3.min.js"></script>

    <script>

        function MostrarEmpleadoID() {
            var accion = "mostrarEmpleadoAdmin";
            $.ajax({
                url: 'router.php',
                method: 'POST',
                data: {
                    controlador: 'Empleados',
                    accion: accion,
                },
                success: function (response) {

                    if (response && response.length > 0) {
                        var datos = JSON.parse(response);
                        mostrarEmpleado(datos);
                        console.log(datos);
                    } else {
                        console.log("No se encontraron datos para el ID proporcionado");
                    }
                },
                error: function (error) {
                    console.error(error);
                }
            });
        }

       
        MostrarEmpleadoID();
        function mostrarEmpleado(datos) {
            var empleadosContainer = $("#empleadosContainer");
            var logoContainer = document.getElementById("EmpleadoImg");
         
            var empleadoItem = $("<div class='empleado-item'></div>");
            empleadosContainer.empty();
            empleadoItem.html("<p>ID: " + datos[0].identificacion + "</p>" +
                  "<p>Nombre: " + datos[0].nombre + "</p>" +
                  "<p>Cargo: " + datos[0].cargo + "</p>");
            empleadosContainer.append(empleadoItem);
        }
        function RegistroEmpleado() {
            var accion = "RegistroAdmin";
            $.ajax({
                url: 'router.php',
                method: 'POST',
                data: {
                    controlador: 'Empleados',
                    huella: huella,
                    accion: accion,
                },
                success: function (response) {
                    console.log(response);
                    var datos = JSON.parse(response);
                    mostrarRegistros(datos, 3);
                },
                error: function (error) {
                    console.error(error);
                }
            });
        }




        $(document).ready(function () {
            var paginaActual = 1;
            var resultadosPorPagina = 10;        
            function cargarRegistros() {
                var accion = "RegistroAdmin";
                $.ajax({
                    url: 'router.php',
                    method: 'POST',
                    data: {
                        controlador: 'Empleados',
                        accion: accion,
                        limit: resultadosPorPagina,
                        offset: (paginaActual - 1) * resultadosPorPagina
                    },
                    success: function (response) {
                        var datos = JSON.parse(response);
                        mostrarRegistros(datos);
                        actualizarControlesPaginacion();
                    },
                    error: function (error) {
                        console.error(error);
                    }
                });
            }

            function mostrarRegistros(datos) {
                var registrosContainer = $("#registrosContainer");
                registrosContainer.empty();

                for (var i = datos.length - 1; i >= 0; i--) {
                    var registro = datos[i];
                    var tarjeta = $("<div class='tarjeta'>" +
                        "<img src='" + "img/logo.png" + "' alt='Foto' class='foto'>" +
                        "<p>ID: " + registro.identificacion + "</p>" +
                        "<p>Nombre: " + registro.nombre + "</p>" +
                        "<p>Fecha y Hora: " + registro.date_time + "</p>" +
                        "<p>Tipo de Acceso: " + registro.access_type + "</p>" +
                        "</div>");

                    registrosContainer.append(tarjeta);
                }
            }
            // Función para actualizar los controles de paginación
            function actualizarControlesPaginacion() {
                $("#paginaActual").text("Página " + paginaActual);
            }
            // Evento al hacer clic en "Siguiente"
            $("#siguiente").on("click", function () {
                paginaActual++;
                cargarRegistros();
            });   

            // Evento al hacer clic en "Anterior"
            $("#anterior").on("click", function () {
                if (paginaActual > 1) {
                    paginaActual--;
                    cargarRegistros();
                }
            });

            cargarRegistros();
        });

       
        function IrATrabajadores(seleccion) {         
                    var controlador = "Empleados";
                    var accion = "vistaTrabajadores";
                    $.ajax({
                        url: 'router.php', // Ruta del archivo PHP que contiene la función Ajax
                        method: 'POST',
                        data: { controlador: controlador, accion: accion }, // Datos opcionales que deseas enviar al archivo PHP
                        success: function (response) {
                            loadContent('./Trabajadores.php');
                        },
                        error: function (xhr, status, error) {
                            console.error('Error en la solicitud Ajax:', error);
                        }
                    });
                

            }

            function loadContent(page) {
                $.ajax({
                    url: page, // Ruta del archivo HTML o PHP que contiene el contenido
                    method: 'GET',
                    success: function (data) {
                        // Actualizar el contenido principal con el contenido cargado
                        $('#contenido').html(data);
                    },
                    error: function (xhr, status, error) {
                        console.error('Error al cargar el contenido:', error);
                    }
                });
            }
    </script>


</body>

</html>
<?php
} else{
    header('Location: ./InicioSesion.html');
}
