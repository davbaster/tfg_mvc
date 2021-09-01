

  // LOGICA PARA RELLENAR UNA TABLA CON DATOS QUE VIENEN DE UN JSON DEL SERVIDOR
//Hay algoritmos para ordenar por fecha, por planilla, por estado


var data = [];
var copydata = [];
const btnBuscarPlanillas                  = document.querySelector('#btnBuscarPlanillas');
const planillasContainerView              = document.querySelector('#planillas-container-view');
const tablePlanillas                      = document.querySelector('#table-planillas-container-right-side');




function verificarCedulaPlanillas(){
  event.preventDefault();
  const cedulaPlanillas = document.querySelector('#cedula_buscar_planillas').value;
  //const apellidoBusc = document.querySelector('#apellido1_buscar').value;

  if(cedulaPlanillas === '' || cedulaPlanillas === null){
    //message.innerHTML= ``;
    planillasContainerView.innerHTML = `Ingrese un n&uacute;mero de c&eacute;dula sin guiones o espacios`;
      //alert('Ingrese un numero de cedula sin guiones o espacios');
  }else{

    // this.datacopy = [...this.data]; 
    planillasContainerView.innerHTML = '';
    getResultadosBusquedaPlanillas(cedulaPlanillas);
  }
    
};



//verificacion de datos correctos en campo cedula
if(btnBuscarPlanillas){
  btnBuscarPlanillas.addEventListener('click', verificarCedulaPlanillas, false);
}





 //localhost se deberia cambiar por la direccion del servidor
//recolecta datos pedidos al servidor y luego los manda a renderData para ser escritos en la vista
async function getResultadosBusquedaPlanillas(cedula){

  event.preventDefault();
        
  data = await fetch(`http://localhost:41062/www/reportes/buscarPlanillas/${cedula}`) 
  .then(res =>res.json())
  .then(json => json);
  this.copydata = [...this.data];
 
  renderResultadosPlanillas(data);
}

//dibuja una estructura de tabla
function dibujarTablaPlanillas(){

  

  tablePlanillas.innerHTML =
  `<table width="100%" cellpadding="0">
      <thead>
          <tr>
          <th data-sort="id-planilla">ID</th>
          <th data-sort="id">Cedula</th>
          <th data-sort="nombre" width="20%">Contratista</th>
          <th>Monto</th>
          <th>Fecha</th>
          </tr>
      </thead>
    <tbody id="databodyPlanillas">
        
    </tbody>
  </table>`;
}



//pone nuevas filas con informacion en la tabla
function renderResultadosPlanillas(data){
  
  
  let total = 0;

  if (data[0].cedula == 'false') {
    

    planillasContainerView.innerHTML = data[0].mensaje;
    
  } else {

    dibujarTablaPlanillas();//dibuja la tabla sin resultados
    const databodyPlanillas = document.querySelector('#databodyPlanillas');//busca el body de la tabla recien dibujada

    databodyPlanillas.innerHTML = '';
    data.forEach(item => { 
       
      databodyPlanillas.innerHTML += `<tr>
                <td>${item.id_planilla}</td>
                <td>${item.cedula_contratista}</td>
                <td>${item.nombre}</td>
                <td>${item.monto}</td>
                <td>${item.fecha_creacion}</td>
                </tr>`;

        
    });
    databodyPlanillas.innerHTML += 
    
    `<tr>
      <div class="">
        <a id="verUsuario href="#" onclick="cerrarFormulario(tablePlanillas)">Cerrar</a>
      </div>
    </tr>`;
    // planillasContainerView.innerHTML = ``;
    
  }

}



    // Esta funcion es llamada desde el boton ver.
    //se usa asincrona para que no recargue toda la pagina, 
    //Esta funcion al ser asyncrona usa promises
    //https://web.dev/promises/ , https://stackoverflow.com/questions/37533929/how-to-return-data-from-promise
    async function verPlanillas(id){

      event.preventDefault();
      
  
      data = await fetch(`http://localhost:41062/www/user/buscar/${id}`)
      .then(res =>res.json())
      .then(json => json);
      this.copydata = [...this.data];
      console.table(data);
      renderPago(data);
      userContainerViewPagos.removeAttribute("hidden");
  
      };


   
      //recibe un objeto del dom
      //borra el codigo html de ese objeto      
      function cerrarFormulario(formulario){
        

        formulario.innerHTML = ``;
      }

      



      //inyecta codigo html con la informacion de un usuario
      function renderPlanillas(data){
        

        userContainerViewPlanillas.innerHTML = 
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

          userContainerViewPlanillas.innerHTML += `
          <div class="">
            <a id="verUsuario href="#" onclick="desplegarFormularioContrasena(${data[0].cedula})">Generar Contrase&ntilde;a</a>
          </div>
          `;

        }

        userContainerViewPlanillas.innerHTML += `
        <div class="">
          <a id="verUsuario href="#" onclick="cerrarFormulario(userContainerView)">Cerrar</a>
        </div>`;

        
      }
