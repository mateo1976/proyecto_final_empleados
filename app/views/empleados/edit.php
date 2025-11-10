<?php
// app/views/empleados/edit.php
$errors = getErrors();
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Editar Empleado - M.S.C COMPANY</title>
  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="<?php echo base_url('css/styles.css'); ?>" rel="stylesheet">
</head>
<body>

<?php include __DIR__ . '/../partials/header.php'; ?>

<div class="container-fluid">
  <div class="row">
    <?php include __DIR__ . '/../partials/sidebar.php'; ?>
    
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      
      <!-- Breadcrumb -->
      <nav aria-label="breadcrumb" class="mt-3">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?php echo route('dashboard'); ?>">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="<?php echo route('empleados'); ?>">Empleados</a></li>
          <li class="breadcrumb-item active">Editar</li>
        </ol>
      </nav>

      <!-- Encabezado -->
      <div class="d-flex justify-content-between align-items-center pb-2 mb-3 border-bottom">
        <h2>
          <i class="bi bi-pencil-square text-warning"></i> 
          Editar Empleado: <?php echo e($empleado['nombre']); ?>
        </h2>
        <a href="<?php echo route('empleados'); ?>" class="btn btn-outline-secondary">
          <i class="bi bi-arrow-left"></i> Volver
        </a>
      </div>

      <!-- Mostrar errores si existen -->
      <?php if (!empty($errors)): ?>
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <h5 class="alert-heading"><i class="bi bi-exclamation-triangle"></i> Errores de validación:</h5>
        <ul class="mb-0">
          <?php foreach($errors as $error): ?>
            <li><?php echo e($error); ?></li>
          <?php endforeach; ?>
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
      <?php endif; ?>

      <!-- Formulario -->
      <div class="card shadow-sm">
        <div class="card-header bg-warning text-dark">
          <h5 class="mb-0"><i class="bi bi-clipboard-data"></i> Información del Empleado</h5>
        </div>
        <div class="card-body">
          <form method="post" action="<?php echo route('empleados_update'); ?>" id="formEmpleado">
            
            <?php echo csrf_field(); ?>
            <input type="hidden" name="id" value="<?php echo e($empleado['id']); ?>">

            <div class="row">
              <!-- Documento -->
              <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">
                  <i class="bi bi-card-text"></i> Documento *
                </label>
                <input 
                  type="text" 
                  name="documento" 
                  class="form-control <?php echo !empty($errors) ? 'is-invalid' : ''; ?>" 
                  value="<?php echo old('documento', $empleado['documento']); ?>"
                  required>
              </div>

              <!-- Nombre -->
              <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">
                  <i class="bi bi-person"></i> Nombre Completo *
                </label>
                <input 
                  type="text" 
                  name="nombre" 
                  class="form-control <?php echo !empty($errors) ? 'is-invalid' : ''; ?>" 
                  value="<?php echo old('nombre', $empleado['nombre']); ?>"
                  required>
              </div>

              <!-- Sexo -->
              <div class="col-md-4 mb-3">
                <label class="form-label fw-bold">
                  <i class="bi bi-gender-ambiguous"></i> Sexo *
                </label>
                <select name="sexo" class="form-select" required>
                  <option value="M" <?php echo old('sexo', $empleado['sexo']) == 'M' ? 'selected' : ''; ?>>
                    Masculino
                  </option>
                  <option value="F" <?php echo old('sexo', $empleado['sexo']) == 'F' ? 'selected' : ''; ?>>
                    Femenino
                  </option>
                </select>
              </div>

              <!-- Teléfono -->
              <div class="col-md-4 mb-3">
                <label class="form-label fw-bold">
                  <i class="bi bi-telephone"></i> Teléfono
                </label>
                <input 
                  type="tel" 
                  name="telefono" 
                  class="form-control" 
                  value="<?php echo old('telefono', $empleado['telefono']); ?>">
              </div>

              <!-- Correo -->
              <div class="col-md-4 mb-3">
                <label class="form-label fw-bold">
                  <i class="bi bi-envelope"></i> Correo Electrónico
                </label>
                <input 
                  type="email" 
                  name="correo" 
                  class="form-control" 
                  value="<?php echo old('correo', $empleado['correo']); ?>">
              </div>

              <!-- Domicilio -->
              <div class="col-md-12 mb-3">
                <label class="form-label fw-bold">
                  <i class="bi bi-geo-alt"></i> Domicilio
                </label>
                <input 
                  type="text" 
                  name="domicilio" 
                  class="form-control" 
                  value="<?php echo old('domicilio', $empleado['domicilio']); ?>">
              </div>

              <!-- Fecha Ingreso -->
              <div class="col-md-4 mb-3">
                <label class="form-label fw-bold">
                  <i class="bi bi-calendar-check"></i> Fecha de Ingreso
                </label>
                <input 
                  type="date" 
                  name="fechaIngreso" 
                  class="form-control" 
                  value="<?php echo old('fechaIngreso', $empleado['fechaIngreso']); ?>">
              </div>

              <!-- Fecha Nacimiento -->
              <div class="col-md-4 mb-3">
                <label class="form-label fw-bold">
                  <i class="bi bi-gift"></i> Fecha de Nacimiento
                </label>
                <input 
                  type="date" 
                  name="fechaNacimiento" 
                  class="form-control" 
                  value="<?php echo old('fechaNacimiento', $empleado['fechaNacimiento']); ?>"
                  max="<?php echo date('Y-m-d'); ?>">
              </div>

              <!-- Sueldo -->
              <div class="col-md-4 mb-3">
                <label class="form-label fw-bold">
                  <i class="bi bi-cash"></i> Sueldo Básico *
                </label>
                <div class="input-group">
                  <span class="input-group-text">$</span>
                  <input 
                    type="number" 
                    name="sueldoBasico" 
                    class="form-control" 
                    step="0.01" 
                    min="0"
                    value="<?php echo old('sueldoBasico', $empleado['sueldoBasico']); ?>"
                    required>
                </div>
              </div>
            </div>

            <hr class="my-4">

            <!-- Info adicional -->
            <div class="row mb-3">
              <div class="col-md-6">
                <small class="text-muted">
                  <i class="bi bi-clock-history"></i> 
                  Creado: <?php echo formatDate($empleado['created_at'], 'd/m/Y H:i'); ?>
                </small>
              </div>
              <div class="col-md-6 text-end">
                <small class="text-muted">
                  <i class="bi bi-pencil"></i> 
                  Última actualización: <?php echo formatDate($empleado['updated_at'], 'd/m/Y H:i'); ?>
                </small>
              </div>
            </div>

            <!-- Botones -->
            <div class="d-flex justify-content-between">
              <a href="<?php echo route('empleados'); ?>" class="btn btn-outline-secondary">
                <i class="bi bi-x-circle"></i> Cancelar
              </a>
              <button type="submit" class="btn btn-warning btn-lg">
                <i class="bi bi-save"></i> Guardar Cambios
              </button>
            </div>

          </form>
        </div>
      </div>

      <div class="text-muted mt-3">
        <small>* Campos obligatorios</small>
      </div>

    </main>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo base_url('js/main.js'); ?>"></script>

</body>
</html>
