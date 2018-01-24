<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Mako Tickets</title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="all,follow">
  <!-- Bootstrap CSS-->
  <link rel="stylesheet" href="<?= base_url('assets/libs/bootstrap/css/bootstrap.min.css') ?>">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" type="text/css" href="<?=base_url('assets/libs/fontawesome/css/font-awesome.min.css')?>">
  <!-- Data Tables -->
  <link rel="stylesheet" type="text/css" href="<?=base_url('assets/libs/data-tables/css/jquery.dataTables.min.css')?>">
  <link rel="stylesheet" type="text/css" href="<?=base_url('assets/libs/data-tables/css/responsive.dataTables.min.css')?>">
  <!-- File Input -->
  <link rel="stylesheet" type="text/css" href="<?=base_url('assets/libs/fileinput-master/css/fileinput.min.css')?>">
  <link rel="stylesheet" type="text/css" href="<?=base_url('assets/libs/fileinput-master/css/theme.min.css')?>">
  <!-- LobiBox -->
  <link rel="stylesheet" type="text/css" href="<?=base_url('assets/libs/lobibox/css/lobibox.min.css')?>">
  <!-- theme stylesheet-->
  <link rel="stylesheet" href="<?= base_url('assets/libs/admin/css/style.custom.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/libs/admin/css/custom.css') ?>">
  <!-- Favicon-->
  <link rel="shortcut icon" href="img/favicon.ico">
</head>
<body>
  <div class="page home-page">
    <!-- Main Navbar-->
    <header class="header">
      <nav class="navbar">
        <!-- Search Box-->
        <div class="search-box">
          <button class="dismiss"><i class="icon-close"></i></button>
          <form id="searchForm" action="#" role="search">
            <input type="search" placeholder="What are you looking for..." class="form-control">
          </form>
        </div>
        <div class="container-fluid">
          <div class="navbar-holder d-flex align-items-center justify-content-between">
            <!-- Navbar Header-->
            <div class="navbar-header">
            <!-- Navbar Brand -->
            <a href="<?= base_url() ?>" class="navbar-brand">
                <img src="<?= base_url('assets/libs/admin/images/logo_bco.png')?>" style="width: 150px">
                <!-- Toggle Button-->
                <a id="toggle-btn" href="#" class="menu-btn active">
                  <span></span>
                  <span></span>
                  <span></span>
                </a>
            </div>
            <!-- Navbar Menu -->
            <ul class="nav-menu list-unstyled d-flex flex-md-row align-items-md-center">
              <!-- Logout    -->
              <li class="nav-item">
                <a href="<?= base_url('admin/logout/') ?>" class="nav-link logout">Cerrar sesión<i class="fa fa-sign-out"></i></a>
              </li>
            </ul>              
          </div>
        </div>
      </nav>
    </header>