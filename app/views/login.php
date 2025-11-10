<?php
// app/views/login.php
$errors = getErrors();
$flash = getFlash();
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login - M.S.C COMPANY</title>
  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="<?php echo base_url('css/styles.css'); ?>" rel="stylesheet">
  
  <style>
    body {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: 100vh;
    }
    .login-card {
      backdrop-filter: blur(10px);
      background: rgba(255, 255, 255, 0.95);
      border-radius: 20px;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    }
    .logo-icon {
      width: 80px;
      height: 80px;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 40px;
      color: white;
      margin: 0 auto 20px;
    }
  </style>
</head>
<body class="bg-light">
  
  <div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="col-md-5 col-lg-4">
      
      <div class="login-card p-5">
        <!-- Logo -->
        <div class="logo-icon">
          <i class="bi bi-building"></i>
        </div>
        
        <!-- Título -->
        <h3 class="text-center mb-2 fw-bold">M.S.C COMPANY</h3>
        <p class="text-center text-muted mb-4">Sistema de Gestión de Empleados</p>
        
        <!-- Mensaje flash -->
        <?php if ($flash): ?>
        <div class="alert alert-<?php echo $flash['type'] === 'success' ? 'success' : 'danger'; ?> alert-dismissible fade show" role="alert">
          <i class="bi bi-<?php echo $flash['type'] === 'success' ? 'check-circle' : 'exclamation-triangle'; ?>"></i>
          <?php echo e($flash['message']); ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>
        
        <!-- Errores de validación -->
        <?php if (!empty($errors)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <strong><i class="bi bi-exclamation-triangle"></i> Error:</strong>
          <ul class="mb-0 mt-2">
            <?php foreach($errors as $error): ?>
              <li><?php echo e($error); ?></li>
            <?php endforeach; ?>
          </ul>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>
        
        <!-- Formulario -->
        <form method="post" action="<?php echo route('login_action'); ?>" id="loginForm">
          
          <?php echo csrf_field(); ?>
          
          <!-- Email -->
          <div class="mb-3">
            <label class="form-label fw-semibold">
              <i class="bi bi-envelope"></i> Correo Electrónico
            </label>
            <input 
              type="email" 
              name="email" 
              class="form-control form-control-lg <?php echo !empty($errors) ? 'is-invalid' : ''; ?>" 
              placeholder="admin@gmail.com"
              value="<?php echo old('email'); ?>"
              required 
              autofocus>
          </div>
          
          <!-- Contraseña -->
          <div class="mb-3">
            <label class="form-label fw-semibold">
              <i class="bi bi-lock"></i> Contraseña
            </label>
            <div class="input-group">
              <input 
                type="password" 
                name="password" 
                id="password"
                class="form-control form-control-lg <?php echo !empty($errors) ? 'is-invalid' : ''; ?>" 
                placeholder="••••••••"
                required>
              <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                <i class="bi bi-eye" id="eyeIcon"></i>
              </button>
            </div>
          </div>
          
          <!-- Recordar sesión -->
          <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="remember" name="remember">
            <label class="form-check-label" for="remember">
              Recordar sesión
            </label>
          </div>
          
          <!-- Botón de login -->
          <button type="submit" class="btn btn-primary btn-lg w-100 mb-3">
            <i class="bi bi-box-arrow-in-right"></i> Iniciar Sesión
          </button>
          
        </form>
        
        <!-- Info adicional -->
        <div class="text-center mt-4">
          <small class="text-muted">
            <i class="bi bi-shield-lock"></i> 
            Conexión segura y cifrada
          </small>
        </div>
        
        <!-- Credenciales de prueba (solo en desarrollo) -->
        <?php if (getenv('APP_ENV') !== 'production'): ?>
        <div class="alert alert-info mt-3 mb-0" role="alert">
          <small>
            <strong>Credenciales de prueba:</strong><br>
            Email: admin@gmail.com<br>
            Contraseña: admin123
          </small>
        </div>
        <?php endif; ?>
        
      </div>
      
      <!-- Footer -->
      <div class="text-center mt-4 text-white">
        <small>
          &copy; <?php echo date('Y'); ?> M.S.C COMPANY. Todos los derechos reservados.
        </small>
      </div>
      
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  
  <script>
  // Toggle mostrar/ocultar contraseña
  document.getElementById('togglePassword').addEventListener('click', function() {
    const password = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');
    
    if (password.type === 'password') {
      password.type = 'text';
      eyeIcon.classList.remove('bi-eye');
      eyeIcon.classList.add('bi-eye-slash');
    } else {
      password.type = 'password';
      eyeIcon.classList.remove('bi-eye-slash');
      eyeIcon.classList.add('bi-eye');
    }
  });
  
  // Validación del formulario
  document.getElementById('loginForm').addEventListener('submit', function(e) {
    const email = document.querySelector('[name="email"]').value;
    const password = document.querySelector('[name="password"]').value;
    
    if (!email || !password) {
      e.preventDefault();
      alert('Por favor completa todos los campos');
      return false;
    }
    
    // Validar formato de email
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
      e.preventDefault();
      alert('Por favor ingresa un correo válido');
      return false;
    }
  });
  </script>

</body>
</html>
