//compara si los passwords son iguales
var verificarContrasenaIgual = function() {
    if (document.getElementById('confcontrasena').value ==
      document.getElementById('contrasena').value) {
      document.getElementById('message').style.color = 'green';
      document.getElementById('message').innerHTML = 'Contrase&ntilde;a confirmada correctamente';
      document.getElementById("btnEnviar").disabled = false;
    } else {
      document.getElementById('message').style.color = 'red';
      document.getElementById('message').innerHTML = 'Contrase&ntilde;a no es igual';
      // document.getElementById("confcontrasena").required = true;
      document.getElementById("btnEnviar").disabled = true;
    }
  }


  //se activa cuando se seleccina una opcion del combobox
  document.getElementById('rol').onchange = verificarSeleccionMenuDesplegable;

  //esconde o muestra codigo html en la pagina
  function verificarSeleccionMenuDesplegable(){
    const datosOpcional = document.getElementById('datos_opcional');
    var value = this.value;
    if(value == 'administrador' || value == 'contratista' ){
      //agregar required a los campos contrasena, confcontrasena
      datosOpcional.removeAttribute("hidden");
    }else{
      datosOpcional.setAttribute("hidden", true);
    }
  }