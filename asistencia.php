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

// Si se envía el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $grado_seleccionado = $_POST['grado'];
    $seccion_seleccionada = trim($_POST['seccion']);
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];  // Capturar la hora seleccionada

    // Consulta para obtener los alumnos filtrados
    $sql = "SELECT * FROM alumnos WHERE grado = ? AND seccion = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $grado_seleccionado, $seccion_seleccionada);
    $stmt->execute();
    $result = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Asistencia</title>
    <style>
        body {
            background-color: #f0f8ff;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .form-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 20px;
            max-width: 600px;
            width: 100%;
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
        select, input[type="text"], input[type="date"], input[type="time"], button {
            width: 100%;
            padding: 10px;
            margin: 5px 0 15px;
            border: 1px solid #007bff;
            border-radius: 4px;
            box-sizing: border-box;
            height: 40px;
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
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
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
        .back-button {
            display: block;
            margin: 20px auto;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="form-container">
    <h2>Registrar Asistencia</h2>
    <form method="post">
        <label for="grado">Grado:</label>
        <select name="grado" required>
            <?php foreach ($grados as $grado): ?>
                <option value="<?php echo $grado; ?>"><?php echo $grado; ?></option>
            <?php endforeach; ?>
        </select>

        <label for="seccion">Sección:</label>
        <input type="text" name="seccion" required>

        <label for="fecha">Fecha:</label>
        <input type="date" name="fecha" required>

        <label for="hora">Hora:</label>
        <input type="time" name="hora" required> <!-- Campo para seleccionar la hora -->

        <button type="submit">Filtrar Alumnos</button>
    </form>
</div>

<?php if (isset($result) && $result->num_rows > 0): ?>
    <h2>Alumnos</h2>
    <form method="post" action="guardar_asistencias.php">
        <input type="hidden" name="fecha" value="<?php echo $fecha; ?>">
        <input type="hidden" name="hora" value="<?php echo $hora; ?>"> <!-- Enviar la hora -->
        <input type="hidden" name="grado" value="<?php echo $grado_seleccionado; ?>">
        <input type="hidden" name="seccion" value="<?php echo $seccion_seleccionada; ?>">
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Estado</th>
                    <th>Comentarios</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($alumno = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($alumno['nombre'] . ' ' . $alumno['apellido']); ?></td>
                        <td>
                            <select name="asistencia[<?php echo $alumno['id']; ?>]" required>
                                <option value="1">Presente</option>
                                <option value="0">Ausente</option>
                                <option value="2">Permiso</option>
                            </select>
                        </td>
                        <td>
                            <input type="text" name="comentarios[<?php echo $alumno['id']; ?>]" placeholder="Comentarios opcionales">
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <button type="submit">Guardar Asistencias</button>
    </form>
<?php endif; ?>

<a class="back-button" href="dashboard.php">Volver </a>
</body>
</html>






