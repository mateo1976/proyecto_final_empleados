<?php
// app/views/cumpleanos.php
require_once __DIR__ . '/../models/Empleado.php';
$empleadoModel = new Empleado();
$mes = date('n'); // default current month
if (isset($_GET['mes'])) $mes = (int)$_GET['mes'];
$lista = $empleadoModel->cumpleanosPorMes($mes);
$totales = ['M'=>0,'F'=>0];
foreach($lista as $l) {
    $totales[$l['sexo']]++;
}
$totalRegalos = $totales['M'] + $totales['F'];
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Cumpleaños - M.S.C COMPANY</title>
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
      <h2 class="mt-3">Cumpleaños</h2>
      <div class="mb-3">
        <div class="btn-group" role="group" aria-label="Meses">
<?php
$meses = ['','Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];
for ($m=1;$m<=12;$m++) {
    $active = $m==$mes ? 'btn-primary' : 'btn-outline-primary';
    echo "<a href="index.php?route=cumpleanos&mes=$m" class="btn $active">{$meses[$m]}</a>";
}
?>
        </div>
      </div>

      <div class="card mb-3">
        <div class="card-body">
          <h5>Resumen mes seleccionado</h5>
          <p>Rosas (F): <?php echo $totales['F']; ?> — Corbatas (M): <?php echo $totales['M']; ?> — Total: <?php echo $totalRegalos; ?></p>
        </div>
      </div>

      <div class="card">
        <div class="card-body">
          <table class="table">
            <thead><tr><th>Nombre</th><th>Documento</th><th>Fecha Nac.</th><th>Regalo</th></tr></thead>
            <tbody>
<?php foreach($lista as $l): ?>
  <tr>
    <td><?php echo htmlspecialchars($l['nombre']); ?></td>
    <td><?php echo htmlspecialchars($l['documento']); ?></td>
    <td><?php echo $l['fechaNacimiento']; ?></td>
    <td><?php echo $l['sexo']=='F' ? 'Rosas' : 'Corbata'; ?></td>
  </tr>
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
