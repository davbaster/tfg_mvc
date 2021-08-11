
//compara si los passwords son iguales
var check = function() {
    if (document.getElementById('current_password').value !=
      document.getElementById('new_password').value) {
      document.getElementById('message').style.color = 'green';
      document.getElementById('message').innerHTML = 'Contrase&ntilde;a es diferente';
      document.getElementById("btnEnviar").disabled = false;
    } else {
      document.getElementById('message').style.color = 'red';
      document.getElementById('message').innerHTML = 'Contrase&ntilde;a es igual a la anterior';
      // document.getElementById("confcontrasena").required = true;
      document.getElementById("btnEnviar").disabled = true;
    }
  }