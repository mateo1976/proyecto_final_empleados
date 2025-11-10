<?php
// app/views/partials/header.php
?>
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="<?php echo route('dashboard'); ?>">
      <i class="bi bi-building"></i> M.S.C COMPANY
    </a>
    
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
            <i class="bi bi-person-circle"></i> 
            <span class="d-none d-sm-inline"><?php echo e(auth()); ?></span>
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="<?php echo route('dashboard'); ?>">
              <i class="bi bi-speedometer2"></i> Dashboard
            </a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item text-danger" href="<?php echo route('logout'); ?>">
              <i class="bi bi-box-arrow-right"></i> Cerrar sesión
            </a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>

<?php
// Mostrar mensaje flash si existe
$flash = getFlash();
if ($flash):
    $alertType = $flash['type'] === 'success' ? 'success' : 'danger';
?>
<div class="container-fluid mt-3">
  <div class="alert alert-<?php echo $alertType; ?> alert-dismissible fade show" role="alert">
    <i class="bi bi-<?php echo $flash['type'] === 'success' ? 'check-circle' : 'exclamation-triangle'; ?>"></i>
    <?php echo e($flash['message']); ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
</div>
<?php endif; ?>
