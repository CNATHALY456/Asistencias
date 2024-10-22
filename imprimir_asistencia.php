<?php
session_start();

// Verificar si el docente ha iniciado sesión
if (!isset($_SESSION['docente_id'])) {
    header("Location: login.php");
    exit();
}

// Obtener el nombre del docente
$nombre_docente = $_SESSION['nombre_docente'];

// Conexión a la base de datos
$conn = new mysqli('localhost', 'root', '', 'asistencia');
if ($conn->connect_error) {
    die("Error en la conexión: " . $conn->connect_error);
}



// Variables para almacenar el resultado si se envía el formulario
$asistencias = [];
$grados = [];
$secciones = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $grado = $_POST['grado'];
    $seccion = $_POST['seccion'];
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];

    // Consultar las asistencias
    $sql = "SELECT a.nombre, a.apellido, asis.estado, asis.comentario 
            FROM alumnos a 
            LEFT JOIN asistencias asis ON a.id = asis.alumno_id 
            WHERE a.grado = ? AND a.seccion = ? AND asis.fecha = ? AND asis.hora = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $grado, $seccion, $fecha, $hora);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $asistencias[] = $row;
    }
}

// Consultar los grados y secciones disponibles
$sqlGrados = "SELECT DISTINCT grado FROM alumnos";
$resultGrados = $conn->query($sqlGrados);
while ($row = $resultGrados->fetch_assoc()) {
    $grados[] = $row['grado'];
}

$sqlSecciones = "SELECT DISTINCT seccion FROM alumnos";
$resultSecciones = $conn->query($sqlSecciones);
while ($row = $resultSecciones->fetch_assoc()) {
    $secciones[] = $row['seccion'];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Imprimir Asistencia</title>
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
            max-width: 600px;
            margin: auto;
        }
        h2 {
            color: #007bff;
            text-align: center;
        }
        label {
            display: block;
            margin: 10px 0 5px;
        }
        select, input[type="text"], input[type="date"], button {
            width: 100%;
            padding: 10px;
            margin: 5px 0 15px;
            border: 1px solid #007bff;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .result-container {
            margin-top: 20px;
        }
        .result-container table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .result-container th, .result-container td {
            border: 1px solid #007bff;
            padding: 10px;
            text-align: left;
        }
        .result-container th {
            background-color: #007bff;
            color: white;
        }
        @media print {
            button, .form-container {
                display: none; /* Ocultar botones y formulario al imprimir */
            }
        }
    </style>
    <script>
        function printPage() {
            window.print(); // Función para imprimir la página
        }
    </script>
</head>
<body>
    <div class="form-container">
        <h2>Imprimir Asistencia</h2>
        <form method="post">
            <label for="grado">Grado:</label>
            <select name="grado" required>
                <?php foreach ($grados as $g): ?>
                    <option value="<?php echo htmlspecialchars($g); ?>"><?php echo htmlspecialchars($g); ?></option>
                <?php endforeach; ?>
            </select>

            <label for="seccion">Sección:</label>
            <select name="seccion" required>
                <?php foreach ($secciones as $s): ?>
                    <option value="<?php echo htmlspecialchars($s); ?>"><?php echo htmlspecialchars($s); ?></option>
                <?php endforeach; ?>
            </select>

            <label for="fecha">Fecha:</label>
            <input type="date" name="fecha" required>

            <label for="hora">Hora:</label>
            <input type="time" name="hora" required>

            <button type="submit">Generar Reporte</button>
        </form>
    </div>

    <?php if (!empty($asistencias)): ?>
        <div class="result-container">
            <h2>Reporte de Asistencia</h2>
            <p><strong>Docente:</strong> <?php echo htmlspecialchars($nombre_docente); ?></p>
            <p><strong>Grado:</strong> <?php echo htmlspecialchars($grado); ?></p>
            <p><strong>Sección:</strong> <?php echo htmlspecialchars($seccion); ?></p>
            <p><strong>Fecha:</strong> <?php echo htmlspecialchars($fecha); ?></p>
            <p><strong>Hora:</strong> <?php echo htmlspecialchars($hora); ?></p>

            <table>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Estado</th>
                        <th>Comentarios</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($asistencias as $asistencia): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($asistencia['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($asistencia['apellido']); ?></td>
                            <td><?php echo htmlspecialchars($asistencia['estado']); ?></td>
                            <td><?php echo htmlspecialchars($asistencia['comentario']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <button onclick="printPage()">Imprimir</button> <!-- Botón para imprimir -->
        </div>
    <?php endif; ?>

    <div style="text-align: center; margin-top: 20px;">
        <a href="dashboard.php">Volver</a>
    </div>
</body>
</html>
