  // //se activa cuando se seleccina una opcion del combobox de roles
  // document.getElementById('rol').onchange = verificarSeleccionMenuDesplegable;

  // //esconde o muestra datos opcionales del usuario
  // //muestra los datos opcionales si el rol no es construccion
  // function verificarSeleccionMenuDesplegable(){
  //   const datosOpcional = document.getElementById('datos_opcional');
  //   var value = this.value;
  //   if(value == 'administrador' || value == 'contratista' ){
  //     //agregar required a los campos contrasena, confcontrasena
  //     datosOpcional.removeAttribute("hidden");
  //   }else{
  //     datosOpcional.setAttribute("hidden", true);
  //   }
  // }












  // LOGICA PARA RELLENAR UNA TABLA CON DATOS QUE VIENEN DE UN JSON DEL SERVIDOR
//Hay algoritmos para ordenar por fecha, por planilla, por estado


var data = [];
var copydata = [];
const btnBuscar                 = document.querySelector('#btnBuscar');
const userContainerViewPagos    = document.querySelector('#user-container-view-pagos');
const userContainerEdit         = document.querySelector('#user-container-edit');
const userEdit                  = document.querySelector('#user-edit');
// const message                   = document.querySelector('#message');



function verificarCedulaPagos(){
  event.preventDefault();
  const cedula = document.querySelector('#cedula_buscar_pagos').value;
  //const apellidoBusc = document.querySelector('#apellido1_buscar').value;

  if(cedula === '' || cedula === null){
    //message.innerHTML= ``;
    userContainerViewPagos.innerHTML = `Ingrese un n&uacute;mero de c&eacute;dula sin guiones o espacios`;
      //alert('Ingrese un numero de cedula sin guiones o espacios');
  }else{

    // this.datacopy = [...this.data]; 
    userContainerViewPagos.innerHTML = '';
    getResultadosBusquedaPagos(cedula);
  }
    
};


//verificacion de datos correctos en campo cedula
if(btnBuscarPagos){
  btnBuscarPagos.addEventListener('click', verificarCedulaPagos, false);
}

  


 //localhost se deberia cambiar por la direccion del servidor
//recolecta datos pedidos al servidor y luego los manda a renderData para ser escritos en la vista
async function getResultadosBusquedaPagos(id){

  event.preventDefault();
        
  data = await fetch(`http://localhost:41062/www/reportes/buscarPagos/${id}`) 
  .then(res =>res.json())
  .then(json => json);
  this.copydata = [...this.data];
 
  renderResultadosPagos(data);
}


//pone nuevas filas con informacion en la tabla
function renderResultadosPagos(data){
  
  
  let total = 0;

  if (data[0].cedula == 'false') {
    

    userContainerViewPagos.innerHTML = data[0].mensaje;
    
  } else {

    dibujarTabla();//dibuja la tabla sin resultados
    var databody = document.querySelector('#databody');//busca el body de la tabla recien dibujada

    databody.innerHTML = '';
    data.forEach(item => { 
       
                databody.innerHTML += `<tr>
                <td>${item.cedula}</td>
                <td>${item.nombre}</td>
                <td>${item.adeudado}</td>
                </tr>`;

        
    });
    
  }

}

//dibuja una estructura de tabla
function dibujarTabla(){

  var tableContainer = document.querySelector('#table-pagos-container-right-side');

  tableContainer.innerHTML =
  `<table width="100%" cellpadding="0">
      <thead>
          <tr>
          <th data-sort="id">Cedula</th>
          <th data-sort="nombre" width="20%">Trabajador</th>
          <th>Cantidad</th>
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
      userContainerViewPagos.removeAttribute("hidden");
  
      };


   
      //recibe un objeto del dom
      //borra el codigo html de ese objeto      
      function cerrarFormulario(formulario){
        

        formulario.innerHTML = ``;
      }

      



      //inyecta codigo html con la informacion de un usuario
      function renderUser(data){
        

        userContainerViewPagos.innerHTML = 
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

          userContainerViewPagos.innerHTML += `
          <div class="">
            <a id="verUsuario href="#" onclick="desplegarFormularioContrasena(${data[0].cedula})">Generar Contrase&ntilde;a</a>
          </div>
          `;

        }

        userContainerViewPagos.innerHTML += `
        <div class="">
          <a id="verUsuario href="#" onclick="cerrarFormulario(userContainerView)">Cerrar</a>
        </div>`;

        
      }
