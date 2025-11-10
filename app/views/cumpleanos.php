<?php
// app/views/cumpleanos.php
require_once __DIR__ . '/../models/Empleado.php';
$empleadoModel = new Empleado();

$mes = isset($_GET['mes']) ? (int)$_GET['mes'] : date('n');
$lista = $empleadoModel->cumpleanosPorMes($mes);

$totales = ['M' => 0, 'F' => 0];
foreach($lista as $l) {
    $totales[$l['sexo']]++;
}
$totalRegalos = $totales['M'] + $totales['F'];

$meses = [
    1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
    5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
    9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
];
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Cumpleaños - M.S.C COMPANY</title>
  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="<?php echo base_url('css/styles.css'); ?>" rel="stylesheet">
</head>
<body>

<?php include __DIR__ . '/partials/header.php'; ?>

<div class="container-fluid">
  <div class="row">
    <?php include __DIR__ . '/partials/sidebar.php'; ?>
    
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      
      <!-- Encabezado -->
      <div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h2>
          <i class="bi bi-gift-fill text-warning"></i> 
          Cumpleaños - <?php echo $meses[$mes]; ?>
        </h2>
      </div>

      <!-- Selector de meses -->
      <div class="card mb-4 shadow-sm">
        <div class="card-body">
          <h5 class="mb-3">
            <i class="bi bi-calendar3"></i> Seleccionar Mes
          </h5>
          <div class="btn-group flex-wrap" role="group">
            <?php for ($m = 1; $m <= 12; $m++): 
              $active = $m == $mes ? 'btn-primary' : 'btn-outline-primary';
              $mesCorto = substr($meses[$m], 0, 3);
            ?>
            <a href="<?php echo route('cumpleanos', ['mes' => $m]); ?>" 
               class="btn <?php echo $active; ?>">
              <?php echo $mesCorto; ?>
            </a>
            <?php endfor; ?>
          </div>
        </div>
      </div>

      <!-- Resumen de regalos -->
      <div class="row g-3 mb-4">
        <div class="col-md-4">
          <div class="card border-0 shadow-sm text-center">
            <div class="card-body">
              <i class="bi bi-flower1 fs-1 text-danger"></i>
              <h3 class="mt-2"><?php echo $totales['F']; ?></h3>
              <p class="text-muted mb-0">Rosas (Femenino)</p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card border-0 shadow-sm text-center">
            <div class="card-body">
              <i class="bi bi-award fs-1 text-info"></i>
              <h3 class="mt-2"><?php echo $totales['M']; ?></h3>
              <p class="text-muted mb-0">Corbatas (Masculino)</p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card border-0 shadow-sm text-center">
            <div class="card-body">
              <i class="bi bi-gift fs-1 text-warning"></i>
              <h3 class="mt-2"><?php echo $totalRegalos; ?></h3>
              <p class="text-muted mb-0">Total Regalos</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Lista de cumpleaños -->
      <?php if (!empty($lista)): ?>
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
          <h5 class="mb-0">
            <i class="bi bi-list-ul"></i> 
            Empleados con cumpleaños en <?php echo $meses[$mes]; ?>
          </h5>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover">
              <thead class="table-light">
                <tr>
                  <th>Día</th>
                  <th>Nombre</th>
                  <th>Documento</th>
                  <th>Fecha Nacimiento</th>
                  <th>Edad</th>
                  <th>Regalo</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach($lista as $l): 
                  $edad = date('Y') - date('Y', strtotime($l['fechaNacimiento']));
                  $dia = date('d', strtotime($l['fechaNacimiento']));
                ?>
                <tr>
                  <td>
                    <span class="badge bg-primary rounded-circle" style="width:40px;height:40px;line-height:30px;font-size:16px;">
                      <?php echo $dia; ?>
                    </span>
                  </td>
                  <td><strong><?php echo e($l['nombre']); ?></strong></td>
                  <td><?php echo e($l['documento']); ?></td>
                  <td><?php echo formatDate($l['fechaNacimiento']); ?></td>
                  <td><?php echo $edad; ?> años</td>
                  <td>
                    <?php if ($l['sexo'] == 'F'): ?>
                      <span class="badge bg-danger">
                        <i class="bi bi-flower1"></i> Rosas
                      </span>
                    <?php else: ?>
                      <span class="badge bg-info">
                        <i class="bi bi-award"></i> Corbata
                      </span>
                    <?php endif; ?>
                  </td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <?php else: ?>
      <div class="alert alert-info text-center">
        <i class="bi bi-info-circle fs-1 d-block mb-2"></i>
        <h5>No hay cumpleaños registrados en <?php echo $meses[$mes]; ?></h5>
        <p class="mb-0">Selecciona otro mes para ver más cumpleaños.</p>
      </div>
      <?php endif; ?>

    </main>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo base_url('js/main.js'); ?>"></script>

</body>
</html>
