<?php
// app/views/finanzas.php
require_once __DIR__ . '/../models/Empleado.php';
$empleadoModel = new Empleado();
$empleados = $empleadoModel->all();
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Finanzas - M.S.C COMPANY</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="../css/styles.css" rel="stylesheet">
</head>
<body>
<?php include __DIR__ . '/partials/header.php'; ?>
<div class="container-fluid">
  <div class="row">
    <?php include __DIR__ . '/partials/sidebar.php'; ?>
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      <h2 class="mt-3">Finanzas</h2>
      <div class="card mb-3">
        <div class="card-body">
          <p>Lista de sueldos. Aquí puedes ordenar y aplicar aumentos.</p>
        </div>
      </div>

      <div class="card">
        <div class="card-body">
          <table class="table">
            <thead><tr><th>Nombre</th><th>Sueldo</th></tr></thead>
            <tbody>
<?php foreach($empleados as $e): ?>
  <tr><td><?php echo htmlspecialchars($e['nombre']); ?></td><td><?php echo number_format($e['sueldoBasico'],2); ?></td></tr>
<?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>

    </main>
  </div>
</div>
<script src="../js/main.js"></script>
</body>
</html>
