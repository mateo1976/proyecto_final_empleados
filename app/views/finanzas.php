<?php
// app/views/finanzas.php
require_once __DIR__ . '/../models/Empleado.php';
$empleadoModel = new Empleado();
$empleados = $empleadoModel->all();

// Ordenar por sueldo descendente
usort($empleados, function($a, $b) {
    return $b['sueldoBasico'] <=> $a['sueldoBasico'];
});

// Top 10 mejores pagados
$top10 = array_slice($empleados, 0, 10);

// Rangos salariales
$rangos = [
    '0-1M' => 0,
    '1M-2M' => 0,
    '2M-3M' => 0,
    '3M+' => 0
];

foreach($empleados as $e) {
    $sueldo = $e['sueldoBasico'];
    if ($sueldo < 1000000) $rangos['0-1M']++;
    elseif ($sueldo < 2000000) $rangos['1M-2M']++;
    elseif ($sueldo < 3000000) $rangos['2M-3M']++;
    else $rangos['3M+']++;
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Finanzas - M.S.C COMPANY</title>
  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="<?php echo base_url('css/styles.css'); ?>" rel="stylesheet">
  
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
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
          <i class="bi bi-graph-up text-info"></i> 
          Análisis Financiero
        </h2>
      </div>

      <!-- Rangos salariales -->
      <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white">
          <h5 class="mb-0">
            <i class="bi bi-bar-chart-steps"></i> Distribución por Rangos Salariales
          </h5>
        </div>
        <div class="card-body">
          <div class="row text-center">
            <div class="col-md-3">
              <div class="p-3 bg-light rounded">
                <h2 class="text-primary"><?php echo $rangos['0-1M']; ?></h2>
                <p class="mb-0 text-muted">$0 - $1M</p>
              </div>
            </div>
            <div class="col-md-3">
              <div class="p-3 bg-light rounded">
                <h2 class="text-success"><?php echo $rangos['1M-2M']; ?></h2>
                <p class="mb-0 text-muted">$1M - $2M</p>
              </div>
            </div>
            <div class="col-md-3">
              <div class="p-3 bg-light rounded">
                <h2 class="text-warning"><?php echo $rangos['2M-3M']; ?></h2>
                <p class="mb-0 text-muted">$2M - $3M</p>
              </div>
            </div>
            <div class="col-md-3">
              <div class="p-3 bg-light rounded">
                <h2 class="text-danger"><?php echo $rangos['3M+']; ?></h2>
                <p class="mb-0 text-muted">$3M+</p>
              </div>
            </div>
          </div>
          <div class="mt-4">
            <canvas id="chartRangos" height="80"></canvas>
          </div>
        </div>
      </div>

      <!-- Top 10 mejores pagados -->
      <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white">
          <h5 class="mb-0">
            <i class="bi bi-trophy-fill text-warning"></i> Top 10 Mejores Pagados
          </h5>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover">
              <thead class="table-light">
                <tr>
                  <th>#</th>
                  <th>Nombre</th>
                  <th>Documento</th>
                  <th>Sexo</th>
                  <th>Sueldo</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach($top10 as $index => $e): ?>
                <tr>
                  <td>
                    <?php if ($index == 0): ?>
                      <i class="bi bi-trophy-fill text-warning fs-5"></i>
                    <?php elseif ($index == 1): ?>
                      <i class="bi bi-award-fill text-secondary fs-5"></i>
                    <?php elseif ($index == 2): ?>
                      <i class="bi bi-award-fill text-danger fs-5"></i>
                    <?php else: ?>
                      <span class="badge bg-secondary"><?php echo $index + 1; ?></span>
                    <?php endif; ?>
                  </td>
                  <td><strong><?php echo e($e['nombre']); ?></strong></td>
                  <td><?php echo e($e['documento']); ?></td>
                  <td>
                    <?php if ($e['sexo'] == 'M'): ?>
                      <span class="badge bg-info">M</span>
                    <?php else: ?>
                      <span class="badge bg-danger">F</span>
                    <?php endif; ?>
                  </td>
                  <td><strong class="text-success"><?php echo formatMoney($e['sueldoBasico']); ?></strong></td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Todos los sueldos ordenados -->
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
          <h5 class="mb-0">
            <i class="bi bi-list-ol"></i> Todos los Sueldos (Ordenados)
          </h5>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-sm table-hover">
              <thead class="table-light">
                <tr>
                  <th>Nombre</th>
                  <th>Documento</th>
                  <th>Sueldo</th>
                  <th>% del Total</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                $totalNomina = array_sum(array_column($empleados, 'sueldoBasico'));
                foreach($empleados as $e): 
                  $porcentaje = $totalNomina > 0 ? ($e['sueldoBasico'] / $totalNomina * 100) : 0;
                ?>
                <tr>
                  <td><?php echo e($e['nombre']); ?></td>
                  <td><?php echo e($e['documento']); ?></td>
                  <td><strong class="text-success"><?php echo formatMoney($e['sueldoBasico']); ?></strong></td>
                  <td>
                    <div class="progress" style="height: 20px; min-width: 100px;">
                      <div class="progress-bar" role="progressbar" 
                           style="width: <?php echo $porcentaje; ?>%">
                        <?php echo number_format($porcentaje, 1); ?>%
                      </div>
                    </div>
                  </td>
                </tr>
                <?php endforeach; ?>
              </tbody>
              <tfoot class="table-light">
                <tr>
                  <th colspan="2">TOTAL NÓMINA</th>
                  <th class="text-success"><?php echo formatMoney($totalNomina); ?></th>
                  <th>100%</th>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>

    </main>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo base_url('js/main.js'); ?>"></script>

<script>
// Gráfico de rangos
const ctx = document.getElementById('chartRangos').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['$0 - $1M', '$1M - $2M', '$2M - $3M', '$3M+'],
        datasets: [{
            label: 'Cantidad de Empleados',
            data: [
                <?php echo $rangos['0-1M']; ?>,
                <?php echo $rangos['1M-2M']; ?>,
                <?php echo $rangos['2M-3M']; ?>,
                <?php echo $rangos['3M+']; ?>
            ],
            backgroundColor: ['#0d6efd', '#198754', '#ffc107', '#dc3545'],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});
</script>

</body>
</html>
