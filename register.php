<?php
// Conexión a la base de datos
$conn = new mysqli('localhost', 'root', '', 'asistencia');

// Verificar conexión
if ($conn->connect_error) {
    die("Error en la conexión: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = trim($_POST['nombre']);
    $correo = trim($_POST['correo']);
    $contrasena = trim($_POST['contrasena']);

    // Validaciones básicas
    if (empty($nombre) || empty($correo) || empty($contrasena)) {
        echo "<p style='color: red;'>Todos los campos son obligatorios.</p>";
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        echo "<p style='color: red;'>Correo no es válido.</p>";
    } else {
        // Crear contraseña encriptada
        $hash_contrasena = password_hash($contrasena, PASSWORD_DEFAULT);

        // Usar consultas preparadas para evitar inyecciones SQL
        $stmt = $conn->prepare("INSERT INTO docentes (nombre, correo, contrasena) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nombre, $correo, $hash_contrasena);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Redirigir a dashboard.php después de un registro exitoso
            header("Location: dashboard.php");
            exit();  // Asegura que el script se detenga después de la redirección
        } else {
            echo "<p style='color: red;'>Error al registrar: " . $stmt->error . "</p>";
        }

        // Cerrar la declaración
        $stmt->close();
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
    <title>Registrar Docente</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f0f8ff;
            font-family: Arial, sans-serif;
        }
        .register-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 20px;
            text-align: center;
            width: 300px;
        }
        h2 {
            color: #007bff; /* Azul */
        }
        input[type="text"],
        input[type="email"],
        input[type="password"] {
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
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>Registrar Docente</h2>
        <form method="post">
            Nombre: <input type="text" name="nombre" required>
            Correo: <input type="email" name="correo" required>
            Contraseña: <input type="password" name="contrasena" required>
            <button type="submit">Registrarse</button>
        </form>
        <a href="login.php">Volver a Iniciar Sesión</a>
    </div>
</body>
</html>




