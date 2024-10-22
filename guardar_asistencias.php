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

// Si se envía el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];
    $grado = $_POST['grado'];
    $seccion = $_POST['seccion'];

    // Preparar la consulta para insertar asistencias
    $query = "INSERT INTO asistencias (alumno_id, fecha, hora, estado, comentario) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);

    // Verificar si la consulta se preparó correctamente
    if (!$stmt) {
        die("Error al preparar la consulta: " . $conn->error);
    }

    $stmt->bind_param("issss", $alumno_id, $fecha, $hora, $estado, $comentario);

    // Iterar sobre los alumnos y guardar sus asistencias
    foreach ($_POST['asistencia'] as $alumno_id => $asistio) {
        $comentario = isset($_POST['comentarios'][$alumno_id]) ? $_POST['comentarios'][$alumno_id] : '';

        // Convertir la opción de asistencia a 'Presente', 'Ausente' o 'Permiso'
        switch ($asistio) {
            case '1':
                $estado = 'Presente';
                break;
            case '0':
                $estado = 'Ausente';
                break;
            case '2':
                $estado = 'Permiso';
                break;
            default:
                $estado = 'Ausente'; // Valor por defecto
        }

        // Ejecutar la consulta
        if (!$stmt->execute()) {
            echo "Error al guardar la asistencia del alumno ID $alumno_id: " . $stmt->error;
        }
    }

    // Cerrar la declaración y la conexión
    $stmt->close();
    $conn->close();

    // Mensaje de éxito
    echo "<script>alert('Asistencias guardadas con éxito.'); window.location.href='dashboard.php';</script>";
    exit();
}
?>
