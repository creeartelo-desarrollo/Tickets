rules_validation = {
    txtcodigo:{
        required: true,
        maxlength:20
    },
    txtrazon_social:{
        required: true,
        maxlength:200
    },
    rdostatus:{
        required: true,
        digits: true
    }
}

rules_validationsuc = {
    txtsucursal:{
        required: true,
        maxlength:50
    },
    txtcalle:{
        required: true,
        maxlength:100
    },
    txtnum_ext:{
        required: true,
        maxlength:30
    },
    txtnum_int:{
        maxlength:30
    },
    txtcolonia:{
        required: true,
        maxlength:100
    },
    txtmunicipio:{
        required: true,
        maxlength:150
    },
    txtestado:{
        required: true,
        maxlength:150
    },
    txtcp:{
        required: true,
        maxlength:5,
        minlength:5,
        digits: true
    },
    txtlatitud:{
        required: true,
        number:50
    },
    txtlongitud:{
        required: true,
        number: true
    },
    rdostatus:{
        required: true,
        digits: true
    }
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
        url: base_url+"clientes/listarClientes",
        type: "post",
    },
    columns: [
        {data:"Logo",render:function(data){
            if(data){
                return "<img src='"+base_url+"clienteslogos/"+data+"'/ class='img-thumbnail'>";
            }else{
                return "<img src='"+base_url+"clienteslogos/default.jpg'/ class='img-thumbnail'>";
            }
        },
        orderable:false},
        {data:"Codigo"},
        {data:"Razon_Social"},
        {data:"Num_Sucursales"},
        {data:"Status",render:function(data){
            if(data == 1){
                return "Activo";
            }else{
                return "Inactivo";
            }
        }},
        {data:"Id_Cliente",render:function(data){
            return "<div class='btn-group'>\
                <button type='button' class='btn btn-sm btn-primary btn-edit-reg' title='Editar'><i class='fa fa-pencil'></i></button>\
                <input type='hidden' value='"+data+"' class='Id_Cliente'/>\
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
    Id_Cliente = $(this).next(".Id_Cliente").val();
    $("#show-listado").hide();
    $("#show-formulario").show();
    traerFormulario(Id_Cliente);
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

//  EVITA QUE SE ENVÍE EL FORMULARIO 
//  AL DAR ENTER EN EL INPUT DEL MAPA
$(document).on("keyup keypress","#pac-input",function(e){
    keyCode = e.keyCode || e.which;
    if(keyCode == 13){
         e.preventDefault();
    }
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

// ELIMINAR CLIENTE
$(document).on("click",".btn-delete-reg",function(){
    Id_Cliente = $(this).prev(".Id_Cliente").val();
    Lobibox.confirm({
        title: "Espera...",
        msg: "¿Estás seguro de eliminar este registro?",
        callback: function(lobibox, type){
            if(type =="yes"){                
                $.ajax({
                    url: base_url+"clientes/eliminarCliente",
                    data: "Id_Cliente="+Id_Cliente,
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
// ABRE FORMULARIO NUEVO SUCURSAL
$(document).on("click",".abre-formulario-suc",function(){
    Id_Cliente = $(this).find(".Id_Cliente").val();
    $("#show-formulario").hide();
    $("#show-formulario-suc").show();
    traerFormularioSucursal(Id_Cliente,null);
});

// LLAMA TRAER FORMULARIO (EDICIÓN)
$(document).on("click",".btn-edit-reg-suc",function(){
    Id_Cliente = $(this).prev(".Id_Cliente").val();
    Id_Direccion = $(this).next(".Id_Direccion").val();
    $("#show-formulario").hide();
    $("#show-formulario-suc").show();
    traerFormularioSucursal(Id_Cliente,Id_Direccion);
});

// REFRESCA TABLA SUCURSALES
$(document).on("click",".refresh-tablesuc", function(){
    Id_Cliente = $(this).find(".Id_Cliente").val();
    traeSucursales(Id_Cliente);
});

// REGRESA A FORMULARIO NUEVO CLIENTE
$(document).on("click","#show-formulario-suc .cierra-formulario",function(){
    $("#show-formulario").show();
    $("#show-formulario-suc").hide();
});

// ENVIAR FORMULARIO SUCURSAL
$(document).on("submit","#form-crud-suc",function(e){
    e.preventDefault();
    form = $(this);
    if(form.valid()){
         $.ajax({
            url: form.attr("action"),
            type: "post",
            data: $(form).serialize(),
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
                    $("#show-formulario-suc").hide();
                    $("#show-formulario").show();
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

// ELIMINAR DIRECCION
$(document).on("click",".delet-suc",function(){
    Id_Direccion = $(this).prev(".Id_Direccion").val();
    Lobibox.confirm({
        title: "Espera...",
        msg: "¿Estás seguro de eliminar este registro?",
        callback: function(lobibox, type){
            if(type =="yes"){                
                $.ajax({
                    url: base_url+"clientes/eliminarDireccion",
                    data: "Id_Direccion="+Id_Direccion,
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
function traerFormulario(Id_Cliente){
    $.ajax({
        url: base_url+"clientes/muestraFormulario",
        data: "Id_Cliente="+Id_Cliente,
        type: "post",
        beforeSend:function(){
            $("#spinner").show();
        },
        success:function(html){
            $("#spinner").hide();
            $("#show-formulario .lienzo").html(html);
            $("[data-toggle='tooltip']").tooltip();    

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

            // Llama traer las sucursales del cliente
            traeSucursales(Id_Cliente)
        }
    });
}

// FUNCIÓN PARA MOSTRAR FORMULARIO NUEVO / EDICION SUCURSAL
function traerFormularioSucursal(Id_Cliente,Id_Direccion){
     $.ajax({
        url: base_url+"clientes/muestraFormularioSucursal",
        data: {
            Id_Cliente: Id_Cliente,
            Id_Direccion: Id_Direccion,
        },
        type: "post",
        beforeSend:function(){
            $("#spinner").show();
        },
        success:function(html){
            $("#spinner").hide();
            $("#show-formulario-suc .lienzo").html(html)

            //ASIGNA VALIDACIONES AL FORM
            $("#form-crud-suc").validate({
                rules: rules_validationsuc
            });
            initMap();
        }
    });
}

//  FUNCION PARA LLLENAR LA TABLA DE SUCURSALES DEL CLIENTE
function traeSucursales(Id_Cliente){
    $.ajax({
        url: base_url+"clientes/muestraSucursales",
        data: "Id_Cliente="+Id_Cliente,
        type: "post",
        beforeSend:function(){
            $("#spinner").show();
        },
        success:function(html){
            $("#spinner").hide();
            $("#tblsucursales tbody").html(html)
        }
    });
}

//  INICIALIZA MAPA
function initMap(){
    // SE CREA VARIABLE DE COORDENADAS CON POSICION POR DEFUALT EN GDL
    coordenadasi = {
        lat: parseFloat($("#latitud").val()),
        lng: parseFloat($("#longitud").val()),
    }

   var map = new google.maps.Map(document.getElementById('mapa'), {
              zoom: 9,
              center: coordenadasi,
            });


    var input = document.getElementById("pac-input");
    map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
    var autocomplete = new google.maps.places.Autocomplete(input);
    autocomplete.bindTo('bounds', map);

    var marker = new google.maps.Marker({
          map: map,
          position: coordenadasi,
          draggable: true,
          animation: google.maps.Animation.DROP,
          anchorPoint: new google.maps.Point(0, -29)
        });

    google.maps.event.addListener(marker, 'dragend', function() 
    {
        coordenadas = marker.getPosition();
        $("#latitud").val(coordenadas.lat);
        $("#longitud").val(coordenadas.lng);
    });

    autocomplete.addListener('place_changed', function() 
    {
        
        var place = autocomplete.getPlace();
        if (!place.geometry) {
           return;
        }
        
        if (place.geometry.viewport) {
          map.fitBounds(place.geometry.viewport);
        } else {
          map.setCenter(place.geometry.location);
          map.setZoom(9);          
        }

        marker.setPosition(place.geometry.location);
        $("#latitud").val(place.geometry.location.lat);
        $("#longitud").val(place.geometry.location.lng);
    });

    $(document).on("keyup","#latitud,#longitud",function(){
        lat = $("#latitud").val() ? $("#latitud").val() : 0;
        lng = $("#longitud").val() ?  $("#longitud").val() : 0;
       
        coordenadasn = {
            lat: parseFloat(lat),
            lng: parseFloat(lng),
        }

        map.setCenter(coordenadasn);
        marker.setPosition(coordenadasn);
        
    });
}