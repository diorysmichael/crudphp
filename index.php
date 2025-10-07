<?php
// ==== Conexión a la BD ====
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "estudiantes";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// ==== Variables ====
$accion = $_GET['accion'] ?? 'leer'; // por defecto listar
$id = $_GET['id'] ?? null;

// ==== Crear ====
if (isset($_POST['guardar'])) {
    $numero = $_POST['numero'];
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $carrera = $_POST['carrera'];

    $sql = "INSERT INTO estudiantes (numero, nombre, correo, carrera) 
            VALUES ('$numero','$nombre','$correo','$carrera')";
    $conn->query($sql);
    header("Location: index.php");
    exit;
}

// ==== Actualizar ====
if (isset($_POST['actualizar'])) {
    $numero = $_POST['numero'];
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $carrera = $_POST['carrera'];

    $sql = "UPDATE estudiantes 
            SET numero='$numero', nombre='$nombre', correo='$correo', carrera='$carrera' 
            WHERE id=$id";
    $conn->query($sql);
    header("Location: index.php");
    exit;
}

// ==== Eliminar ====
if ($accion == 'eliminar' && $id) {
    $conn->query("DELETE FROM estudiantes WHERE id=$id");
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>CRUD Estudiantes</title>
    <link rel="stylesheet" href="style.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- ===== MENÚ DE NAVEGACIÓN (Bootstrap) ===== -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">Gestión Estudiantes</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link <?php echo ($accion == 'leer') ? 'active' : ''; ?>" href="index.php">Inicio</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo ($accion == 'crear') ? 'active' : ''; ?>" href="index.php?accion=crear">Registrar</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="container">

<?php if ($accion == 'crear'): ?>
    <h2>Registrar Estudiante</h2>
    <form method="POST" class="row g-3 mb-4">
        <div class="col-md-3">
            <input type="text" name="numero" class="form-control" placeholder="Número" required>
        </div>
        <div class="col-md-3">
            <input type="text" name="nombre" class="form-control" placeholder="Nombre completo" required>
        </div>
        <div class="col-md-3">
            <input type="email" name="correo" class="form-control" placeholder="Correo electrónico" required>
        </div>
        <div class="col-md-3">
            <input type="text" name="carrera" class="form-control" placeholder="Carrera" required>
        </div>
        <div class="col-12">
            <button type="submit" name="guardar" class="btn btn-success">Guardar</button>
        </div>
    </form>
    <a href="index.php" class="btn btn-secondary">← Volver a la lista</a>

<?php elseif ($accion == 'editar' && $id): ?>
    <?php
    $sql = "SELECT * FROM estudiantes WHERE id=$id";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    ?>
    <h2>Editar Estudiante</h2>
    <form method="POST" class="row g-3 mb-4">
        <div class="col-md-3">
            <input type="text" name="numero" value="<?php echo $row['numero']; ?>" class="form-control" required>
        </div>
        <div class="col-md-3">
            <input type="text" name="nombre" value="<?php echo $row['nombre']; ?>" class="form-control" required>
        </div>
        <div class="col-md-3">
            <input type="email" name="correo" value="<?php echo $row['correo']; ?>" class="form-control" required>
        </div>
        <div class="col-md-3">
            <input type="text" name="carrera" value="<?php echo $row['carrera']; ?>" class="form-control" required>
        </div>
        <div class="col-12">
            <button type="submit" name="actualizar" class="btn btn-primary">Actualizar</button>
        </div>
    </form>
    <a href="index.php" class="btn btn-secondary">← Volver a la lista</a>

<?php else: ?>
    <h2>Lista de Estudiantes</h2>
    <a href="index.php?accion=crear" class="btn btn-success mb-3">+ Nuevo Estudiante</a>
    <table class="table table-striped table-hover table-bordered">
        <thead class="table-dark">
            <tr>
                <th>No.</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Carrera</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $sql = "SELECT * FROM estudiantes";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['numero']}</td>
                    <td>{$row['nombre']}</td>
                    <td>{$row['correo']}</td>
                    <td>{$row['carrera']}</td>
                    <td>
                        <a href='index.php?accion=editar&id={$row['id']}' class='btn btn-sm btn-primary'>Editar</a> 
                        <a href='index.php?accion=eliminar&id={$row['id']}' onclick=\"return confirm('¿Seguro que deseas eliminar este estudiante?');\" class='btn btn-sm btn-danger'>Eliminar</a>
                    </td>
                  </tr>";
        }
        ?>
        </tbody>
    </table>
<?php endif; ?>

</div>

<!-- Bootstrap JS Bundle (incluye Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

