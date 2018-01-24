  <!-- Page Footer-->
            <footer class="main-footer">
              <div class="container-fluid">
                <div class="row">
                  <div class="col-sm-12">
                    <p>Mako &copy;</p>
                  </div>
                </div>
              </div>
            </footer>
          </div>
        </div>
    </div>
   

    <!-- Javascript files-->
    <!-- JQuery -->
    <script type="text/javascript" src="<?= base_url('assets/libs/jQuery/jquery-3.1.1.min.js')?>"></script>
    <!-- Bootstrap -->
    <script type="text/javascript" src="<?= base_url('assets/libs/bootstrap/js/tether.min.js')?>"></script>
    <script type="text/javascript" src="<?= base_url('assets/libs/bootstrap/js/bootstrap.min.js')?>"></script>
    <!-- Jquery Validation -->
    <script type="text/javascript" src="<?= base_url('assets/libs/jQueryValidation/js/jquery.validate.min.js')?>"></script>
    <script type="text/javascript" src="<?= base_url('assets/libs/jQueryValidation/js/messages_es.js')?>"></script>
    <!-- Data Table -->
    <script type="text/javascript" src="<?= base_url('assets/libs/data-tables/js/jquery.dataTables.min.js')?>"></script>
    <script type="text/javascript" src="<?= base_url('assets/libs/data-tables/js/dataTables.responsive.min.js')?>"></script>
    <!-- File Input -->
    <script type="text/javascript" src="<?= base_url('assets/libs/fileinput-master/js/fileinput.min.js') ?>"></script>
    <script type="text/javascript" src="<?= base_url('assets/libs/fileinput-master/js/theme.min.js') ?>"></script>
    <script type="text/javascript" src="<?= base_url('assets/libs/fileinput-master/js/es.js') ?>"></script>
    <!-- Lobibox -->
    <script type="text/javascript" src="<?= base_url('assets/libs/lobibox/js/lobibox.min.js') ?>"></script>
    <!-- Google Maps -->
    <script defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB4IONKCLg0TlJRAeaicXw5xl0gQrHH5Rc&libraries=places"></script>

    
    <!-- Admin -->
    <script type="text/javascript">base_url = "<?= base_url() ?>"</script>
    <script type="text/javascript" src="<?= base_url('assets/libs/admin/js/admin.js')?>"></script>
    <?php if(isset($modulojs)){ ?>
    <script type="text/javascript" src="<?= base_url('assets/libs/admin/js/'.$modulojs)?>"></script>
    <?php } ?>
    

    <?php 
      $msg = $this->session->flashdata("msg");
      $msge = $this->session->flashdata("msge");
    ?>
        
    <?php if($msg){ ?>
      <script type="text/javascript">
        Lobibox.notify("success",{
          position:"top center",
          size:"mini",
          msg:"<?=$msg?>"
        });
      </script>
    <?php } ?>

    <?php if($msge){ ?>
      <script type="text/javascript">
        Lobibox.notify("error",{
          position:"top center",
          size:"mini",
          msg:"<?=$msge?>"
        });
      </script>
    <?php } ?>

  </body>
</html>