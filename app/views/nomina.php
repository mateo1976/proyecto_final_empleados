<?php
// app/views/nomina.php
require_once __DIR__ . '/../models/Empleado.php';
$empleadoModel = new Empleado();
$total = $empleadoModel->totalNomina()['total'] ?? 0;
$porSexo = $empleadoModel->totalPorSexo();
$salario_minimo = 1400000; // referencia, ajustable en config
$mayorSM = $empleadoModel->countMayorSalario($salario_minimo)['cantidad'] ?? 0;
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Nómina - M.S.C COMPANY</title>
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
      <h2 class="mt-3">Nómina</h2>
      <div class="card mb-3">
        <div class="card-body">
          <p>Total nómina general: <strong><?php echo number_format($total,2); ?></strong></p>
          <p>Empleados que ganan más del salario mínimo (<?php echo number_format($salario_minimo,0); ?>): <strong><?php echo $mayorSM; ?></strong></p>
        </div>
      </div>
    </main>
  </div>
</div>
<script src="../js/main.js"></script>
</body>
</html>
