var usuarioLogueado;
$(document).on("ready",inicio);
function inicio () {
  var username = $( "#login_user" ),
    password = $( "#login_pass" ),
    allFields = $( [] ).add( username ).add( password );

  var estado = $.get('status.php', function(data){
    if (data.logueado===1){
      var datos = { errores: 0 } ;
      showPrincipal(datos);
    }
    else{
      $( "#divlogin" ).dialog({
        autoOpen: true,
        height: 350,
        width: 350,
        modal: true,
        buttons: {
          "Ingresar": function() {
              //alert('Aqui crearé la funcion del login');
              var formValues = $('#formlogin').serialize(),
              url = $('#formlogin').attr( 'action' );
              var logueando = $.post( url, formValues, showPrincipal, "json" );
              logueando.done(function(data){
                $( "#divlogin" ).dialog( "close" );
              });
          },
          Cancel: function() {
            $( this ).dialog( "close" );
          }
        },
        close: function() {
          allFields.val( "" ).removeClass( "ui-state-error" );
        }
      });

      $( "#btn_ingresar" )
        .button({
          icons: {
              primary: "ui-icon-unlocked"
          }
        })
        .click(function() {
          $( "#divlogin" ).dialog( "open" );
        });
      $('#div_btn_ingresar').show();
    }

  }, "json");

  // Actualizo la hora
  setInterval(setFechaHoraServer,1000);
}

function showPrincipal (data)
{
  if (data.errores===0)
  {
    $('#div_btn_ingresar').hide();
    $('#divlogin').hide();
    var logueado = $.get('principal.php', function(dataLogueado){
      $('#principal').html(dataLogueado.contenido);
      eval(dataLogueado.jscall);

      $('#div_user_logueado').show('fast');
      $('#saludo_user_logueado').html('Hola, estas autenticado como ');
      $('#user_logueado').html(dataLogueado.user.first_name + ' ' + dataLogueado.user.last_name);
      // Ahora dejo toda la info del usuario en una variable de JavaScript
      usuarioLogueado = dataLogueado.user;

      // Si ya hizo login, entonces puedo hacer logout
      $( "#btn_logout" )
      .button({
        icons: {
            primary: "ui-icon-power"
        }
      })
      .click(function() {
            var url = 'logout.php',
                datanull = null;
            var saliendo = $.get( url, function(data){
              alert(data.msg);
              $('#div_user_logueado').hide();
              $('#user_logueado').empty();
              $('#principal').empty();
            }, "json" );
            inicio ();
      });
    }, "json");
  }
  else
  {
    alert(data.msg);
  }
}
function getListadoUsuarios()
{
  $.get('listado_usuarios.php',function(data){
    $('#listado_usuarios').html(data.contenido);
    eval(data.jscall);
  },'json');
}
function showListadoUsuarios()
{
  $('#usuarios tr td button').button({icons: { primary: 'ui-icon-pencil'}}
    ).click(function(){
      var modificarLocutor = $.post('modificacion_usuario.php',{ id_user: $(this).attr('rel') },function(data){
        $('#div_modificar').html(data.contenido);
        eval(data.jscall);
      },'json');
    });
    $('#btn_adduser').button({icons: { primary: 'ui-icon-circle-plus'}}
      ).click(function(){
        var user_name=$.trim(prompt('Indique el user_name del nuevo usuario'));
        if(user_name!=='')
        {
          $.post('add_usuario.php',{ user_name: user_name }, function(data){
            if(data.errores===0)
            {
              // Aqui deberia volver a dibujar
              getListadoUsuarios();
              hideError();
            }
            else
            {
              alert(data.contenido);
            }
          },'json');
        }
        else
        {
          showError('El nombre de usuario no puede estar vacio');
        }
      });
}
function setFechaHoraServer()
{
  var saliendo = $.get( 'now.php', function(data){
    $('#hora_server').html('['+data.ahora+']');
  }, "json" );
}
function showError(mensaje)
{
  $('#divMensajeError div p span.contenido').text(mensaje);
  $('#divMensajeError').show('fast');
}
function hideError()
{
  $('#divMensajeError div p span.contenido').empty();
  $('#divMensajeError').hide('fast');
}

function btnCambioPass(){
  $( '#divchangepass' ).dialog({
    height: 350,
    width: 350,
    modal: true,
    buttons: {
      'Cambiar': function() {
        // Revisamos que todo este OK
        var pass1 = $('#change_pass_1').val(),
            pass2 = $('#change_pass_2').val();
        if(pass1.length === 0)
        {
          alert('El Password no puede estar vacio');
        }
        else
        {
            if(pass1!=pass2)
            {
              alert('Los Password no son iguales');
            }
            else
            {
              var formValues = $('#formchangepass').serialize(),
              url = $('#formchangepass').attr( 'action' );
              var logueando = $.post( url, formValues, function(data){
                alert(data.msg);
                if(data.errores===0)
                {
                  $( '#divchangepass' ).dialog( 'close' );
                }
              }, 'json' );
            }
        }
      },
      Cancelar: function() {
        $( this ).dialog( 'close' );
      }
    },
    close: function() {
      $('#formchangepass')[0].reset();
    }
  });
}

function guardando(valor,id_valor, checked)
{
  var url = 'save.php',
      dataPost = { valor: valor, id_valor: id_valor, checked: checked };
  var posting = $.post(url, dataPost,
      function (data){
          eval(data.jscall);
  }, "json");
}
function savehighlight(e)
{
  $("#"+e).animate({ backgroundColor: "#FDFF00" }, 10);
  $("#"+e).animate({ backgroundColor: "#FFFFFF" }, 1000);

  // Si pase por aqui es que lo ultimo que hice fue correcto
  hideError();
}
function mulaChecksum(s)
{
  if ($.trim(s)===''){
    return '';
  }
  var i;
  var chk = 0x12345678;

  for (i = 0; i < s.length; i++) {
    chk += (s.charCodeAt(i) * (i+1));
  }

  return chk;
}
function setCacheInput(e)
{
  //calculo crc y lo guardo.
  $(e).attr('crcchk',mulaChecksum($(e).val()));
}
function editInputCRC(e)
{
  setCacheInput(e);
}
function editInputCRCOff(e)
{
  var guardar=false;
  //calculo placeholder
  var ph='cualquiertonteranuncausada....,,,,';
  var evalue=$.trim($(e).val());
  $(e).val(evalue);
  var evaluecrc=mulaChecksum(evalue);
     if ($(e).attr('placeholder')!==undefined){
      if ($(e).attr('placeholder')!==''){
        ph=$(e).attr('placeholder');
      }
  }

  var currentcrc=$(e).attr('crcchk');
  //si no es la primera vez verifico que la ultima version guardada sea distinta a la version del caché
  //veo si lo he guardado antes, si nunca lo he guardado...
  //ignoro valores iguales a place holder
  if ($(e).val()==ph){
    evalue='';
    $(e).val('');
    if (currentcrc!==''){
      //a menos que antes hubiera un valor guardado!.. o sea limpio
      guardar=true;
    }
  }
  else if (currentcrc==='' && evalue!=='')
  {
    guardar=true;
  }else if (currentcrc!=evaluecrc){
    //si son distintos... guardo
    guardar=true;
  }
  if (guardar){
    // La respuesta en ajax a xajax_neditcell(evalue,e.id);
    guardando($(e).val(),$(e).attr('id'));
    setCacheInput(e);
  }
}
function confirmEliminaUsuario(user)
{
  $( "#modalModificarUsuario" ).attr('title','Eliminación de usuario')
    .html("<p><span class='ui-icon ui-icon-alert' style='float: left; margin: 0 7px 20px 0;'></span>¿Está seguro de eliminar a este usuario? Recuerde que al hacerlo, se eliminará toda la información asociada a él.</p>")
    .dialog({
      resizable: false,
      height:280,
      width: 400,
      modal: true,
      buttons: {
        "Eliminar": function() {
          var eliminando = $.post( 'delete_usuario.php', {user_name: user}, function(data){
            alert(data.contenido);
            if(data.errores===0)
            {
              getListadoUsuarios();
            }
          }, "json" );

          $( this ).dialog( "close" );
        },
        Cancelar: function() {
          $( this ).dialog( "close" );
        }
      }
    });
}