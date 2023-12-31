<?php
session_start();
 if($_SESSION['identificacionI']){
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página con Barra de Navegación</title>
    <link rel="stylesheet" href="css/Admin.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <style>
        /* styles.css */
        .catalog-container {
            display: flex;
        }

        .catalog-item {
            border: 1px solid #ccc;
            margin: 10px;
            padding: 10px;
        }

        .catalog-item h2 {
            margin-bottom: 5px;
        }

        .catalog-item p {
            margin: 5px 0;
        }

        select, option{
            color: black !important;
        }

        .sidebar {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: start;
            text-align: center;
            gap: 3px;
            max-width: 145px;
        }
        
        #datepicker{
            width: 130px;    
        }
     
        #seleccionOpciones,
        .sidebar button {
            max-width: 135px;
            margin: 3px !important;
        }

     .nav-container{
      min-height: 70px;
     }

     #informacion{
        display: grid;
        grid-template-columns: repeat(4, 1fr);
    }
     }
    </style>
</head>

<body>

    <header>
        <h1>Área Administrativa </h1>
    </header>

    <nav class="nav-container">
        <div id="Admin"> </div>
        <button class="link" onclick="CerrarSession()">Cerrar la Sesión</button>
    </nav>

    <div class="contenAdmin">


        <div class="sidebar">
            <button class="link" onclick="entradahoy()">Entradas de hoy</button>
            <select id="seleccionOpciones" >
                <option></option>
            </select>
            <br>
            <input type="text" id="datepicker">
        </div>

        <div id="contenido">

        </div>

    </div>
    <div id="informacion" class="catalog-container">
        <!-- Aquí se mostrará la información dinámicamente -->
    </div>

    <script src="librerias/jquery-3.6.3.min.js"></script>
    <script src="librerias/sweetalert2@10.js"></script>
    <script>



        $(document).ready(function () {
            // Petición para llenar el select de Sede
            $.post("router.php", { controlador: "Empleados", accion: "obtenerCargosUnicos" }, function (data) {
                var datos = JSON.parse(data);


                var selectcargo = $("#seleccionOpciones");
                selectcargo.empty(); // Limpiar opciones antes de agregar nuevas

                var option = $('<option></option>').attr('value', "").text("Cargo");
                selectcargo.append(option);
                $.each(datos, function (index, value) {
                    selectcargo.append($("<option>").text(value).val(value));
                });

                selectcargo.val("").prop('selected', true);

                // Deshabilitar la opción seleccionada
                selectcargo.find('option[value=""]').prop('disabled', true);

                selectcargo.on('change', function () {
                    // Obtener el texto seleccionado
                    var seleccion = $(this).find(':selected').text();

                    guardarCargo(seleccion);
                    tuFuncionAlSeleccionar(seleccion);
                });

            });

            function tuFuncionAlSeleccionar(seleccion) {
                if (seleccion == "Cargo") {

                } else {

                    var controlador = "Empleados";
                    var accion = "vistaTrabajadores";
                    $.ajax({
                        url: 'router.php', // Ruta del archivo PHP que contiene la función Ajax
                        method: 'POST',
                        data: { controlador: controlador, accion: accion }, // Datos opcionales que deseas enviar al archivo PHP
                        success: function (response) {
                            $pagina = response;
                            loadContent('./Trabajadores.php');
                        },
                        error: function (xhr, status, error) {
                            console.error('Error en la solicitud Ajax:', error);
                        }
                    });
                }

            }

            function guardarCargo(cargo) {
                var controlador = "Empleados";
                var accion = "setCargo";
                $.ajax({
                    url: 'router.php',
                    method: 'POST',
                    data: { controlador: controlador, accion: accion, cargo: cargo },
                    success: function (response) {

                    },
                    error: function (xhr, status, error) {
                        console.error('Error en la solicitud Ajax:', error);
                    }
                });
            }


        });

        // Función para mostrar la vista correspondiente
        function MostrarPorCargo(cargo) {
            // Implementa la lógica para mostrar la vista según el cargo seleccionado
            alert("Mostrar vista para el cargo: " + cargo);
        }

        DatosAdmin();

        function DatosAdmin() {
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

        function mostrarEmpleado(datos) {
                var empleadosContainer = $("#Admin");
                var logoContainer = document.getElementById("EmpleadoImg");
            
                // Verificar si datos está definido y tiene al menos un elemento
                if (datos && datos.length > 0) {
                    var empleadoItem = $("<div class='empleado-item'></div>");
                    empleadosContainer.empty();
                    
                    // Verificar si identificacion está definido antes de intentar acceder
                    var identificacion = datos[0].identificacion || 'No disponible';
                    var nombre = datos[0].nombre || 'No disponible';
                    var cargo = datos[0].cargo || 'No disponible';
            
                    empleadoItem.html("<p><b>ID:</b> " + identificacion + " <b>Nombre:</b> " + nombre + " <b>Cargo:</b> " + cargo + " </p>");
                    empleadosContainer.append(empleadoItem);
                } else {
                    console.error('Datos no definidos o vacíos.');
                }
            }

        function entradahoy() {
            var controlador = "Empleados";
            var accion = "Hoy";
            $.ajax({
                url: 'router.php', // Ruta del archivo PHP que contiene la función Ajax
                method: 'POST',
                data: { controlador: controlador, accion: accion }, // Datos opcionales que deseas enviar al archivo PHP
                success: function (response) {
                    $pagina = response;
                    const informacionDiv = document.getElementById('informacion');

                    // Limpiar contenido previo.
                    informacionDiv.innerHTML = '';
                    loadContent('./Entradas.php');

                },
                error: function (xhr, status, error) {
                    console.error('Error en la solicitud Ajax:', error);
                }
            });
        }

        function loadContent(page) {
            $.ajax({
                url: page,
                method: 'GET',
                success: function (data) {
                    $('#contenido').html(data);
                },
                error: function (xhr, status, error) {
                        console.error('Error al cargar el contenido:');
                        console.log('XHR:', xhr);
                        console.log('Status:', status);
                        console.log('Error:', error);
                    }

            });
        
        }

        entradahoy();
        function CerrarSession() {
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
    <script>
        flatpickr("#datepicker", {
            dateFormat: "Y-m-d",
            onChange: function (selectedDates, dateStr, instance) {
                console.log("Fecha seleccionada:", dateStr);
                // Establecer la zona horaria en UTC
                var selectedDate = new Date(dateStr);

                var year = selectedDate.getUTCFullYear();
                var month = selectedDate.getUTCMonth() + 1;
                var day = selectedDate.getUTCDate();
                console.log(year);
                console.log(month);
                console.log(day); obtenerFechasAcceso(year, month, day);
            }
        });


        function obtenerFechasAcceso(año, mes, dia) {
            var controlador = "Registros";
            var accion = "obtenerFechasAcceso";
            $.ajax({
                url: 'router.php', // Ruta del archivo PHP que contiene la función Ajax
                method: 'POST',
                data: { controlador: controlador, accion: accion, año: año, mes: mes, dia: dia }, // Datos opcionales que deseas enviar al archivo PHP
                success: function (response) {
                    var datos = JSON.parse(response);

                    if (datos.length === 0) {
                        // Mostrar alerta si no hay registros para la fecha seleccionada.
                        alert('No hay registros para esta fecha.');
                    } else {
                        // Actualizar el contenedor si hay datos.
                        mostrarEmpleados(datos);
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error en la solicitud Ajax:', error);
                }
            });
        }

        // Función principal para mostrar empleados.
        function mostrarEmpleados(data) {
            // Obtener el elemento donde se mostrará la información.
            const informacionDiv = document.getElementById('informacion');

            // Limpiar contenido previo.
            informacionDiv.innerHTML = '';

            // Recorrer los datos y construir el contenido dinámicamente.
            data.forEach(empleado => {
                const empleadoDiv = construirContenidoEmpleado(empleado);

                // Agregar el div del empleado al contenedor principal.
                informacionDiv.appendChild(empleadoDiv);
            });
        }

        // Función para procesar las fechas y eliminar los NULL.
        function procesarFechas(fechas) {
            // Verificar si fechas es un array antes de usar map.
            if (Array.isArray(fechas)) {
                // Filtrar las fechas para eliminar los NULL.
                const fechasFiltradas = fechas.filter(fecha => fecha !== null);

                // Convertir las fechas a cadenas y unirlas con comas.
                return fechasFiltradas.map(fecha => `"${fecha}"`).join(', ');
            } else {
                // Si no es un array, simplemente devolver el valor.
                return fechas;
            }
        }

        // Función para mostrar las fechas procesadas.
        function mostrarFechas(fechas) {
            // Procesar las fechas para eliminar los NULL.
            const fechasProcesadas = procesarFechas(fechas);

            // Reemplazar NULL por espacio en blanco.
            return fechasProcesadas.replace(/NULL/g, '');
        }

        // Función para comparar horas y aplicar colores.
        function compararHoras(fecha, horaComparacion) {
            // Extraer la parte horaria de la fecha.
            const horaFecha = fecha.split(' ')[1];

            // Comparar las horas.
            if (horaFecha <= '08:00') {
                // Si la hora es antes o igual a las 08:00, devolver 'azul'.
                return 'blue';
            } else {
                // Si la hora es después de las 08:00, devolver 'rojo'.
                return 'green';
            }
        }

        // Función para comparar horas de salida y aplicar colores.
        function compararHorasSalida(fecha, horaComparacion) {
            // Extraer la parte horaria de la fecha.
            const horaFecha = fecha.split(' ')[1];

            // Comparar las horas.
            if (horaFecha < '06:00') {
                // Si la hora es antes de las 06:00, devolver 'rojo'.
                return 'red';
            } else {
                // Si la hora es a las 06:00 o después, devolver 'azul'.
                return 'blue';
            }
        }

        // Función para construir el contenido de cada empleado.
        function construirContenidoEmpleado(empleado) {
            const empleadoDiv = document.createElement('div');
            empleadoDiv.classList.add('catalog-item');
            empleadoDiv.innerHTML = `<h2>${empleado.nombre}</h2>`;

            // Mostrar fechas de entrada y aplicar color según la comparación.
            empleadoDiv.innerHTML += `<p>Fechas de Entrada: <span style="color: ${compararHoras(empleado.fechas_entrada, '08:00')}">${mostrarFechas(empleado.fechas_entrada)}</span></p>`;

            // Mostrar fechas de permiso con color blanco.
            empleadoDiv.innerHTML += `<p>Fechas de Permiso: <span style="color: white;">${mostrarFechas(empleado.fechas_permiso)}</span></p>`;

            // Mostrar fechas de salida y aplicar color según la comparación.
            empleadoDiv.innerHTML += `<p>Fechas de Salida: <span style="color: ${compararHorasSalida(empleado.fechas_salida, '06:00')}">${mostrarFechas(empleado.fechas_salida)}</span></p>`;

            return empleadoDiv;
        }

    </script>
</body>

</html>
<?php
 } else{
     header('Location: ./InicioSesion.html');
 }
?>
