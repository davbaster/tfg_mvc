  //se activa cuando se seleccina una opcion del combobox de roles
  document.getElementById('rol').onchange = verificarSeleccionMenuDesplegable;

  //esconde o muestra datos opcionales del usuario
  //muestra los datos opcionales si el rol no es construccion
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
const btnBuscar                 = document.querySelector('#btnBuscar');
const userContainerView         = document.querySelector('#user-container-view');
const userContainerEdit         = document.querySelector('#user-container-edit');
const userEdit                  = document.querySelector('#user-edit');
// const message                   = document.querySelector('#message');


function verificarCedula(){
  event.preventDefault();
  const cedula = document.querySelector('#cedula_buscar').value;
  //const apellidoBusc = document.querySelector('#apellido1_buscar').value;

  if(cedula === '' || cedula === null){
    //message.innerHTML= ``;
    userContainerView.innerHTML = `Ingrese un n&uacute;mero de c&eacute;dula sin guiones o espacios`;
      //alert('Ingrese un numero de cedula sin guiones o espacios');
  }else{

    // this.datacopy = [...this.data]; 
    userContainerView.innerHTML = '';
    getResultadosBusqueda(cedula);
  }
    
};


//ACTUALIZAR CONTRASENA FORMULARIO Y METODOS
function actualizarContrasena(cedula){
  
    
  
  userContainerView.innerHTML=

  `
  <form action='/user/generarPassword/' method="POST">
    <div class="section">
        <input type="text" name="cedula" id="cedula" value="${cedula}" hidden>

        <label for="contrasena">Nuevo Password</label>
        <input type="password" name="contrasena" id="contrasena" autocomplete="off" required>

        <label for="confcontrasena">Confirmar Password</label>
        <input type="password"  onkeyup='verificarContrasenaIgual();' name="confcontrasena" id="confcontrasena" autocomplete="off" required>

        <span id='message'></span>
        <div><input type="submit" id="btnEnviar" value="Cambiar password" /></div>
        <div class="">
          <a id="btnCancel href="#" onclick="cerrarFormulario(userContainerView)">Cancelar</a>
        </div>
        
    </div>
  </form>
  `;
  
};


//compara si los passwords son iguales
function verificarContrasenaIgual() {
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



if(btnBuscar){
  btnBuscar.addEventListener('click', verificarCedula, false);
}
  


 //localhost se deberia cambiar por la direccion del servidor
//recolecta datos pedidos al servidor y luego los manda a renderData para ser escritos en la vista
async function getResultadosBusqueda(id){

  event.preventDefault();
        
  data = await fetch(`http://localhost:41062/www/user/buscar/${id}`) 
  .then(res =>res.json())
  .then(json => json);
  this.copydata = [...this.data];
 
  renderResultados(data);
}


//pone nuevas filas con informacion en la tabla
function renderResultados(data){
  
  
  let total = 0;

  if (data[0].cedula == 'false') {
    

    userContainerView.innerHTML = data[0].mensaje;
    
  } else {

    dibujarTabla();//dibuja la tabla sin resultados
    var databody = document.querySelector('#databody');//busca el body de la tabla recien dibujada

    databody.innerHTML = '';
    data.forEach(item => { 
        //total += item.amount;
        switch (item.estado) {

            case "inactivo":

                databody.innerHTML += `<tr>
                <td>${item.cedula}</td>
                <td>${item.nombre}</td>
                <td><a id="activarUsuario" href="#" onclick="activarUsuario(${item.cedula})">Activar</a><a id="verUsuario href="#" onclick="ver(${item.cedula})">Ver</a></td>
                </tr>`;

                break;
        
            default:
              databody.innerHTML += `<tr>
              <td>${item.cedula}</td>
              <td>${item.nombre}</td>
              <td><a id="verUsuario href="#" onclick="ver(${item.cedula})">Ver</a></td>
              </tr>`;

                break;
        }
    });
    
  }

}

//dibuja una estructura de tabla
function dibujarTabla(){

  var tableContainer = document.querySelector('#table-container-right-side');

  tableContainer.innerHTML =
  `<table width="100%" cellpadding="0">
      <thead>
          <tr>
          <th data-sort="id">Cedula</th>
          <th data-sort="nombre" width="20%">Trabajador</th>
          <th>Acciones</th>
          </tr>
      </thead>
    <tbody id="databody">
        
    </tbody>
  </table>`;
}


    // Esta funcion es llamada desde el boton ver.
    //se usa asincrona para que no recargue toda la pagina, 
    //Esta funcion al ser asyncrona usa promises
    //https://web.dev/promises/ , https://stackoverflow.com/questions/37533929/how-to-return-data-from-promise
    async function ver(id){

      event.preventDefault();
      
  
      data = await fetch(`http://localhost:41062/www/user/buscar/${id}`)
      .then(res =>res.json())
      .then(json => json);
      this.copydata = [...this.data];
      console.table(data);
      renderUser(data);
      userContainerView.removeAttribute("hidden");
  
      };


    async function deshabilitarUsuario(id){

      event.preventDefault();
      

      data = await fetch(`http://localhost:41062/www/user/deshabilitarUsuario/${id}`)
      .then(res =>res.json())
      .then(json => json);
      this.copydata = [...this.data];
      console.table(data);
      renderUser(data);
      userContainerView.removeAttribute("hidden");

      };



      //recibe un objeto del dom
      //borra el codigo html de ese objeto      
      function cerrarFormulario(formulario){
        

        formulario.innerHTML = ``;
      }

      //Permite editar la informacion de un usuario
      function editar(data){

        userContainerEdit.removeAttribute("hidden");
        userContainerView.setAttribute("hidden", true);//esconde el resultado de busqueda


        userEdit.innerHTML = `<div class="">
        <label for="cedula">Cedula</label>
        <input type="text" name="cedula" id="cedula" required value="${data[0].cedula}"></input>
        </div>
        <div class="">
            <label for="name">Nombre</label>
            <input type="text" name="nombre" id="nombre" required value="${data[0].nombre}"></input>
        </div>
        <div class="">
            <label for="name">Primer Apellido</label>
            <input type="text" name="apellido1" id="apellido1" required value="${data[0].apellido1}"></input>
        </div>
        <div class="">
            <label for="name">Segundo Apellido</label>
            <input type="text" name="apellido2" id="apellido2" required value="${data[0].apellido2}"></input>
        </div>
        <div class="">
            <label for="name">Telefono</label>
            <input type="text" name="telefono" id="telefono" value="${data[0].telefono}"></input>
        </div>
        <div class="">
            <label for="name">Cuenta Bancaria</label>
            <input type="text" name="cuentaBancaria" id="cuentaBancaria" value="${data[0].cuentaBancaria}"></input>
        </div>
        <div class="">
            <label for="name">Direccion</label>
            <input type="text" name="direccion" id="direccion" value="${data[0].direccion}"></input>
        </div>
        <div id="datos_opcional" hidden>
            <div class="">
                <label for="name">Correo Electr&oacute;nico</label>
                <input type="text" name="email" id="email" value="${data[0].email}"></input>
            </div>
            <div class="">
                <label for="name">Contrase&ntilde;a</label>
                <input type="password" name="contrasena" id="contrasena" value=""></input>
            </div>
            <div class="">
                <label for="">Confirmaci&oacute;n Contrase&ntilde;a</label>
                <input type="password" name="confcontrasena" id="confcontrasena" onkeyup='verificarContrasenaIgual()' value=""></input>
                <span id='message'></span>
            </div>
        </div>`;

        switch (data[0].rol) {

          case "administrador":

              userEdit.innerHTML += `        <div class="">
              <label for="rol">Rol del usuario:</label>
                  <select name="rol" id="rol" required>
                      <option value="construccion">Construccion</option>
                      <option value="contratista">Contratista</option>
                      <!-- <option value="contador">Contador</option> -->
                      <option value="administrador" selected>Administrador</option>
                      
                  </select>
              </div>`;

              break;
          
        case "contratista":

              userEdit.innerHTML += `        <div class="">
              <label for="rol">Rol del usuario:</label>
                  <select name="rol" id="rol" required>
                      <option value="construccion">Construccion</option>
                      <option value="contratista" selected>Contratista</option>
                      <!-- <option value="contador">Contador</option> -->
                      <option value="administrador" selected>Administrador</option>
                      
                  </select>
              </div>`;

              break;

          
        case "construccion":

          userEdit.innerHTML += `        <div class="">
          <label for="rol">Rol del usuario:</label>
              <select name="rol" id="rol" required>
                  <option value="construccion" selected>Construccion</option>
                  <option value="contratista" >Contratista</option>
                  <!-- <option value="contador">Contador</option> -->
                  <option value="administrador" selected>Administrador</option>
                  
              </select>
          </div>`;

          break;

      
          default:
            userEdit.innerHTML += `        <div class="">
            <label for="rol">Rol del usuario:</label>
                <select name="rol" id="rol" required>
                    <option value="construccion" selected>Construccion</option>
                    <option value="contratista" >Contratista</option>
                    <option value="contador" selected>Contador</option>
                    <option value="administrador" >Administrador</option>
                    
                </select>
            </div>`;
  
            break;
      }



    }
        




      //inyecta codigo html con la informacion de un usuario
      function renderUser(data){
        

        userContainerView.innerHTML = 
        `<div class="">
            <label for="cedula">Cedula: ${data[0].cedula}</label>
        </div>
        <div class="">
            <label for="name">Nombre: ${data[0].nombre}</label>
        </div>
        <div class="">
            <label for="name">Primer Apellido: ${data[0].apellido1}</label>
        </div>
        <div class="">
            <label for="name">Segundo Apellido: ${data[0].apellido2}</label>
        </div>
        <div class="">
            <label for="name">Telefono: ${data[0].telefono}</label>
        </div>
        <div class="">
            <label for="name">Cuenta Bancaria: ${data[0].cuentaBancaria}</label>
        </div>
        <div class="">
            <label for="name">Direccion: ${data[0].direccion}</label>
        </div>
        <div class="">
            <label for="name">Correo Electr&oacute;nico: ${data[0].email}</label>
        </div>
        <div class="">
            <label for="rol">Rol del usuario: ${data[0].rol}</label>
        </div>
        <div class="">
            <a id="verUsuario href="#" onclick="deshabilitarUsuario(${data[0].cedula})">Deshabilitar Usuario</a>
        </div>
        <div class="">
            <a id="verUsuario href="#" onclick="editar(data)">Editar Informacion</a>
        </div>
          `;

        //entre si tiene accesso al sistema, por lo que tiene contrase;a
        if( data[0].rol != "construccion" ){

          userContainerView.innerHTML += `
          <div class="">
            <a id="verUsuario href="#" onclick="actualizarContrasena(${data[0].cedula})">Generar Contrase&ntilde;a</a>
          </div>
          `;

        }

        userContainerView.innerHTML += `
        <div class="">
          <a id="verUsuario href="#" onclick="cerrarFormulario(userContainerView)">Cerrar</a>
        </div>`;

        
      }

      //cierra la vista de edicion de usuario
      //vuelve a la vista de resultado de busqueda
      function cancelarEdicion(){

        userContainerEdit.setAttribute('hidden', 'true');
        
        userContainerView.setAttribute('hidden', 'false');
      }