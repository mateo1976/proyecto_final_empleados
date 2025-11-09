<?php
// app/views/dashboard.php
require_once __DIR__ . '/../models/Empleado.php';
$empleadoModel = new Empleado();
$total = count($empleadoModel->all());
$totNom = $empleadoModel->totalNomina()['total'] ?? 0;
$porSexo = $empleadoModel->totalPorSexo();
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Dashboard - M.S.C COMPANY</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="../css/styles.css" rel="stylesheet">
</head>
<body>
<?php include __DIR__ . '/partials/header.php'; ?>
<div class="container-fluid">
  <div class="row">
    <?php include __DIR__ . '/partials/sidebar.php'; ?>
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      <div class="d-flex justify-content-between flex-wrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Dashboard</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
          <a href="index.php?route=empleados" class="btn btn-outline-primary">Ir a Empleados</a>
        </div>
      </div>

      <div class="row g-3 mb-4">
        <div class="col-md-3">
          <div class="card shadow-sm p-3">
            <div class="card-body">
              <h6>Total Empleados</h6>
              <h3 class="counter"><?php echo $total; ?></h3>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card shadow-sm p-3">
            <div class="card-body">
              <h6>Nómina Total</h6>
              <h3 class="counter"><?php echo number_format($totNom,2); ?></h3>
            </div>
          </div>
        </div>
        <!-- more cards -->
      </div>

      <div class="card">
        <div class="card-body">
          <h5>Bienvenido, administrador</h5>
          <p>Panel principal de gestión de empleados.</p>
        </div>
      </div>

    </main>
  </div>
</div>
<script src="../js/main.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
