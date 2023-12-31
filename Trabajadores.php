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
    <link rel="stylesheet" href="librerias/bootstrap.min.css">
    <style>
        .trabajadores {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #575a61c0;
        }

        .contenedor-principal {
            display: flex;
            justify-content: space-between;
        }

        #izquierdo {
            width: 300px;
            max-width: 300px;
            /* Establece un ancho máximo */
            box-sizing: border-box;
            padding: 20px;
        }

        #derecho {
            flex: 1;
            width: 65%;
            /* Puedes ajustar el ancho según tus necesidades */
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
            display: flex;
            flex-wrap: wrap;
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
            width: calc(20% - 20px);
            box-sizing: border-box;
            border: 2px solid blue;
            padding: 5px;
            margin-bottom: 5px;
            border-radius: 5px;
            background-color: #c8c7c7;
        }

        .tarjeta img {

            width: 80%;
            height: auto;
            border-radius: 5px;
        }



        a:hover {
            background-color: #2980b9;
        }

        #paginacionContainer button {
            background-color: #3498db;
            margin: 5px;
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

        #tablaRegistros {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        #tablaRegistros th,
        #tablaRegistros td {
            border: 1px solid #3498db;
            padding: 8px;
            text-align: left;
        }

        #tablaRegistros th {
            background-color: #3498db;
            color: #fff;
        }

        #tablaRegistros td {
            background-color: #ecf0f1;
        }

        #registrostablaRegistros tr:hover {
            background-color: #d4e6f1;
        }

        #registrostablaRegistros a {
            color: #3498db;
            text-decoration: none;
        }


        .tabla-grande {
            width: 80%;
        }


        #paginacionContainer {
            margin-top: 20px;
            /* Ajuste del margen superior */
        }

        .table{
            margin: 5px;
        }

        button {
            background-color: #333;
            color: white !important;
            border-radius: 15px;
            transition: .5s;
            margin: 3px !important;
        }
        
        button:hover {
            cursor: pointer;
            transform: scale(1.1);
        }

        .sidebar{
            align-items: center;
        }

        @media (max-width: 750px){
            html{
                font-size: 10px;
            }
            #anterior, #paginaActual, #siguiente{
                transform: scale(0.8);
                font-size: 10px;
            }

            .table{
                font-size: 10px;
            }

            button, select{
                transform: scale(0.85) !important;
            }
        }

        @media (max-width: 550px){
            #anterior, #paginaActual, #siguiente{
                transform: scale(0.6);
                font-size: 8px;
            }

            .table{
                font-size: 8px;
            }
        }
    </style>

</head>

<body class="trabajadores">

    <div class="contenedor-principal">

        <div id="" class="">

            <div id="registrosContainer" class="catalog-container">
                <div id="paginacionContainer" class="paginacion-container">
                    <button id="anterior">Anterior</button>
                    <span id="paginaActual">Página 1</span>
                    <button id="siguiente">Siguiente</button>
                </div>
            </div>
            <table id="tablaRegistros" class="table table-bordered table-hover">
                <thead class="table-primary">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Cargo</th>
                        <th>Ver Informacion</th>
                    </tr>
                </thead>
                <tbody id="registrostablaRegistros"></tbody>
            </table>
        </div>
    </div>


    <script src="librerias/jquery-3.6.3.min.js"></script>

    <script>



        $(document).ready(function () {


            var paginaActual = 1;
            var resultadosPorPagina = 10;

            function cargarRegistros() {
                // var cargo =Cargo;
                var accion = "MostrarEmpleadosCargo";

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
                        var tabla = document.getElementById("registrostablaRegistros");
                        tabla.innerHTML = "";
                        mostrarRegistros(datos);
                        actualizarControlesPaginacion();
                    },
                    error: function (error) {
                        console.error(error);
                    }
                });
            }

            function mostrarRegistros(datos) {

                var tabla = document.getElementById("registrostablaRegistros");

                for (var i = 0; i < datos.length; i++) {
                    var fila = document.createElement("tr");

                    var celdaidentificacion = document.createElement("td");
                    celdaidentificacion.textContent = datos[i].identificacion;
                    fila.appendChild(celdaidentificacion);
                    // celdaNombrFacultad.style.color = "white";
                    celdaidentificacion.style.textShadow = "2px 2px 4px rgba(0, 0, 0, 2)";

                    var celdanombre = document.createElement("td");
                    celdanombre.textContent = datos[i].nombre;
                    fila.appendChild(celdanombre);
                    //  celdaDescripcion.style.color = "white";
                    celdanombre.style.textShadow = "2px 2px 4px rgba(0, 0, 0, 2)";

                    var celdacargo = document.createElement("td");
                    celdacargo.textContent = datos[i].cargo;
                    fila.appendChild(celdacargo);
                    //celdaNombreSede.style.color = "white";
                    celdacargo.style.textShadow = "2px 2px 4px rgba(0, 0, 0, 2)";

                    var celdaAcciones = document.createElement("td");

                    var botonhuella = document.createElement("button");
                    botonhuella.className = "btn-ver";
                    botonhuella.type = "button";
                    botonhuella.textContent = "Ver";
                    botonhuella.addEventListener("click", VerInfo.bind(null, datos[i].huella, datos[i].identificacion));
                    celdaAcciones.appendChild(botonhuella);
                    fila.appendChild(celdaAcciones)
                    tabla.appendChild(fila);
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


            function guardarHID(huella) {
                var controlador = "Empleados";
                var accion = "setHuellaID";
                $.ajax({
                    url: 'router.php',
                    method: 'POST',
                    data: { controlador: controlador, accion: accion, huella: huella},
                    success: function (response) {
                        console.log(response);       
                    },
                    error: function (xhr, status, error) {
                        console.error('Error en la solicitud Ajax:', error);
                    }
                });
            }
            function guardarID(identificacion) {
                var controlador = "Empleados";
                var accion = "setIDE";
                $.ajax({
                    url: 'router.php',
                    method: 'POST',
                    data: { controlador: controlador, accion: accion,identificacion: identificacion },
                    success: function (response) {
                        console.log(response);       
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
            function VerInfo(huella,identificacion) {
                guardarHID(huella);
                guardarID(identificacion);
                var controlador = "Empleados";
                var accion = "InformacionUsuario";
                $.ajax({
                    url: 'router.php', // Ruta del archivo PHP que contiene la función Ajax
                    method: 'POST',
                    data: { controlador: controlador, accion: accion}, // Datos opcionales que deseas enviar al archivo PHP
                    success: function (response) {
                        loadContent('./InformacionUsuario.php'); 
                    },
                    error: function (xhr, status, error) {
                        console.error('Error en la solicitud Ajax:', error);
                    }
                });

            }

            cargarRegistros();
        });





    </script>


</body>

</html>
<?php
} else{
    header('Location: ./InicioSesion.html');
}
?>
