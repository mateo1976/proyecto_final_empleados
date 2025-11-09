<?php
// app/views/empleados/index.php
$empleados = $empleados ?? [];
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Empleados - M.S.C COMPANY</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="../css/styles.css" rel="stylesheet">
</head>
<body>
<?php include __DIR__ . '/../partials/header.php'; ?>
<div class="container-fluid">
  <div class="row">
    <?php include __DIR__ . '/../partials/sidebar.php'; ?>
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      <div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h2>Empleados</h2>
        <a href="index.php?route=empleados_create" class="btn btn-primary">Nuevo empleado</a>
      </div>

      <div class="card mb-3">
        <div class="card-body">
          <table id="tablaEmpleados" class="display" style="width:100%">
            <thead>
              <tr>
                <th>Documento</th>
                <th>Nombre</th>
                <th>Sexo</th>
                <th>Teléfono</th>
                <th>Correo</th>
                <th>Sueldo</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
<?php foreach($empleados as $e): ?>
  <tr>
    <td><?php echo htmlspecialchars($e['documento']); ?></td>
    <td><?php echo htmlspecialchars($e['nombre']); ?></td>
    <td><?php echo htmlspecialchars($e['sexo']); ?></td>
    <td><?php echo htmlspecialchars($e['telefono']); ?></td>
    <td><?php echo htmlspecialchars($e['correo']); ?></td>
    <td><?php echo number_format($e['sueldoBasico'],2); ?></td>
    <td>
      <a class="btn btn-sm btn-outline-secondary" href="index.php?route=empleados_edit&id=<?php echo $e['id']; ?>">Editar</a>
      <a class="btn btn-sm btn-outline-danger" href="index.php?route=empleados_delete&id=<?php echo $e['id']; ?>" onclick="return confirm('¿Eliminar empleado?')">Eliminar</a>
    </td>
  </tr>
<?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>

    </main>
  </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="../js/main.js"></script>
<script>
$(document).ready(function() {
    $('#tablaEmpleados').DataTable();
});
</script>
</body>
</html>
