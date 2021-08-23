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



  // LOGICA PARA RELLENAR UNA TABLA CON DATOS QUE VIENEN DE UN JSON DEL SERVIDOR
//Hay algoritmos para ordenar por fecha, por planilla, por estado


var data = [];
var copydata = [];
const btnBuscar         = document.querySelector('#btnBuscar');

    //cuando hay click en el boton btnBuscar
    //revise que el valor no es nulo
    //copie la data desde el promise
    //
    btnBuscar.addEventListener('click', e =>{
      const cedula = document.querySelector('#cedula_buscar').value;
      //const apellidoBusc = document.querySelector('#apellido1_buscar').value;

      if(cedula === '' || cedula === null){

          alert('Ingrese un numero de cedula sin guiones o espacios');
      }else{

        // this.datacopy = [...this.data]; 
        getResultadosBusqueda(cedula);
      }
     
  });


 //localhost se deberia cambiar por la direccion del servidor
//recolecta datos pedidos al servidor y luego los manda a renderData para ser escritos en la vista
async function getResultadosBusqueda(id){
        
  data = await fetch(`http://localhost:41062/www/user/buscar/${id}`) 
  .then(res =>res.json())
  .then(json => json);
  this.copydata = [...this.data];
 
  renderResultados(data);
}


//pone nuevas filas con informacion en la tabla
function renderResultados(data){
  var databody = document.querySelector('#databody');
  let total = 0;
  databody.innerHTML = '';
  data.forEach(item => { 
      //total += item.amount;
      switch (item.estado) {

          case "inactivo":

              databody.innerHTML += `<tr>
              <td>${item.cedula}</td>
              <td>${item.nombre}</td>
              <td><a id="activarUsuario" href="#" onclick="activarUsuario(${item.cedula})">Activar</a><a id="verUsuario href="#" onclick="verUsuario(${item.cedula})">Ver</a></td>
              </tr>`;

              break;
      
          default:
            databody.innerHTML += `<tr>
            <td>${item.cedula}</td>
            <td>${item.nombre}</td>
            <td><a id="verUsuario href="#" onclick="verUsuario(${item.cedula})">Ver</a></td>
            </tr>`;

              break;
      }
  });
}