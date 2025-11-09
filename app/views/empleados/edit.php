<?php
// app/views/empleados/edit.php
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Editar Empleado - M.S.C COMPANY</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="../css/styles.css" rel="stylesheet">
</head>
<body>
<?php include __DIR__ . '/../partials/header.php'; ?>
<div class="container-fluid">
  <div class="row">
    <?php include __DIR__ . '/../partials/sidebar.php'; ?>
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      <h2 class="mt-3">Editar Empleado</h2>
      <div class="card">
        <div class="card-body">
          <form method="post" action="index.php?route=empleados_update">
            <input type="hidden" name="id" value="<?php echo $empleado['id']; ?>">
            <div class="row">
              <div class="col-md-6 mb-3">
                <label>Documento</label>
                <input name="documento" class="form-control" required value="<?php echo htmlspecialchars($empleado['documento']); ?>">
              </div>
              <div class="col-md-6 mb-3">
                <label>Nombre</label>
                <input name="nombre" class="form-control" required value="<?php echo htmlspecialchars($empleado['nombre']); ?>">
              </div>
              <div class="col-md-4 mb-3">
                <label>Sexo</label>
                <select name="sexo" class="form-select">
                  <option value="M" <?php echo $empleado['sexo']=='M'?'selected':''; ?>>Masculino</option>
                  <option value="F" <?php echo $empleado['sexo']=='F'?'selected':''; ?>>Femenino</option>
                </select>
              </div>
              <div class="col-md-8 mb-3">
                <label>Domicilio</label>
                <input name="domicilio" class="form-control" value="<?php echo htmlspecialchars($empleado['domicilio']); ?>">
              </div>
              <div class="col-md-6 mb-3">
                <label>Teléfono</label>
                <input name="telefono" class="form-control" value="<?php echo htmlspecialchars($empleado['telefono']); ?>">
              </div>
              <div class="col-md-6 mb-3">
                <label>Correo</label>
                <input name="correo" type="email" class="form-control" value="<?php echo htmlspecialchars($empleado['correo']); ?>">
              </div>
              <div class="col-md-6 mb-3">
                <label>Fecha Ingreso</label>
                <input name="fechaIngreso" type="date" class="form-control" value="<?php echo $empleado['fechaIngreso']; ?>">
              </div>
              <div class="col-md-6 mb-3">
                <label>Fecha Nacimiento</label>
                <input name="fechaNacimiento" type="date" class="form-control" value="<?php echo $empleado['fechaNacimiento']; ?>">
              </div>
              <div class="col-md-6 mb-3">
                <label>Sueldo Básico</label>
                <input name="sueldoBasico" type="number" step="0.01" class="form-control" value="<?php echo $empleado['sueldoBasico']; ?>">
              </div>
            </div>
            <button class="btn btn-primary">Guardar cambios</button>
          </form>
        </div>
      </div>
    </main>
  </div>
</div>
<script src="../js/main.js"></script>
</body>
</html>
