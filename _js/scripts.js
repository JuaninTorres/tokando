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

function getContacto(){
  $('#contact-form').jqTransform();
  $("button").click(function(){
    $(".formError").hide();
  });

  var use_ajax=true;
  $.validationEngine.settings={};

  $("#contact-form").validationEngine({
    inlineValidation: true,
    promptPosition: "centerRight",
    success :  function(){use_ajax=true},
    failure : function(){use_ajax=false;}
   });

  $('#btn_enviar_contacto').button({
          icons: {
              primary: "ui-icon-mail-closed"
          }
        })
        .click(function() {
          if(!$('#subject').val().length)
            {
              $.validationEngine.buildPrompt(".jqTransformSelectWrapper","* Este campo es requerido","error")
              return false;
            }
            if(use_ajax)
            {
              $('#loading').css('visibility','visible');
              var f = $('#contact-form'),
              accion = $(f).attr('action');
              $.post(accion,$(f).serialize()+'&ajax=1',
                function(data){
                  if(parseInt(data)==-1){
                    $.validationEngine.buildPrompt("#captcha","* Número de verificacion equivocado!","error");
                  }
                  else
                  {
                    $(f).hide('slow').after('<h1>Muchas gracias!</h1>');
                  }
                  $('#loading').css('visibility','hidden');
                }
              );
            }
            $(this).preventDefault();
        });

    $('#btn_reset_contacto').button({
          icons: {
              primary: "ui-icon-trash"
          }
        })
        .click(function() {
            $('#contact-form')[0].reset();
            $(this).preventDefault();
        });
}

// Carga del locutor online
$(document).on("ready",contDinamico);
function contDinamico () {
    var urlLocutorOnline = "/_output/locutor_online.php";
    var locOnline = $.get( urlLocutorOnline, function(data){
        $("#espacio_locutor").html(data);
    });
    setTimeout("contDinamico()",60000);
}