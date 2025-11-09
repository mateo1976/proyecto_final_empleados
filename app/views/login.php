<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Login - M.S.C COMPANY</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="../css/styles.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card shadow-lg p-4" style="width:420px;">
      <h3 class="card-title text-center mb-3">M.S.C COMPANY</h3>
      <form method="post" action="login_action.php">
        <div class="mb-3">
          <label class="form-label">Correo</label>
          <input type="email" name="email" class="form-control" required value="admin@gmail.com">
        </div>
        <div class="mb-3">
          <label class="form-label">Contraseña</label>
          <input type="password" name="password" class="form-control" required value="admin123">
        </div>
        <button class="btn btn-primary w-100">Ingresar</button>
      </form>
    </div>
  </div>
</body>
</html>
