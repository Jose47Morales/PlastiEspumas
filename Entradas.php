<?php
session_start();
if($_SESSION['identificacionI']){
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registros antes y después de las 8:00 AM</title>
    <!-- jQuery -->
    <script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* Estilo para las tablas */
        .tables_container {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            margin: 10px;
        }

        #t1,
        #t2 {
            width: 48%;
            margin-bottom: 10px;
            font-size: 12px;
        }

        select, option{
            color: black !important;
        }
        
        @media (max-width: 750px) {
            .tables_container {
                flex-direction: column;
                align-items: center;
            }

            #t1,
            #t2 {
                width: 100%;
            }

            #t1 table,
            #t2 table {
                max-width: 100%;
            }

            #t1 table td,
            #t2 table td {
                font-size: 10px; /* Puedes ajustar este valor según tus necesidades */
            }
        }

        @media (max-width: 550px) {
            html {
                font-size: 10px;
            }

            #t1 table td,
            #t2 table td {
                font-size: 8px; /* Puedes ajustar este valor según tus necesidades */
            }

            button, select{
                transform: scale(0.8) !important;
                font-size: 11px;
            }
            
        }

        th,
        td {
            padding: 5px;
            border: 1px solid #ddd;
            text-align: left;
        }

        table {
            max-width: 75%;
            border-spacing: 0;
            border-collapse: collapse;
            overflow-x: auto;
        }

        table td {
            font-size: 10px;
        }

        select, option{
            color: black !important;
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
    </style>

</head>

<body>

    <div class="tables_container">
        <div id="t1">
            <!-- Contenido de la tabla t1 -->
        </div>

        <div id="t2">
            <!-- Contenido de la tabla t2 -->
        </div>
    </div>

    <script>
        $(document).ready(function () {

            function mostrarRegistros(tipo) {
                $.ajax({
                    url: 'router.php',
                    method: 'POST',
                    data: {
                        accion: 'obtenerRegistros' + tipo,
                        controlador: 'Empleados',
                        fecha: obtenerFechaActual()
                    },
                    success: function (response) {
                        var datos = JSON.parse(response);
                        console.log('Datos recibidos:', datos);

                        if (tipo == "AntesDe8AM") {
                            construirTabla(datos, $('#t1'), "AntesDe8AM");
                        } else if (tipo == "DespuesDe8AM") {
                            construirTabla(datos, $('#t2'), "DespuesDe8AM");
                        }
                    },
                    error: function (error) {
                        console.error(error);
                    }
                });
            }

            function obtenerFechaActual() {
                var today = new Date();
                var dd = String(today.getDate()).padStart(2, '0');
                var mm = String(today.getMonth() + 1).padStart(2, '0');
                var yyyy = today.getFullYear();
                return yyyy + '-' + mm + '-' + dd;
            }

            function construirTabla(datos, contenedor, tipo) {
                var tabla = $('<table>').addClass('table table-bordered table-hover').appendTo(contenedor);
                var thead = $('<thead>').addClass('table-primary').appendTo(tabla);
                var tbody = $('<tbody>').appendTo(tabla);

                var columnasMostradas = ['Identificacion', 'Nombre', 'Cargo', 'Tipo de acceso', 'Estado', 'Hora de ingreso'];
                var filtro = ['identificacion', 'nombre', 'cargo', 'access_type', 'status', 'date_time'];

                var headerRow = $('<tr>').appendTo(thead);
                columnasMostradas.forEach(function (columna) {
                    $('<th>').text(columna).appendTo(headerRow);
                });

                datos.forEach(function (row, index) {
                    if (row['access_type'] === 'Entrada') {
                        var dataRow = $('<tr>').appendTo(tbody);

                        filtro.forEach(function (columna) {
                            $('<td>').text(row[columna]).appendTo(dataRow);
                        });

                        if (tipo === 'AntesDe8AM') {
                            dataRow.css('background-color', (index % 2 === 0) ? 'blue' : 'blue');
                            dataRow.css('color', (index % 2 === 0) ? 'white' : 'white');
                        } else if (tipo === 'DespuesDe8AM') {
                            dataRow.css('background-color', (index % 2 === 0) ? 'red' : 'red');
                            dataRow.css('color', (index % 2 === 0) ? 'white' : 'white');
                        }
                    }
                });

                contenedor.css('overflow-x', 'auto');
                contenedor.css('text-align', 'center');
                tabla.css('margin', 'auto');
            }

            mostrarRegistros("AntesDe8AM");
            mostrarRegistros("DespuesDe8AM");
        });
    </script>
</body>
</html>
    <?php
} else{
    header('Location: ./InicioSesion.html');
}
?>
