<?php
session_start();

// Verificar si el docente ha iniciado sesión
if (!isset($_SESSION['docente_id'])) {
    header("Location: login.php");
    exit();
}

// Conexión a la base de datos
$conn = new mysqli('localhost', 'root', '', 'asistencia');

// Verificar la conexión
if ($conn->connect_error) {
    die("Error en la conexión: " . $conn->connect_error);
}

// Consultar los grados disponibles
$grados = ['Primer año', 'Segundo año', 'Tercer año'];

// Variables para el mes y año seleccionados
$mes_seleccionado = date('m'); // Mes actual
$año_seleccionado = date('Y'); // Año actual
$dia_seleccionado = date('d'); // Día actual

// Si se envía el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $grado_seleccionado = $_POST['grado'];
    $seccion_seleccionada = trim($_POST['seccion']);
    $mes_seleccionado = $_POST['mes'];
    $año_seleccionado = $_POST['año'];
    $dia_seleccionado = $_POST['dia'];
    $hora_seleccionada = $_POST['hora'];

    // Obtener las asistencias de la base de datos, incluyendo la hora y comentario
    $sql = "SELECT a.nombre, a.apellido, a.grado, a.seccion, asis.fecha, asis.estado, asis.hora, asis.comentario 
            FROM alumnos a 
            LEFT JOIN asistencias asis ON a.id = asis.alumno_id 
            WHERE a.grado = ? AND a.seccion = ? 
            AND DAY(asis.fecha) = ? AND MONTH(asis.fecha) = ? AND YEAR(asis.fecha) = ? 
            AND asis.hora = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssiiis", $grado_seleccionado, $seccion_seleccionada, $dia_seleccionado, $mes_seleccionado, $año_seleccionado, $hora_seleccionada);
    $stmt->execute();
    $result = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Asistencias</title>
    <style>
        body {
            background-color: #f0f8ff;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .form-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 20px;
            max-width: 500px;
            margin: auto;
            margin-bottom: 20px; /* Espacio abajo del formulario */
        }
        h1, h2 {
            color: #007bff;
            text-align: center;
        }
        label {
            font-weight: bold; /* Resaltar las etiquetas */
        }
        select, input[type="text"], input[type="time"] {
            width: calc(100% - 22px); /* Ajustar el ancho para el padding */
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #007bff;
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box; /* Incluir padding en el ancho total */
        }
        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            margin-top: 10px;
        }
        button:hover {
            background-color: #0056b3;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #007bff;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        .presente {
            color: green;
        }
        .ausente, .permiso {
            color: red;
        }
        .button-container {
            text-align: center; /* Centrar los botones */
            margin-top: 20px; /* Espacio arriba de los botones */
        }
        .button {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 10px 20px; /* Espacio en los botones */
            border-radius: 4px;
            text-decoration: none;
            font-size: 16px;
            margin: 0 10px; /* Espacio entre los botones */
            transition: background-color 0.3s; /* Efecto de transición */
        }
        .button:hover {
            background-color: #0056b3; /* Color al pasar el mouse */
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Listado de Asistencias</h1>
        <form method="post">
            <label for="grado">Grado:</label>
            <select name="grado" required>
                <?php foreach ($grados as $grado): ?>
                    <option value="<?php echo $grado; ?>"><?php echo $grado; ?></option>
                <?php endforeach; ?>
            </select>

            <label for="seccion">Sección:</label>
            <input type="text" name="seccion" required>

            <label for="dia">Día:</label>
            <select name="dia" required>
                <?php for ($i = 1; $i <= 31; $i++): ?>
                    <option value="<?php echo $i; ?>" <?php echo $dia_seleccionado == $i ? 'selected' : ''; ?>><?php echo $i; ?></option>
                <?php endfor; ?>
            </select>

            <label for="mes">Mes:</label>
            <select name="mes" required>
                <?php for ($i = 1; $i <= 12; $i++): ?>
                    <option value="<?php echo $i; ?>" <?php echo $mes_seleccionado == $i ? 'selected' : ''; ?>><?php echo date('F', mktime(0, 0, 0, $i, 1)); ?></option>
                <?php endfor; ?>
            </select>

            <label for="año">Año:</label>
            <input type="text" name="año" value="<?php echo $año_seleccionado; ?>" required>

            <label for="hora">Hora:</label>
            <input type="time" name="hora" required>

            <button type="submit">Filtrar Asistencias</button>
        </form>
    </div>

    <div class="asistencias-list">
        <h2>Asistencias registradas:</h2>
        <table>
            <thead>
                <tr>
                    <th>Apellido</th>
                    <th>Nombre</th>
                    <th>Grado</th>
                    <th>Sección</th>
                    <th>Asistencia</th>
                    <th>Hora</th>
                    <th>Comentario</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (isset($result) && $result->num_rows > 0) {
                    while ($asistencia = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($asistencia['apellido']); ?></td>
                            <td><?php echo htmlspecialchars($asistencia['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($asistencia['grado']); ?></td>
                            <td><?php echo htmlspecialchars($asistencia['seccion']); ?></td>
                            <td class="<?php echo strtolower($asistencia['estado']); ?>">
                                <?php echo $asistencia['estado']; ?>
                            </td>
                            <td><?php echo htmlspecialchars($asistencia['hora']); ?></td>
                            <td><?php echo htmlspecialchars($asistencia['comentario']); ?></td>
                        </tr>
                    <?php endwhile;
                } else {
                    echo "<tr><td colspan='7' style='text-align: center;'>No hay asistencias registradas para este grado y sección en la fecha seleccionada.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <div class="button-container">
        <a href="dashboard.php" class="button">Volver</a>
        <a href="login.php" class="button">Cerrar Sesión</a>
    </div>

    <?php
    // Cerrar la conexión
    if (isset($stmt)) {
        $stmt->close();
    }
    $conn->close();
    ?>
</body>
</html>



