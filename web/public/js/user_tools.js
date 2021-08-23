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
const btnBuscar                 = document.querySelector('#btnBuscar');
const userContainerView         = document.querySelector('#user-container-view');
const userContainerEdit         = document.querySelector('#user-container-edit');
const userEdit                  = document.querySelector('#user-edit');




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
        userContainerView.innerHTML = '';
        getResultadosBusqueda(cedula);
      }
     
  });


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

      //recibe un objeto del dom
      //borra el codigo html de ese objeto      
      function cerrarFormulario(formulario){
        

        formulario.innerHTML = ``;
      }

      //Permite editar la informacion de un usuario
      function editar(data){

        userContainerEdit.removeAttribute("hidden");
        userContainerView.setAttribute("hidden", true);//esconde el resultado de busqueda

        

        userEdit.innerHTML = 
        `HOLA MUNDO SOY DAVID
        <div class="">
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
        </div>`;


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
            <a id="verUsuario href="#" onclick="editar(data)">Editar Informacion</a>
        </div>
        <div class="">
            <a id="verUsuario href="#" onclick="cerrarFormulario(userContainerView)">Cerrar</a>
        </div>`;
        
      }

      //cierra la vista de edicion de usuario
      //vuelve a la vista de resultado de busqueda
      function cancelarEdicion(){

        userContainerEdit.innerHTML=``;
        
        userContainerView.removeAttribute(hidden);
      }