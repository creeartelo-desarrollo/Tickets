 <div class="page-content d-flex align-items-stretch">
  <!-- Side Navbar -->
  <nav class="side-navbar">
    <!-- Sidebar Header-->
    <div class="sidebar-header d-flex align-items-center">
      <div class="avatar"><img src="<?= base_url('assets/libs/admin/images/icono.png') ?>" alt="..." class="img-fluid rounded-circle"></div>
      <div class="title">
        <h1 class="h4"><?= $session["Nombre"] ?></h1>
        <p><?= $session["Rol"] ?></p>
      </div>
    </div>
    <!-- Sidebar Navidation Menus--><span class="heading">Menú</span>
    <ul class="list-unstyled">
      <li><a href="<?= base_url('dashboards') ?>"><i class="fa fa-home"></i>Inicio</a></li>
      <li><a href="#menutickets" aria-expanded="false" data-toggle="collapse"> <i class="fa fa-ticket"></i>Tickets </a>
        <ul id="menutickets" class="collapse list-unstyled">
          <li><a href="<?= base_url('tickets') ?>">Abiertos</a></li>
          <li><a href="<?= base_url('tickets/cerrados') ?>"">Cerrados</a></li>
        </ul>
      </li>
      <li> <a href="<?= base_url('clientes') ?>"><i class="fa fa-address-book"></i>Clientes</a></li>
      <li> <a href="<?= base_url('usuarios') ?>"><i class="fa fa-users"></i>Usuarios</a></li>
    </ul>
  </nav>