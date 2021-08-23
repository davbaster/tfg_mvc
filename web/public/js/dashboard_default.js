

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

    switch (target.id){

      case 'peticionPagoOpenCard':
            $value = target.lastElementChild.lastElementChild.value;
            // target.classList.add('active'); //ver el elemento seleccionado resaltado
            getContentPeticionPago($value);
            break;

      case 'peticionPagoOpenFecha':
            $value = target.parentElement.lastElementChild.lastElementChild.value;
            getContentPeticionPago($value);
            break;

      case 'peticionPagoOpenTitulo':
            $value = target.parentElement.lastElementChild.lastElementChild.value;
            getContentPeticionPago($value);
            break;

      case 'peticionPagoOpenMonto':
            $value = target.parentElement.lastElementChild.lastElementChild.value;
            getContentPeticionPago($value);
            break;

      case 'peticionPagoOpenPlanilla':
            $value = target.parentElement.lastElementChild.lastElementChild.value;
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
  const cerrarPeticionPagoBtn = document.querySelector('#cerrar-peticion-pago');
  $idPlanilla = data[0].id_planilla;
  
  //cerrarPeticionPagoBtn.value = $idPlanilla; //asignar valor del card (planilla seleccionada) al boton de cerrar planilla, 
  cerrarPeticionPagoBtn.setAttribute("value", $idPlanilla);
  //newPagoBtn.value = $idPlanilla;//asigna al boton de crear pago el valor del card (planilla seleccionada)

  newPagoBtn.setAttribute("value", $idPlanilla);
  

  
  
}

