rules_validation = {
    txtnombre:{
        required: true,
        maxlength: 50
    },
    cmbrol:{
        required: true,
        digits: true
    },
    txtusuario:{
        required: true,
        maxlength: 100
    },
    pswcontrasena:{
        required: {
            depends: function(element){
                return !$.isNumeric($("input[name='Id_Usuario']").val())
            }
        },
        minlength: 6,
        maxlength: 18
    },
    pswconf_contrasena:{
        required: {
            depends: function(element){
               return !$.isNumeric($("input[name='Id_Usuario']").val())
            }
        },
        equalTo: "#contrasena"
    },
    optstatus:{
        required: true,
        digits: true
    },
}

// INICIALIZA DATATABLE
$('#data-table').DataTable({
    processing: true,
    serverSide: true,
    order: [[1 , "desc" ]],
    language: {
      url: base_url+"assets/libs/data-tables/json/spanish.json",
    },
    responsive: {
        details: {
            display: $.fn.dataTable.Responsive.display.childRowImmediate,
            type: ''
        }
    },
    ajax: {
        url: base_url+"usuarios/listarUsuarios",
        type: "post",
    },
    columns: [
        {data:"Ruta_Imagen",render:function(data){
            if(data){
                return "<img src='"+base_url+"usuariosprofile/"+data+"'/ class='img-thumbnail'>";
            }else{
                return "<img src='"+base_url+"usuariosprofile/default.jpg'/ class='img-thumbnail'>";
            }
        }},
        {data:"Nombre"},
        {data:"Usuario"},
        {data:"Rol"},
        {data:"Status",render:function(data){
            if(data == 1){
                return "Activo";
            }else{
                return "Inactivo";
            }
        }},
        {data:"Id_Usuario",render:function(data){
            return "<div class='btn-group'>\
                <button type='button' class='btn btn-sm btn-primary btn-edit-reg' title='Editar'><i class='fa fa-pencil'></i></button>\
                <input type='hidden' value='"+data+"' class='Id_Usuario'/>\
                <button type='button' class='btn btn-sm btn-danger btn-delete-reg' title='Eliminar'><i class='fa fa-trash'></i></button>\
            </div>"
        },
        orderable:false},
    ]
});

// ABRE FORMULARIO NUEVO
$(document).on("click",".abre-formulario",function(){
    $("#show-listado").hide();
    $("#show-formulario").show();
    traerFormulario();
});

// LLAMA TRAER FORMULARIO (EDICIÓN)
$(document).on("click",".btn-edit-reg",function(){
    Id_Usuario = $(this).next(".Id_Usuario").val();
    $("#show-listado").hide();
    $("#show-formulario").show();
    traerFormulario(Id_Usuario);
});

// CIERRA FORMULARIO NUEVO
$(document).on("click","#show-formulario .cierra-formulario",function(){
    $("#show-formulario").hide();
    $("#show-listado").show();
});

// REFRESCA TABLA
$(document).on("click",".refresh-table", function(){
    var table_refresh = $(this).attr("data-refresh");
    $("#"+table_refresh).DataTable().ajax.reload();
});

// ENVIAR FORMULARIO
$(document).on("submit","#form-crud",function(e){
    e.preventDefault();
    form = $(this);
    if(form.valid()){
         $.ajax({
            url: form.attr("action"),
            type: "post",
            data: new FormData(this),
            contentType: false,
            processData: false,
            dataType: "json",
            beforeSend:function(){
                $("#spinner").show();
            },
            success:function(data){
                $("#spinner").hide();
                if(data.head === "_er:"){
                    Lobibox.notify("error",{
                        position:"top center",
                        size:"mini",
                        msg:data.body
                    });
                }else if(data.head == "_ok:"){
                    $("#show-formulario").hide();
                    $("#show-listado").show();
                    Lobibox.notify("success",{
                        position:"top center",
                        size:"mini",
                        msg:data.body
                    });
                }
            }
        })
    }
});

// ELIMINAR USUARIO
$(document).on("click",".btn-delete-reg",function(){
    Id_Usuario = $(this).prev(".Id_Usuario").val();
    Lobibox.confirm({
        title: "Espera...",
        msg: "¿Estás seguro de eliminar este registro?",
        callback: function(lobibox, type){
            if(type =="yes"){                
                $.ajax({
                    url: base_url+"usuarios/eliminarUsuario",
                    data: "Id_Usuario="+Id_Usuario,
                    type: "post",
                    dataType: "json",
                    beforeSend:function(){
                        $("#spinner").show();
                    },
                    success:function(data){
                        lobibox.destroy();
                        $("#spinner").hide();
                        if(data.head === "_er:"){
                            Lobibox.notify("error",{
                                position:"top center",
                                size:"mini",
                                msg:data.body
                            });
                        }else if(data.head == "_ok:"){
                            Lobibox.notify("success",{
                                position:"top center",
                                size:"mini",
                                msg:data.body
                            });
                        }
                    }
                });
            }else{
                lobibox.destroy();
            }
        }
    }); 
});

// FUNCIÓN PARA MOSTRAR FORMULARIO NUEVO / EDICION
function traerFormulario(Id_Usuario){
    $.ajax({
        url: base_url+"usuarios/muestraFormulario",
        data: "Id_Usuario="+Id_Usuario,
        type: "post",
        beforeSend:function(){
            $("#spinner").show();
        },
        success:function(html){
            $("#spinner").hide();
            $("#show-formulario .lienzo").html(html);

            //ASIGNA VALIDACIONES AL FORM
            $("#form-crud").validate({
                rules: rules_validation
            });

            // INICIALIZA INPUT FILE
            $("#form-crud input.inputfile").fileinput({
                maxFileSize: 2400,
                showCaption: false,
                browseLabel: '',
                removeLabel: '',
                elErrorContainer: '#kv-avatar-errors-1',
                msgErrorClass: 'alert alert-block alert-danger',
                layoutTemplates: {main2: '{preview} {remove} {browse}'},
                allowedFileExtensions: ["jpg", "png"]
            });
        }
    });
}