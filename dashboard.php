<?php
session_start();

// Verificar si el docente ha iniciado sesión
if (!isset($_SESSION['docente_id']) || !isset($_SESSION['nombre_docente'])) {
    header("Location: login.php");
    exit();
}

$nombre_docente = $_SESSION['nombre_docente']; // Obtener el nombre del docente
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Asistencias</title>
    <style>
        body {
            background-color: #f0f8ff;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .dashboard-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 20px;
            max-width: 600px;
            margin: auto;
        }
        h1, h2 {
            color: #007bff; /* Azul */
        }
        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
            margin: 5px 0;
            width: 100%;
        }
        button:hover {
            background-color: #0056b3;
        }
        a {
            text-decoration: none;
            color: #007bff;
            display: inline-block;
            margin: 10px 0;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h1>Bienvenido, <?php echo htmlspecialchars($nombre_docente); ?>!</h1>

        <!-- Botón para agregar un nuevo alumno -->
        <a href="agregar_alumno.php"><button>Agregar Alumno</button></a>

        <!-- Botón para ver asistencias -->
        <a href="asistencia.php"><button>Registrar Asistencia</button></a>

        <a href="listar_asistencias.php"><button>Ver Asistencias</button></a>

        <!-- Botón para imprimir asistencia -->
        <a href="imprimir_asistencia.php"><button>Imprimir Asistencia</button></a>

      

        <a href="login.php">Cerrar Sesión</a>
    </div>
</body>
</html>

