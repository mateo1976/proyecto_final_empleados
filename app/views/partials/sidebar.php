<?php
// app/views/partials/sidebar.php
?>
<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
  <div class="position-sticky pt-3">
    <ul class="nav flex-column">
      <li class="nav-item">
        <a class="nav-link active" href="index.php?route=dashboard">
          <i class="bi bi-house-door-fill"></i>
          Dashboard
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="index.php?route=empleados">
          <i class="bi bi-people-fill"></i>
          Empleados
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="index.php?route=cumpleanos">
          <i class="bi bi-cupcake"></i>
          Cumpleaños
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="index.php?route=finanzas">
          <i class="bi bi-cash-stack"></i>
          Finanzas
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="index.php?route=nomina">
          <i class="bi bi-receipt"></i>
          Nómina
        </a>
      </li>
    </ul>
  </div>
</nav>
