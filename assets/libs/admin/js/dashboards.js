/**
 * Variables del mapa
 */
var map;
var markers = [];
var infowindows = [];

/**
 * - INICIALIZA MAPA CON COORDENADAS EN EL CENTRO DE GDL
 * - TRAE LOS PINES DE LA PRIMERA OPCIÓN
 */
$(function(){
  map = new google.maps.Map(document.getElementById('mapa'), {
   zoom: 9,
   center: {lat: 20.6528405, lng: -103.2562794}
  });

  traerPines(1);
});

/**
 * MANDA A LLAMAR TRAERPINES DE ACUERDO 
 * A LA OPCIÓN SELECCIONADA DEL COMBO
 */
$(document).on("change","#cmbpines",function(){
  opcion = $(this).val();
  traerPines(parseInt(opcion));  
  if(opcion == 6){
    tiempo = 1000 * 1 * 60;
    intervalo = window.setInterval(function(){
      traerPines(6)},tiempo);
  }else{
    window.clearInterval(intervalo);
  }
});


/**
 * TRAE PINES DE ACUERDO AL PARAMETRO ENVIADO
 * 1: TICKETS ABIERTOS
 * 2: TICKETS SIN ATENDER
 * 3: TICKETS EN PROCESO
 * 4: SUCURSALES ACTIVAS 
 * 5: SUCURSALES INACTIVAS
 * 6: UBICACIÓN USUARIOS 
 * 
 * @param  INT opcion OPCION DEL COMBO
 */
function traerPines(opcion) {
  limpiarMarkers();
  switch(opcion){
    case 1:
      uri = "/traerPinesAbiertos";
      break;
    case 2:
      uri = "/traerPinesSinAtender";
      break;
    case 3:
      uri = "/traerPinesEnProceso";
      break;
    case 4:
      uri = "/traerPinesSucursalesActivas";
      break;
    case 5:
      uri = "/traerPinesSucursalesInactivas";
      break;
    case 6:
      uri = "/traerPinesUsuarios";
      break;
  } 


  $.ajax({
    url: base_url + "dashboards"+uri,
    dataType:"json",
    success:function(res){      
      // VARIABLES PARA EL INFO WINDOW
      var infowindow = new google.maps.InfoWindow();
      var contentString = "";
      // RECORRER ARRAY DE REGISTROS RECIBIDOS
      for (var i = 0; i < res.length; i++) {
        var data = res[i];
        // CREAR MARCARCADOR
        var marker = new google.maps.Marker({
          position: {lat: parseFloat(res[i].Latitud), lng:parseFloat(res[i].Longitud)},
          map: map
        });
        
        (function (marker, data) {
          // CREA INFO WINDOW CON EL CONTENIDO HTML
          // DE ACUERDO AL PARAMETRO
          google.maps.event.addListener(marker, "click", function (e) {
            // CONTENIDO PARA LOS TICKETS
            if(opcion > 0 && opcion < 4){
              infowindow.setContent("<div style='text-align:center'>\
                <a href='"+base_url+"' style='font-weight:900'>"+data.Num_Seguimiento+"</a> </br>" + 
                "<span style='font-weight:900'>" + data.Usuario + "</span> <br />" +
                data.Razon_Social + " " + data.Sucursal + "</br>" +
                cualEstado(data.Status) + "<br>" +  data.Fecha_Alta + 
              "</div>");
            // CONTENIDO PARA LAS SUCURSALES
            }else if(opcion > 3 && opcion < 6){
              infowindow.setContent("<div style='text-align:center'>\
                <a href='"+base_url+"' style='font-weight:900'>"+data.Codigo+"</a> </br>" + 
                data.Razon_Social + " <br> " + data.Sucursal + "</br>" +
              "</div>");
            // CONTENIDO PARA LAS CUADRILLAS
            }else if(opcion == 6){
              
              infowindow.setContent("<div style='text-align:center'>\
                <a href='"+base_url+"' style='font-weight:900'>"+data.Nombre+"</a> </br> \
                Actualizado: <br> " + data.Fecha_Actualiza + "</br>" +
              "</div>");
            }
            
            infowindow.open(map, marker);
          });
        })(marker, data);
        // AGREGA MARCADOR AL ARRAY DE MARCADORES
        markers.push(marker);   
      }
    }
  }); 
}

/**
 * LIMPIA ARRAY DE MARCADORES Y
 * LIMPIA EL MAPA
 */
function limpiarMarkers(){
  for (var i = 0; i < markers.length; i++) {
    markers[i].setMap(null);
  }
  infowindows = [];
  markers = [];
}

/**
 * REGRESA EL NOMBRE DEL ESTADO DEL TICKET 
 * DE ACUERDO AL ENTERO DEL PARAMETRO
 * 
 * @param  {[INT]} intStatus [STATUS]
 * @return {[STRING]} [NOMBRE DEL STATUS]
 */
function cualEstado(intStatus){
  var status = "";
  switch(parseInt(intStatus)){
    case 1:
        status = "LEVANTADO";
        break;
    case 2:
        status = "ASIGNADO";
        break;
    case 3:
        status = "EN EL LUGAR";
        break;
    case 4:
        status = "DIAGNOSTICANDO";
        break;
    case 5:
        status = "TRABAJANDO";
        break;
    case 6:
        status = "PAUSADO";
        break;
    case 7:
        status = "EVIDENCIANDO";
        break;
    case 8:
        status = "TERMINADO";
        break;
    case 9:
        status = "CERRADO";
        break;
    default:
        status = "INDEFINIDO";
        break;
  }
  return status;
}
/**
 * REGRESA EL NOMBRE DEL ESTADO DE LAS SUCURSALES 
 * DE ACUERDO AL ENTERO DEL PARAMETRO
 * 
 * @param  {[INT]} intStatus [STATUS]
 * @return {[STRING]} [NOMBRE DEL STATUS]
 */

function cualEstado2(intStatus){
  var status = "";
  switch(parseInt(intStatus)){
    case 1:
        status = "ACTIVO";
        break;
    case 2:
        status = "INACTIVO";
        break;
  }
}
