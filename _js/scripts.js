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

function validar()
{
  $("#contact-form").validate({
    rules: {
      name: {
        required: true,
        minlength: 2
      },
      email: {
        required: true,
        email: true
      },
      subject: "required",
      message: {
        required: true,
        minlength: 10
      }
    },
    messages: {
      name: {
        required: "Por favor ingrese su nombre",
        minlength: "Su nombre no puede ser tan corto"
      },
      email: "Por favor ingrese un email válido",
      email: "Por favor seleccione un asunto",
      message: {
        required: "Por favor ingrese su mensaje",
        minlength: "Su mensaje no puede ser tan corto"
      },
    }
  });
}

function getContacto(){
  // $('#contact-form').jqTransform();
  // $("button").click(function(){
  //   $(".formError").hide();
  // });

  // var use_ajax=true;
  // $.validationEngine.settings={};

  // $("#contact-form").validationEngine({
  //   inlineValidation: true,
  //   promptPosition: "centerRight",
  //   success :  function(){use_ajax=true},
  //   failure : function(){use_ajax=false;}
  //  });

  //$("#contact-form").validate();

  $('#btn_enviar_contacto').button({
          icons: {
              primary: "ui-icon-mail-closed"
          }
        })
        .click(function() {
              $('#loading').css('visibility','visible');
              $('#resultado_contacto').show('hide');
              var f = $('#contact-form'),
              accion = $(f).attr('action');


              $.post(accion,$(f).serialize()+'&ajax=1',
                function(data){
                  if(validar())
                  {
                    if(parseInt(data)==-1){
                      // Error
                      $('#resultado_contacto').html(data).show('slow');
                    }
                    else
                    {
                      $('#resultado_contacto').show('hide');
                      $(f).hide('slow').after('<h1>Muchas gracias!</h1>');
                    }

                  }
                  $('#loading').css('visibility','hidden');
                }
              );
        }).preventDefault();

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
function contDinamico () {
    var urlLocutorOnline = "/_output/locutor_online.php";
    var locOnline = $.get( urlLocutorOnline, function(data){
        $("#espacio_locutor").html(data);
    });
    setTimeout("contDinamico()",60000);
}