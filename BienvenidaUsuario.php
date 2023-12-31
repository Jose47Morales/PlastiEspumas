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
        background-color: #07267dc0;
    }
    .contenedor-principal {
        display: flex;
        justify-content: space-between;
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
        /* Permite que los elementos se envuelvan en una nueva línea si no caben */
        grid-template-columns: repeat(4, 1fr);
        /* Espacio entre los elementos */
    }
    .tarjeta-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
    }

    .tarjeta {
        width: 110px;
        box-sizing: border-box;
        border: 2px solid blue;
        padding: 5px;
        margin: 10px;
        margin-bottom: 5px;
        border-radius: 5px;
        background-color: #c8c7c7;
    }

    .tarjeta img {
       
        width: 80%;
        height: auto;
        border-radius: 5px;
    }

    a {
        display: block;
        margin-top: 20px;
        padding: 10px;
        background-color: #3498db;
        color: #fff;
        text-decoration: none;
        border-radius: 5px;
        transition: background-color 0.3s ease;
    }

    a:hover {
        background-color: #2980b9;
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

    #logo{
        width: 150px;
    }

    @media (max-width: 750px){
        html{
            font-size: 12px;
        }

        .containerbienvenida{
            transform: scale(0.9);
        }

        #registrosContainer {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 550px){
        html{
            font-size: 10px;
        }

        .conatinerbienvenida{
            transform: scale(0.8);
        }

        #registrosContainer {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        }
    }
</style>

</head>

<body>

    <div class="contenedor-principal">

        <div id="izquierdo" class="izquierdo-container">
            <div class="containerbienvenida">
                <h1>Bienvenido a tu Página Personal</h1>
                <div id="empleadosContainer"></div>
                <button id="anterior" onclick="CerrarSession()">Cerrar Session</button>
              
            </div>
        </div>
   
        <div id="derecho" class="derecho-container">
            <div id="logoContainer"> <img src="img/logo.png" alt="Logo" id="logo"></div><h1>Registros de Asistencia</h1>
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
            var accion = "mostrarEmpleado";
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
           // var fotoPrimeraPersona = datos[0].foto;
           // if (fotoPrimeraPersona) {
             //   var imagen = document.createElement("img");
             //   imagen.src = fotoPrimeraPersona;
             //   imagen.width = 200;
            //    imagen.height = 200;
            //    logoContainer.appendChild(imagen);
           // } else {
          // Si no hay foto disponible, puedes mostrar un mensaje o dejar el contenedor vacío
          //      console.log("No hay foto disponible");
          //  }

            var empleadoItem = $("<div class='empleado-item'></div>");
            empleadosContainer.empty();
            empleadoItem.html("<p>ID: " + datos[0].identificacion + "</p>" +
                  "<p>Nombre: " + datos[0].nombre + "</p>" +
                  "<p>Cargo: " + datos[0].cargo + "</p>");
            empleadosContainer.append(empleadoItem);
        }
        function RegistroEmpleado(Huella) {
            var huella = Huella;
            var accion = "Registro";
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
                var accion = "Registro";
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

                for (var i = 0; i < datos.length; i++) {
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

      function CerrarSession(){
        var accion = "cerrarSession";
                $.ajax({
                    url: 'router.php',
                    method: 'POST',
                    data: {
                        controlador: 'Login',
                        accion: accion,                       
                    },
                    success: function (response) {
                     window.location.href = "index.html"; 
                    },
                    error: function (error) {
                        console.error(error);
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
