function setHoraCliente()
{
  var mydate=new Date();
  var year=mydate.getYear();
  if (year < 1000) year+=1900;
  var day=mydate.getDay();
  var month=mydate.getMonth();
  var daym=mydate.getDate();
  if (daym<10) daym="0"+daym;
  var dayarray=new Array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sabado");
  var montharray=new Array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");

  var hora = mydate.getHours() + ':'. mydate.getMinutes() + ':' + mydate.getSeconds();
  var textoFecha = dayarray[day]+" "+daym+" de "+montharray[month]+" de "+year + ' - ' + hora;
}

// Carga del locutor online
$(document).on("ready",contDinamico);
function contDinamico () {
    var urlLocutorOnline = "/_output/locutor_online.php";
    var locOnline = $.get( urlLocutorOnline, function(data){
        $("#locutorimagen").html(data);
    });
    setTimeout("contDinamico()",60000);
}