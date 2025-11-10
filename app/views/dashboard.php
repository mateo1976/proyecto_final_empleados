<?php
// app/views/dashboard.php
require_once __DIR__ . '/../models/Empleado.php';
$empleadoModel = new Empleado();

$empleados = $empleadoModel->all();
$total = count($empleados);
$totNom = $empleadoModel->totalNomina()['total'] ?? 0;
$porSexo = $empleadoModel->totalPorSexo();

// Calcular promedios
$promedioSueldo = $total > 0 ? $totNom / $total : 0;

// Cumpleaños del mes actual
$mesActual = date('n');
$cumpleMesActual = $empleadoModel->cumpleanosPorMes($mesActual);

// Estadísticas por sexo
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
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard - M.S.C COMPANY</title>
  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="<?php echo base_url('css/styles.css'); ?>" rel="stylesheet">
  
  <!-- Chart.js para gráficos -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
</head>
<body>

<?php include __DIR__ . '/partials/header.php'; ?>

<div class="container-fluid">
  <div class="row">
    <?php include __DIR__ . '/partials/sidebar.php'; ?>
    
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      
      <!-- Encabezado -->
      <div class="d-flex justify-content-between flex-wrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">
          <i class="bi bi-speedometer2 text-primary"></i> 
          Dashboard
        </h1>
        <div class="btn-toolbar mb-2 mb-md-0">
          <div class="btn-group me-2">
            <a href="<?php echo route('empleados'); ?>" class="btn btn-sm btn-outline-primary">
              <i class="bi bi-people"></i> Ver Empleados
            </a>
            <a href="<?php echo route('empleados_create'); ?>" class="btn btn-sm btn-primary">
              <i class="bi bi-plus-circle"></i> Nuevo
            </a>
          </div>
        </div>
      </div>

      <!-- Mensaje de bienvenida -->
      <div class="alert alert-info alert-dismissible fade show" role="alert">
        <h5 class="alert-heading">
          <i class="bi bi-emoji-smile"></i> 
          ¡Bienvenido, <?php echo e(auth()); ?>!
        </h5>
        <p class="mb-0">Panel de control principal. Aquí encontrarás un resumen de la información más importante.</p>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>

      <!-- Tarjetas de estadísticas principales -->
      <div class="row g-3 mb-4">
        <div class="col-md-3">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
              <div class="display-1 text-primary mb-2">
                <i class="bi bi-people-fill"></i>
              </div>
              <h3 class="counter mb-0"><?php echo $total; ?></h3>
              <p class="text-muted mb-0">Total Empleados</p>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
              <div class="display-1 text-success mb-2">
                <i class="bi bi-cash-stack"></i>
              </div>
              <h3 class="counter mb-0"><?php echo formatMoney($totNom, 0); ?></h3>
              <p class="text-muted mb-0">Nómina Total</p>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
              <div class="display-1 text-warning mb-2">
                <i class="bi bi-graph-up-arrow"></i>
              </div>
              <h3 class="counter mb-0"><?php echo formatMoney($promedioSueldo, 0); ?></h3>
              <p class="text-muted mb-0">Sueldo Promedio</p>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
              <div class="display-1 text-danger mb-2">
                <i class="bi bi-gift-fill"></i>
              </div>
              <h3 class="counter mb-0"><?php echo count($cumpleMesActual); ?></h3>
              <p class="text-muted mb-0">Cumpleaños este mes</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Fila de gráficos -->
      <div class="row g-3 mb-4">
        <!-- Gráfico de distribución por sexo -->
        <div class="col-md-6">
          <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
              <h5 class="mb-0">
                <i class="bi bi-pie-chart"></i> Distribución por Sexo
              </h5>
            </div>
            <div class="card-body">
              <canvas id="chartSexo" height="200"></canvas>
            </div>
          </div>
        </div>

        <!-- Gráfico de nómina por sexo -->
        <div class="col-md-6">
          <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
              <h5 class="mb-0">
                <i class="bi bi-bar-chart"></i> Nómina por Sexo
              </h5>
            </div>
            <div class="card-body">
              <canvas id="chartNomina" height="200"></canvas>
            </div>
          </div>
        </div>
      </div>

      <!-- Estadísticas detalladas -->
      <div class="row g-3 mb-4">
        <div class="col-md-6">
          <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
              <h5 class="mb-0">
                <i class="bi bi-gender-male text-info"></i> Empleados Masculinos
              </h5>
            </div>
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <h2 class="mb-0"><?php echo $masculinos; ?></h2>
                  <p class="text-muted mb-0">Total de empleados</p>
                </div>
                <div class="text-end">
                  <h4 class="text-success mb-0"><?php echo formatMoney($sueldoM, 0); ?></h4>
                  <p class="text-muted mb-0">Nómina total</p>
                </div>
              </div>
              <div class="progress mt-3" style="height: 25px;">
                <div class="progress-bar bg-info" role="progressbar" 
                     style="width: <?php echo $total > 0 ? ($masculinos/$total*100) : 0; ?>%">
                  <?php echo $total > 0 ? round($masculinos/$total*100, 1) : 0; ?>%
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
              <h5 class="mb-0">
                <i class="bi bi-gender-female text-danger"></i> Empleados Femeninos
              </h5>
            </div>
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <h2 class="mb-0"><?php echo $femeninos; ?></h2>
                  <p class="text-muted mb-0">Total de empleados</p>
                </div>
                <div class="text-end">
                  <h4 class="text-success mb-0"><?php echo formatMoney($sueldoF, 0); ?></h4>
                  <p class="text-muted mb-0">Nómina total</p>
                </div>
              </div>
              <div class="progress mt-3" style="height: 25px;">
                <div class="progress-bar bg-danger" role="progressbar" 
                     style="width: <?php echo $total > 0 ? ($femeninos/$total*100) : 0; ?>%">
                  <?php echo $total > 0 ? round($femeninos/$total*100, 1) : 0; ?>%
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Cumpleaños próximos -->
      <?php if (!empty($cumpleMesActual)): ?>
      <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white">
          <h5 class="mb-0">
            <i class="bi bi-calendar-event text-warning"></i> 
            Cumpleaños en <?php echo strftime('%B', mktime(0, 0, 0, $mesActual, 1)); ?>
          </h5>
        </div>
        <div class="card-body">
          <div class="row">
            <?php foreach(array_slice($cumpleMesActual, 0, 4) as $cumple): ?>
            <div class="col-md-3 mb-2">
              <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                  <i class="bi bi-gift fs-2 text-warning"></i>
                </div>
                <div class="flex-grow-1 ms-3">
                  <strong><?php echo e($cumple['nombre']); ?></strong><br>
                  <small class="text-muted"><?php echo formatDate($cumple['fechaNacimiento']); ?></small>
                </div>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
          <?php if (count($cumpleMesActual) > 4): ?>
          <div class="text-center mt-3">
            <a href="<?php echo route('cumpleanos'); ?>" class="btn btn-sm btn-outline-warning">
              Ver todos (<?php echo count($cumpleMesActual); ?>)
            </a>
          </div>
          <?php endif; ?>
        </div>
      </div>
      <?php endif; ?>

      <!-- Accesos rápidos -->
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
          <h5 class="mb-0">
            <i class="bi bi-lightning"></i> Accesos Rápidos
          </h5>
        </div>
        <div class="card-body">
          <div class="row g-3">
            <div class="col-md-3">
              <a href="<?php echo route('empleados_create'); ?>" class="btn btn-outline-primary w-100 py-3">
                <i class="bi bi-person-plus fs-3 d-block mb-2"></i>
                Nuevo Empleado
              </a>
            </div>
            <div class="col-md-3">
              <a href="<?php echo route('cumpleanos'); ?>" class="btn btn-outline-warning w-100 py-3">
                <i class="bi bi-gift fs-3 d-block mb-2"></i>
                Cumpleaños
              </a>
            </div>
            <div class="col-md-3">
              <a href="<?php echo route('nomina'); ?>" class="btn btn-outline-success w-100 py-3">
                <i class="bi bi-receipt fs-3 d-block mb-2"></i>
                Nómina
              </a>
            </div>
            <div class="col-md-3">
              <a href="<?php echo route('finanzas'); ?>" class="btn btn-outline-info w-100 py-3">
                <i class="bi bi-graph-up fs-3 d-block mb-2"></i>
                Finanzas
              </a>
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
// Gráfico de distribución por sexo (Pie)
const ctxSexo = document.getElementById('chartSexo').getContext('2d');
new Chart(ctxSexo, {
    type: 'doughnut',
    data: {
        labels: ['Masculino', 'Femenino'],
        datasets: [{
            data: [<?php echo $masculinos; ?>, <?php echo $femeninos; ?>],
            backgroundColor: ['#0dcaf0', '#dc3545'],
            borderWidth: 2,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

// Gráfico de nómina por sexo (Bar)
const ctxNomina = document.getElementById('chartNomina').getContext('2d');
new Chart(ctxNomina, {
    type: 'bar',
    data: {
        labels: ['Masculino', 'Femenino'],
        datasets: [{
            label: 'Nómina Total ($)',
            data: [<?php echo $sueldoM; ?>, <?php echo $sueldoF; ?>],
            backgroundColor: ['#0dcaf0', '#dc3545'],
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
