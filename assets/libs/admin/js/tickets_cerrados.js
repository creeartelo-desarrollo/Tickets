rules_validation = {
    txtfecha:{
        required: true,
        dateISO: true,
    },
    txtnoseguimiento:{
        required: true,
        maxlength: 20
    },
    txtordenserv:{
        required: true,
        maxlength: 20
    },
    txtticket:{
        maxlength: 100
    },
    cmbcliente:{
        required: true,
        digits: true
    },
    cmbdireccion:{
        required: true,
        digits: true
    },
    txadescripcion:{
        required: true,
        maxlength: 200
    },
    txaobservaciones:{
        maxlength: 500
    },
    cmbasignado:{
        required: true,
        digits: true
    }
}

rules_validation_2 = {
    txadiagnostico:{
        required: true,
        maxlength: 800
    },
    txamaterial:{
        maxlength: 1000
    },
    txaobservaciones:{
        maxlength: 500
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
        url: base_url+"tickets/listarTicketsCerrados",
        type: "post",
    },
    columns: [
        {data:"Fecha"},
        {data:"Num_Seguimiento"},
        {data:"Ticket"},
        {data:"Razon_Social"},
        {data:"Usuario"},
        {data:"Alerta",render:function(data){
            return "<span class='semaforo' style='background-color:"+data+"'></span>";
        },orderable:false},
        {data:"Intervalo"},
        {data:"Orden_Servicio"},
        {data:"Id_Ticket",render:function(data){
            return "<div class='btn-group'>\
                <a href='"+base_url+"tickets/pdf/"+data+"' class='btn btn-sm btn-success' title='PDF' target='_blank'><i class='fa fa-file-pdf-o'></i></a>\
                <button type='button' class='btn btn-sm btn-primary btn-edit-reg' title='Editar'><i class='fa fa-pencil'></i></button>\
                <input type='hidden' value='"+data+"' class='Id_Ticket'/>\
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
    Id_Ticket = $(this).next(".Id_Ticket").val();
    $("#show-listado").hide();
    $("#show-formulario").show();
    traerFormulario(Id_Ticket);
});

// CIERRA FORMULARIO NUEVO
$(document).on("click",".cierra-formulario",function(){
    $("#show-formulario").hide();
    $("#show-listado").show();
});

// REFRESCA TABLA
$(document).on("click",".refresh-table", function(){
    var table_refresh = $(this).attr("data-refresh");
    $("#"+table_refresh).DataTable().ajax.reload();
});

//  LLENA COMBO DE SUCURSALES QUE PERETENECEN
//  AL CLIENTE SELECCIONADO
$(document).on("change","select[name='cmbcliente']",function(){
    Id_Cliente = $(this).val();
    $.ajax({
        url: base_url+"tickets/muestraDireccionesCliente",
        data: "Id_Cliente="+Id_Cliente,
        type: "post",
        dataType: "json",
        beforeSend:function(){
            $("#spinner").show();
        },
        success:function(data){
            $("#spinner").hide();
            $("select[name='cmbdireccion']").html("");
            
            // si hay datos llena el textarea de la direccion 
            // con el primer registro del arreglo
            if(data.length > 0){
                llenaCampoDireccion(data[0].Id_Direccion);     
            }
           
            for (var i = 0; i < data.length; i++) {
               $("select[name='cmbdireccion']").append(
                    "<option value='"+data[i].Id_Direccion+"'>"+
                        data[i].Sucursal
                    +"</option>"
                );
            }
        }
    });
});


//  MANDA A A LLAMAR llenaCampoDireccion
$(document).on("change","select[name='cmbdireccion']",function(){
    Id_Direccion = $(this).val();
    llenaCampoDireccion(Id_Direccion);
});

// ENVIAR FORMULARIO
$(document).on("submit","#form-crud, #form-crud-2",function(e){
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


// ELIMINAR REGISTRO
$(document).on("click",".btn-delete-reg",function(){
    Id_Ticket = $(this).prev(".Id_Ticket").val();
    Lobibox.confirm({
        title: "Espera...",
        msg: "¿Estás seguro de eliminar este registro?",
        callback: function(lobibox, type){
            if(type =="yes"){                
                $.ajax({
                    url: base_url+"tickets/eliminarTicket",
                    data: "Id_Ticket="+Id_Ticket,
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

/**
 *  EXPORTA LA TABLA A EXCEL
 */
$("#frm-exporta").validate({
    rules:{
        txtfechai:{
            required:true,
            dateISO: true,
        },
         txtfechaf:{
            required:true,
            dateISO: true,
        },
        "chkfiltros[]":{
            required:true,
        }
    },
    submitHandler: function(form) {
        $.ajax({
            data: $(form).serialize(),
            type: "post",
            dataType: "json",
            url: $(form).attr("action"),
            beforeSend:function(){
                $("#spinner").show();
            },
            success:function(data){
                if(data.head === "_er:"){
                    Lobibox.notify("error",{
                        position:"top center",
                        size:"mini",
                        msg:data.body
                    });
                }else if(data.head == "_ok:"){
                    location.replace(data.body);
                    $("#modal-descarga").modal("hide");
                    Lobibox.notify("success",{
                        position:"top center",
                        size:"mini",
                        msg:"Excelente! Se ha iniciado la descarga"
                    });
                }
            }
        })
    }
});

// FUNCIÓN PARA MOSTRAR FORMULARIO NUEVO / EDICION
function traerFormulario(Id_Ticket){
     $.ajax({
        url: base_url+"tickets/muestraFormulario",
        data: "Id_Ticket="+Id_Ticket,
        type: "post",
        beforeSend:function(){
            $("#spinner").show();
        },
        success:function(html){
            $("#spinner").hide();
            $("#show-formulario .lienzo").html(html)

            //ASIGNA VALIDACIONES AL FORM
            $("#form-crud").validate({
                rules: rules_validation
            });

            //ASIGNA VALIDACIONES AL 
            //FORM DE LA CUADRILLA
            $("#form-crud-2").validate({
                rules: rules_validation_2
            });
        }
    });
}


//  LLENA EL TEXTAREA DE DIRECCIÓN CON LA INFORMACIÓN 
//  DE LA SUCURSAL SELECCIONADA EN EL COMBO
function llenaCampoDireccion(Id_Direccion){
    $.ajax({
        url: base_url+"tickets/muestraDireccion",
        data: "Id_Direccion="+Id_Direccion,
        type: "post",
        dataType: "json",
        beforeSend:function(){
            $("#spinner").show();
        },
        success:function(data){
            direccion = data.Calle + " " + data.No_Ext + " " + data.No_Int + " " +
                        data.Colonia + ", " + data.Municipio + " " + data.Estado;
            $("#spinner").hide();
            $("#direccion").val(direccion);
        }
    });
}
