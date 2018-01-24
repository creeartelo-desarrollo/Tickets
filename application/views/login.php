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
    <!-- LobiBox -->
    <link rel="stylesheet" type="text/css" href="<?=base_url('assets/libs/lobibox/css/lobibox.min.css')?>">
    <!-- theme stylesheet-->
    <link rel="stylesheet" href="<?= base_url('assets/libs/admin/css/style.custom.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/libs/admin/css/custom.css') ?>">
    <!-- Favicon-->
    <link rel="shortcut icon" href="img/favicon.ico">
  </head>
  <body>
    <div class="page login-page">
      <div class="container d-flex align-items-center">
        <div class="form-holder has-shadow">
          <div class="row">
            <!-- Logo & Information Panel-->
            <div class="col-lg-6">
              <div class="info d-flex align-items-center">
                <div class="content">
                  <div class="logo">
                   <img src="<?= base_url('assets/libs/admin/images/logo.png') ?>" class="img-fluid">
                  </div>
                  <p>Administrador de Tickets</p>
                </div>
              </div>
            </div>
            <!-- Form Panel    -->
            <div class="col-lg-6 bg-white">
              <div class="form d-flex align-items-center">
                <div class="content">
                  <form id="frmlogin" method="post" action="<?= base_url('admin/login') ?>">
                    <div class="form-group">
                      <input id="login-username" type="text" name="txtusuario" required="required" class="input-material">
                      <label for="login-username" class="label-material">Usuario</label>
                    </div>
                    <div class="form-group">
                      <input id="login-password" type="password" name="pswcontrasena" required="required" class="input-material">
                      <label for="login-password" class="label-material">Contraseña</label>
                    </div><button class="btn btn-primary">Entrar</button>
                    <!-- This should be submit button but I replaced it with <a> for demo purposes-->
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- JQuery -->
    <script type="text/javascript" src="<?= base_url('assets/libs/jQuery/jquery-3.1.1.min.js')?>"></script>
    <!-- Bootstrap -->
    <script type="text/javascript" src="<?= base_url('assets/libs/bootstrap/js/tether.min.js')?>"></script>
    <script type="text/javascript" src="<?= base_url('assets/libs/bootstrap/js/bootstrap.min.js')?>"></script>
    <!-- Jquery Validation -->
    <script type="text/javascript" src="<?= base_url('assets/libs/jQueryValidation/js/jquery.validate.min.js')?>"></script>
    <script type="text/javascript" src="<?= base_url('assets/libs/jQueryValidation/js/messages_es.js')?>"></script>
    <!-- Lobibox -->
    <script type="text/javascript" src="<?= base_url('assets/libs/lobibox/js/lobibox.min.js') ?>"></script>
    <!-- Admin -->
    <script type="text/javascript">base_url = "<?= base_url() ?>"</script>
    <script type="text/javascript" src="<?= base_url('assets/libs/admin/js/admin.js')?>"></script>
    
     <script type="text/javascript">
        $("#frmlogin").validate({
            submitHandler: function(form) {
                $.ajax({
                    url: $(form).attr("action"),
                    data: $(form).serialize(),
                    type: "post",
                    dataType: "json",
                    success:function(res){
                        console.log(res)
                        if(res.head == "_ok:"){
                            window.location.replace(res.body);
                        }else if(res.head == "_er:"){
                            Lobibox.notify("error",{
                                position:"top center",
                                size:"mini",
                                msg:res.body
                            });
                        }                   
                    }
                })
            }
        });
    </script>
  </body>
</html>