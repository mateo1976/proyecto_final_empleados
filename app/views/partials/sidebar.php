<?php
// app/views/partials/sidebar.php
?>
<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
  <div class="position-sticky pt-3">
    <ul class="nav flex-column">
      
      <li class="nav-item">
        <a class="nav-link <?php echo isActiveRoute('dashboard'); ?>" href="<?php echo route('dashboard'); ?>">
          <i class="bi bi-house-door-fill"></i>
          Dashboard
        </a>
      </li>
      
      <li class="nav-item">
        <a class="nav-link <?php echo isActiveRoute('empleados'); ?>" href="<?php echo route('empleados'); ?>">
          <i class="bi bi-people-fill"></i>
          Empleados
        </a>
      </li>
      
      <li class="nav-item">
        <a class="nav-link <?php echo isActiveRoute('cumpleanos'); ?>" href="<?php echo route('cumpleanos'); ?>">
          <i class="bi bi-gift-fill"></i>
          Cumpleaños
        </a>
      </li>
      
      <li class="nav-item">
        <a class="nav-link <?php echo isActiveRoute('finanzas'); ?>" href="<?php echo route('finanzas'); ?>">
          <i class="bi bi-cash-stack"></i>
          Finanzas
        </a>
      </li>
      
      <li class="nav-item">
        <a class="nav-link <?php echo isActiveRoute('nomina'); ?>" href="<?php echo route('nomina'); ?>">
          <i class="bi bi-receipt"></i>
          Nómina
        </a>
      </li>
      
    </ul>
    
    <hr class="my-3">
    
    <div class="px-3">
      <small class="text-muted d-block mb-2">Versión 1.0</small>
      <small class="text-muted d-block">
        <i class="bi bi-info-circle"></i> 
        <?php echo count($GLOBALS['empleados'] ?? []); ?> empleados registrados
      </small>
    </div>
  </div>
</nav>
