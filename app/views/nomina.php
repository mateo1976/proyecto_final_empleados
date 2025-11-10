<?php
// app/views/nomina.php
require_once __DIR__ . '/../models/Empleado.php';
$empleadoModel = new Empleado();

$total = $empleadoModel->totalNomina()['total'] ?? 0;
$porSexo = $empleadoModel->totalPorSexo();
$empleados = $empleadoModel->all();

// Salario mínimo de referencia
$salario_minimo = 1400000;
$mayorSM = $empleadoModel->countMayorSalario($salario_minimo)['cantidad'] ?? 0;

// Calcular estadísticas
$masculinos = 0;
$femeninos = 0;
$sueldoM = 0;
$sueldoF = 0;

foreach($porSexo as $ps) {
    if ($ps['sexo'] == 'M') {
        $masculinos = $ps['cantidad'];
        $sueldoM = $ps['totalSueldo'];
    } else {
        $femeninos = $ps['cantidad'];
        $sueldoF = $ps['totalSueldo'];
    }
}

$promedioM = $masculinos > 0 ? $sueldoM / $masculinos : 0;
$promedioF = $femeninos > 0 ? $sueldoF / $femeninos : 0;
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Nómina - M.S.C COMPANY</title>
  
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
          <i class="bi bi-receipt text-success"></i> 
          Análisis de Nómina
        </h2>
      </div>

      <!-- Tarjetas principales -->
      <div class="row g-3 mb-4">
        <div class="col-md-3">
          <div class="card border-0 shadow-sm text-center">
            <div class="card-body">
              <i class="bi bi-cash-stack fs-1 text-success"></i>
              <h3 class="mt-2"><?php echo formatMoney($total, 0); ?></h3>
              <p class="text-muted mb-0">Nómina Total</p>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card border-0 shadow-sm text-center">
            <div class="card-body">
              <i class="bi bi-people fs-1 text-primary"></i>
              <h3 class="mt-2"><?php echo count($empleados); ?></h3>
              <p class="text-muted mb-0">Total Empleados</p>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card border-0 shadow-sm text-center">
            <div class="card-body">
              <i class="bi bi-graph-up-arrow fs-1 text-warning"></i>
              <h3 class="mt-2"><?php echo formatMoney($total / max(count($empleados), 1), 0); ?></h3>
              <p class="text-muted mb-0">Sueldo Promedio</p>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card border-0 shadow-sm text-center">
            <div class="card-body">
              <i class="bi bi-arrow-up-circle fs-1 text-info"></i>
              <h3 class="mt-2"><?php echo $mayorSM; ?></h3>
              <p class="text-muted mb-0">Sobre salario mínimo</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Análisis por sexo -->
      <div class="row g-3 mb-4">
        <div class="col-md-6">
          <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
              <h5 class="mb-0">
                <i class="bi bi-gender-male text-info"></i> Nómina Masculina
              </h5>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-6">
                  <p class="text-muted mb-1">Empleados</p>
                  <h4><?php echo $masculinos; ?></h4>
                </div>
                <div class="col-6">
                  <p class="text-muted mb-1">Total Nómina</p>
                  <h4 class="text-success"><?php echo formatMoney($sueldoM, 0); ?></h4>
                </div>
                <div class="col-12 mt-3">
                  <p class="text-muted mb-1">Sueldo Promedio</p>
                  <h4 class="text-warning"><?php echo formatMoney($promedioM, 0); ?></h4>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
              <h5 class="mb-0">
                <i class="bi bi-gender-female text-danger"></i> Nómina Femenina
              </h5>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-6">
                  <p class="text-muted mb-1">Empleados</p>
                  <h4><?php echo $femeninos; ?></h4>
                </div>
                <div class="col-6">
                  <p class="text-muted mb-1">Total Nómina</p>
                  <h4 class="text-success"><?php echo formatMoney($sueldoF, 0); ?></h4>
                </div>
                <div class="col-12 mt-3">
                  <p class="text-muted mb-1">Sueldo Promedio</p>
                  <h4 class="text-warning"><?php echo formatMoney($promedioF, 0); ?></h4>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Gráfico comparativo -->
      <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white">
          <h5 class="mb-0">
            <i class="bi bi-bar-chart"></i> Comparativa de Nómina
          </h5>
        </div>
        <div class="card-body">
          <canvas id="chartComparativa" height="100"></canvas>
        </div>
      </div>

      <!-- Información del salario mínimo -->
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
          <h5 class="mb-0">
            <i class="bi bi-info-circle"></i> Referencia Salario Mínimo
          </h5>
        </div>
        <div class="card-body">
          <div class="row align-items-center">
            <div class="col-md-6">
              <p class="mb-2">
                <strong>Salario Mínimo Legal:</strong> <?php echo formatMoney($salario_minimo, 0); ?>
              </p>
              <p class="mb-2">
                <strong>Empleados que ganan más:</strong> 
                <span class="badge bg-success fs-6"><?php echo $mayorSM; ?> empleados</span>
              </p>
              <p class="mb-0">
                <strong>Porcentaje:</strong> 
                <?php echo count($empleados) > 0 ? round($mayorSM / count($empleados) * 100, 1) : 0; ?>%
              </p>
            </div>
            <div class="col-md-6">
              <div class="progress" style="height: 30px;">
                <div class="progress-bar bg-success" role="progressbar" 
                     style="width: <?php echo count($empleados) > 0 ? ($mayorSM / count($empleados) * 100) : 0; ?>%">
                  <?php echo count($empleados) > 0 ? round($mayorSM / count($empleados) * 100, 1) : 0; ?>%
                </div>
              </div>
              <small class="text-muted mt-2 d-block">
                Porcentaje de empleados con sueldo superior al salario mínimo
              </small>
            </div>
          </div>
        </div>
      </div>

    </main>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo base_url('js/main.js'); ?>"></script>

<script>
// Gráfico comparativo
const ctx = document.getElementById('chartComparativa').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Masculino', 'Femenino'],
        datasets: [
            {
                label: 'Cantidad Empleados',
                data: [<?php echo $masculinos; ?>, <?php echo $femeninos; ?>],
                backgroundColor: '#0dcaf0',
                yAxisID: 'y'
            },
            {
                label: 'Nómina Total ($)',
                data: [<?php echo $sueldoM; ?>, <?php echo $sueldoF; ?>],
                backgroundColor: '#198754',
                yAxisID: 'y1'
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                type: 'linear',
                position: 'left',
                title: {
                    display: true,
                    text: 'Cantidad'
                }
            },
            y1: {
                type: 'linear',
                position: 'right',
                title: {
                    display: true,
                    text: 'Nómina ($)'
                },
                grid: {
                    drawOnChartArea: false
                },
                ticks: {
                    callback: function(value) {
                        return '$' + value.toLocaleString();
                    }
                }
            }
        }
    }
});
</script>

</body>
</html>
