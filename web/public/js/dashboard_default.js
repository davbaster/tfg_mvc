

if (document.body.addEventListener){
  document.body.addEventListener('click',peticionCard,false);
}
else{
  document.body.attachEvent('onclick',peticionCard);//for IE
}

//handler que maneja los clicks a las tarjetas de peticionesPagos/planillas en estado open
function peticionCard(e){
    e = e || window.event;
    var target = e.target || e.srcElement;

    $value = target.value;
    var id = target.id;

    var idNoNumbers = id.replace(/[0-9]/g, '');


    switch (idNoNumbers){

      case 'peticionPagoOpenCard':
            $value = target.lastElementChild.lastElementChild.value;
            //target.classList.add('active'); //ver el elemento seleccionado resaltado
            resaltarSeleccion(target.id)
            getContentPeticionPago($value);
            break;

      case 'peticionPagoOpenFecha':
            $value = target.parentElement.lastElementChild.lastElementChild.value;
            resaltarSeleccion(target.parentElement.id)
            getContentPeticionPago($value);
            break;

      case 'peticionPagoOpenTitulo':
            $value = target.parentElement.lastElementChild.lastElementChild.value;
            resaltarSeleccion(target.parentElement.id)
            getContentPeticionPago($value);
            break;

      case 'peticionPagoOpenMonto':
            $value = target.parentElement.lastElementChild.lastElementChild.value;
            resaltarSeleccion(target.parentElement.id)
            getContentPeticionPago($value);
            break;

      case 'peticionPagoOpenPlanilla':
            $value = target.parentElement.lastElementChild.lastElementChild.value;
            resaltarSeleccion(target.parentElement.id)
            getContentPeticionPago($value);
            break;

    }
}




//localhost se deberia cambiar por la direccion del servidor
//recolecta datos pedidos al servidor y luego los manda a renderData para ser escritos en la vista
async function getContentPeticionPago(id){
        
  data = await fetch(`http://localhost:41062/www/dashboard/getPeticionPagoJSON/${id}`) 
  .then(res =>res.json())
  .then(json => json);
  this.copydata = [...this.data];
 
  renderData(data);
}


function renderData(data){
  const newPagoBtn = document.querySelector('#new-pago');
  const newPrestamoBtnRender = document.querySelector('#new-prestamo');
  const cerrarPeticionPagoBtn = document.querySelector('#cerrar-peticion-pago');
  const modifyPeticionPagoBtn = document.querySelector('#modify-peticion-pago');

  $idPlanilla = data[0].id_planilla;
  

  cerrarPeticionPagoBtn.setAttribute("value", $idPlanilla);//asigna al boton de cerrar planilla el valor del card (planilla seleccionada)


  newPagoBtn.setAttribute("value", $idPlanilla);//asigna al boton de crear pago el valor del card (planilla seleccionada)

  newPrestamoBtnRender.setAttribute("value", $idPlanilla);//asigna al boton de crear prestamo el valor del card (planilla seleccionada)

  modifyPeticionPagoBtn.setAttribute("value", $idPlanilla);//asigna al boton de crear prestamo el valor del card (planilla seleccionada)

  //Actualizar los datos de planilla en expenses-summary//TODO 
  
}

//recibe un id del objeto al que se le hizo click, para resaltarlo
//busca el objeto previo que estaba resaltado para quitarle la clase active.
//Debe resaltar el objeto parent/card 
function resaltarSeleccion($id){

  var idOject = "#"+$id

  var seleccionActual = document.getElementsByClassName("card-active");
  var nuevaSeleccion = document.querySelector(idOject);

  //entre si hay una seleccion previa
  if (seleccionActual.length != 0) {
    
    seleccionActual[0].classList.add("card-normal")
    seleccionActual[0].classList.remove("card-active");//se remueve la clase al final, sino no podremos agregar la clase nueva
  }
  
  nuevaSeleccion.classList.remove("card-normal");
  nuevaSeleccion.classList.add("card-active");




}

