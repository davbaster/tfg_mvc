//compara si los passwords son iguales
function verificarContrasena() {
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






