<?php
// app/views/finanzas.php - CON FUNCIONALIDAD DE AUMENTOS
require_once __DIR__ . '/../models/Empleado.php';
require_once __DIR__ . '/../models/Aumento.php';

$empleadoModel = new Empleado();
$aumentoModel = new Aumento();
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

// Historial de aumentos
$historial = $aumentoModel->obtenerHistorial(10);
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
          Gestión Financiera
        </h2>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAumentos">
          <i class="bi bi-graph-up-arrow"></i> Aplicar Aumentos
        </button>
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

      <!-- Historial de aumentos -->
      <?php if (!empty($historial)): ?>
      <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white">
          <h5 class="mb-0">
            <i class="bi bi-clock-history"></i> Historial de Aumentos Recientes
          </h5>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-sm">
              <thead>
                <tr>
                  <th>Fecha</th>
                  <th>Empleado</th>
                  <th>Sueldo Anterior</th>
                  <th>Sueldo Nuevo</th>
                  <th>% Aumento</th>
                  <th>Tipo</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach($historial as $h): ?>
                <tr>
                  <td><?php echo formatDate($h['fecha'], 'd/m/Y H:i'); ?></td>
                  <td><?php echo e($h['empleado_nombre']); ?></td>
                  <td><?php echo formatMoney($h['sueldo_anterior']); ?></td>
                  <td class="text-success"><strong><?php echo formatMoney($h['sueldo_nuevo']); ?></strong></td>
                  <td><span class="badge bg-success"><?php echo $h['porcentaje']; ?>%</span></td>
                  <td><span class="badge bg-info"><?php echo ucfirst($h['tipo_aumento']); ?></span></td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <?php endif; ?>

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
                  <th><input type="checkbox" id="selectAll"></th>
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
                  <td><input type="checkbox" class="empleado-check" value="<?php echo $e['id']; ?>"></td>
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
                  <th colspan="3">TOTAL NÓMINA</th>
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

<!-- Modal de Aumentos -->
<div class="modal fade" id="modalAumentos" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title"><i class="bi bi-graph-up-arrow"></i> Aplicar Aumentos Salariales</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        
        <!-- Tabs -->
        <ul class="nav nav-tabs mb-3" id="aumentoTabs" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active" id="global-tab" data-bs-toggle="tab" data-bs-target="#global" type="button">
              <i class="bi bi-people-fill"></i> Todos
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="selectivo-tab" data-bs-toggle="tab" data-bs-target="#selectivo" type="button">
              <i class="bi bi-check-square"></i> Seleccionados
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="rango-tab" data-bs-toggle="tab" data-bs-target="#rango" type="button">
              <i class="bi bi-sliders"></i> Por Rango
            </button>
          </li>
        </ul>

        <div class="tab-content" id="aumentoTabsContent">
          
          <!-- Tab: Aumento Global -->
          <div class="tab-pane fade show active" id="global" role="tabpanel">
            <form action="<?php echo route('aumentos_aplicar'); ?>" method="post">
              <?php echo csrf_field(); ?>
              <input type="hidden" name="tipo" value="global">
              
              <div class="alert alert-info">
                <i class="bi bi-info-circle"></i> Este aumento se aplicará a <strong>TODOS</strong> los empleados.
              </div>
              
              <div class="mb-3">
                <label class="form-label fw-bold">Porcentaje de Aumento (%)</label>
                <input type="number" name="porcentaje" class="form-control" min="0.01" step="0.01" required placeholder="Ej: 10">
                <small class="text-muted">Ingresa el porcentaje de aumento (sin el símbolo %)</small>
              </div>
              
              <button type="submit" class="btn btn-success w-100">
                <i class="bi bi-check-circle"></i> Aplicar Aumento Global
              </button>
            </form>
          </div>

          <!-- Tab: Aumento Selectivo -->
          <div class="tab-pane fade" id="selectivo" role="tabpanel">
            <form action="<?php echo route('aumentos_aplicar'); ?>" method="post">
              <?php echo csrf_field(); ?>
              <input type="hidden" name="tipo" value="selectivo">
              
              <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle"></i> Marca los empleados en la tabla principal antes de aplicar.
              </div>
              
              <div id="selectedCount" class="alert alert-secondary">
                <strong>0</strong> empleados seleccionados
              </div>
              
              <div class="mb-3">
                <label class="form-label fw-bold">Porcentaje de Aumento (%)</label>
                <input type="number" name="porcentaje" class="form-control" min="0.01" step="0.01" required placeholder="Ej: 10">
              </div>
              
              <input type="hidden" name="empleados_ids" id="empleadosIdsInput">
              
              <button type="submit" class="btn btn-success w-100">
                <i class="bi bi-check-circle"></i> Aplicar a Seleccionados
              </button>
            </form>
          </div>

          <!-- Tab: Aumento por Rango -->
          <div class="tab-pane fade" id="rango" role="tabpanel">
            <form action="<?php echo route('aumentos_aplicar'); ?>" method="post">
              <?php echo csrf_field(); ?>
              <input type="hidden" name="tipo" value="rango">
              
              <div class="alert alert-info">
                <i class="bi bi-info-circle"></i> Define un rango salarial para aplicar el aumento.
              </div>
              
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label fw-bold">Sueldo Mínimo ($)</label>
                  <input type="number" name="sueldo_min" class="form-control" step="0.01" required placeholder="0">
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label fw-bold">Sueldo Máximo ($)</label>
                  <input type="number" name="sueldo_max" class="form-control" step="0.01" required placeholder="999999999">
                </div>
              </div>
              
              <div class="mb-3">
                <label class="form-label fw-bold">Porcentaje de Aumento (%)</label>
                <input type="number" name="porcentaje" class="form-control" min="0.01" step="0.01" required placeholder="Ej: 10">
              </div>
              
              <button type="submit" class="btn btn-success w-100">
                <i class="bi bi-check-circle"></i> Aplicar por Rango
              </button>
            </form>
          </div>

        </div>

      </div>
    </div>
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

// Seleccionar/deseleccionar todos
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.empleado-check');
    checkboxes.forEach(cb => cb.checked = this.checked);
    updateSelectedCount();
});

// Actualizar contador de seleccionados
document.querySelectorAll('.empleado-check').forEach(cb => {
    cb.addEventListener('change', updateSelectedCount);
});

function updateSelectedCount() {
    const selected = document.querySelectorAll('.empleado-check:checked');
    const ids = Array.from(selected).map(cb => cb.value);
    
    document.getElementById('selectedCount').innerHTML = `<strong>${selected.length}</strong> empleados seleccionados`;
    document.getElementById('empleadosIdsInput').value = ids.join(',');
}
</script>

</body>
</html>
