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

// Si se envía el formulario para agregar un alumno
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre_alumno = trim($_POST['nombre_alumno']);
    $apellido_alumno = trim($_POST['apellido_alumno']);
    $grado_alumno = $_POST['grado_alumno'];
    $seccion_alumno = trim($_POST['seccion_alumno']);
    $docente_id = $_SESSION['docente_id'];

    // Validación básica
    if (!empty($nombre_alumno) && !empty($apellido_alumno) && !empty($grado_alumno) && !empty($seccion_alumno)) {
        // Usar consulta preparada para evitar inyecciones SQL
        $stmt = $conn->prepare("INSERT INTO alumnos (nombre, apellido, grado, seccion, docente_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $nombre_alumno, $apellido_alumno, $grado_alumno, $seccion_alumno, $docente_id);

        if ($stmt->execute()) {
            // Redirigir al dashboard después de agregar el alumno
            header("Location: dashboard.php");
            exit();
        } else {
            echo "<p style='color: red;'>Error al agregar el alumno: " . $stmt->error . "</p>";
        }

        // Cerrar la declaración
        $stmt->close();
    } else {
        echo "<p style='color: red;'>Todos los campos son obligatorios.</p>";
    }
}

// Cerrar la conexión
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Alumno</title>
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
            max-width: 400px;
            margin: auto;
        }
        h1 {
            color: #007bff; /* Azul */
            text-align: center;
        }
        input[type="text"],
        select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #007bff;
            border-radius: 4px;
            font-size: 16px;
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
        }
        button:hover {
            background-color: #0056b3;
        }
        a {
            display: block;
            margin-top: 10px;
            color: #007bff;
            text-decoration: none;
            text-align: center;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Agregar Alumno</h1>
        <form method="post">
            Nombres del Alumno: <input type="text" name="nombre_alumno" required>
            Apellidos del Alumno: <input type="text" name="apellido_alumno" required>
            
            Grado: 
            <select name="grado_alumno" required>
                <option value="Primer año">Primer año</option>
                <option value="Segundo año">Segundo año</option>
                <option value="Tercer año">Tercer año</option>
            </select>

            Sección: <input type="text" name="seccion_alumno" required>
            
            <button type="submit">Agregar Alumno</button>
        </form>
        <a href="dashboard.php">Volver</a>
    </div>
</body>
</html>


