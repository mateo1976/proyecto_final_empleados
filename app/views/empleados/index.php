<?php
// app/views/empleados/index.php
$empleados = $empleados ?? [];
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Empleados - M.S.C COMPANY</title>
  
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <!-- DataTables CSS -->
  <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
  <!-- Custom CSS -->
  <link href="<?php echo base_url('css/styles.css'); ?>" rel="stylesheet">
</head>
<body>

<?php include __DIR__ . '/../partials/header.php'; ?>

<div class="container-fluid">
  <div class="row">
    
    <?php include __DIR__ . '/../partials/sidebar.php'; ?>
    
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      
      <!-- Encabezado -->
      <div class="d-flex justify-content-between flex-wrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h2 class="h2">
          <i class="bi bi-people-fill text-primary"></i> 
          Gestión de Empleados
        </h2>
        <div class="btn-toolbar mb-2 mb-md-0">
          <a href="<?php echo route('empleados_create'); ?>" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Nuevo empleado
          </a>
        </div>
      </div>

      <!-- Estadísticas rápidas -->
      <div class="row mb-4">
        <div class="col-md-3">
          <div class="card text-center shadow-sm">
            <div class="card-body">
              <i class="bi bi-people fs-1 text-primary"></i>
              <h3 class="mt-2"><?php echo count($empleados); ?></h3>
              <p class="text-muted mb-0">Total Empleados</p>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card text-center shadow-sm">
            <div class="card-body">
              <i class="bi bi-gender-male fs-1 text-info"></i>
              <h3 class="mt-2"><?php echo count(array_filter($empleados, fn($e) => $e['sexo'] === 'M')); ?></h3>
              <p class="text-muted mb-0">Masculino</p>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card text-center shadow-sm">
            <div class="card-body">
              <i class="bi bi-gender-female fs-1 text-danger"></i>
              <h3 class="mt-2"><?php echo count(array_filter($empleados, fn($e) => $e['sexo'] === 'F')); ?></h3>
              <p class="text-muted mb-0">Femenino</p>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card text-center shadow-sm">
            <div class="card-body">
              <i class="bi bi-cash-stack fs-1 text-success"></i>
              <h3 class="mt-2"><?php echo formatMoney(array_sum(array_column($empleados, 'sueldoBasico'))); ?></h3>
              <p class="text-muted mb-0">Nómina Total</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Tabla de empleados -->
      <div class="card shadow-sm">
        <div class="card-header bg-white">
          <h5 class="mb-0">
            <i class="bi bi-table"></i> Listado de Empleados
          </h5>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table id="tablaEmpleados" class="table table-hover table-striped" style="width:100%">
              <thead class="table-light">
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
                  <td><span class="badge bg-secondary"><?php echo e($e['documento']); ?></span></td>
                  <td><strong><?php echo e($e['nombre']); ?></strong></td>
                  <td>
                    <?php if ($e['sexo'] === 'M'): ?>
                      <span class="badge bg-info"><i class="bi bi-gender-male"></i> M</span>
                    <?php else: ?>
                      <span class="badge bg-danger"><i class="bi bi-gender-female"></i> F</span>
                    <?php endif; ?>
                  </td>
                  <td><?php echo e($e['telefono']); ?></td>
                  <td><?php echo e($e['correo']); ?></td>
                  <td><strong class="text-success"><?php echo formatMoney($e['sueldoBasico']); ?></strong></td>
                  <td>
                    <div class="btn-group btn-group-sm" role="group">
                      <a class="btn btn-outline-primary" 
                         href="<?php echo route('empleados_edit', ['id' => $e['id']]); ?>"
                         title="Editar">
                        <i class="bi bi-pencil-square"></i>
                      </a>
                      <a class="btn btn-outline-danger" 
                         href="<?php echo route('empleados_delete', ['id' => $e['id']]); ?>" 
                         onclick="return confirm('¿Está seguro de eliminar a <?php echo e($e['nombre']); ?>?')"
                         title="Eliminar">
                        <i class="bi bi-trash"></i>
                      </a>
                    </div>
                  </td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </main>
  </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<!-- Bootstrap Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<!-- Custom JS -->
<script src="<?php echo base_url('js/main.js'); ?>"></script>

<script>
$(document).ready(function() {
    $('#tablaEmpleados').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
        },
        pageLength: 10,
        order: [[1, 'asc']], // Ordenar por nombre
        responsive: true,
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
             '<"row"<"col-sm-12"tr>>' +
             '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>'
    });
});
</script>

</body>
</html>
