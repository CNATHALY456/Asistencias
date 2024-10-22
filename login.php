<?php
session_start();

$servername = 'localhost';
$username = 'root';  // Ajusta si es necesario
$password = '';  // Deja vacío si no tienes contraseña para MySQL
$database = 'asistencia';  // Nombre de tu base de datos

// Crear conexión
$conn = new mysqli($servername, $username, $password, $database);

// Verificar conexión
if ($conn->connect_error) {
    die("Error en la conexión: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $correo = $_POST['correo'];
    $contrasena = $_POST['contrasena'];

    // Consulta para obtener el docente basado en el correo
    $sql = "SELECT * FROM docentes WHERE correo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $correo); // Usa una consulta preparada
    $stmt->execute();
    $result = $stmt->get_result();
    $docente = $result->fetch_assoc();

    // Verificar la contraseña
    if ($docente && password_verify($contrasena, $docente['contrasena'])) {
        $_SESSION['docente_id'] = $docente['id'];
        $_SESSION['nombre_docente'] = $docente['nombre']; // Almacena el nombre en la sesión
        header("Location: dashboard.php");
        exit; // Asegúrate de terminar el script después de redirigir
    } else {
        echo "<p style='color: red;'>Correo o contraseña incorrectos.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
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
        .login-container {
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
    <div class="login-container">
        <h2>Iniciar Sesión</h2>
        <form method="post">
            Correo: <input type="email" name="correo" required>
            Contraseña: <input type="password" name="contrasena" required>
            <button type="submit">Iniciar Sesión</button>
        </form>
        <a href="register.php">Registrar nuevo docente</a>
    </div>
</body>
</html>
