<?php
// app/views/partials/header.php
?>
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">M.S.C COMPANY</a>
    <div class="d-flex">
      <span class="me-3">Admin: <?php echo $_SESSION['admin']; ?></span>
      <a class="btn btn-outline-secondary" href="index.php?route=logout">Cerrar sesión</a>
    </div>
  </div>
</nav>
