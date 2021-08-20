
// const peticionCard = document.querySelector('#peticionPagoOpen');




//muestra dialogo para crear una planilla.
//cuando se da click en el boton crear peticion
// peticionCard.addEventListener('click', async () =>{
//   event.preventDefault();
  
//   const value = event.currentTarget.lastElementChild.lastElementChild.value;
  

  
//   const html = await getContentPeticionPago(value);
//   // ajaxcontent.innerHTML+= html;
  
// });

// peticionCard.addEventListener('click', e =>{
//   event.preventDefault();
  
//   const value = event.currentTarget.lastElementChild.lastElementChild.value;
  

  
//   getContentPeticionPago(value);
//   // ajaxcontent.innerHTML+= html;
  
// });



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
async function getContentPeticionPago(id){
        
  data = await fetch(`http://localhost:41062/www/dashboard/getPeticionPagoJSON/${id}`) 
  .then(res =>res.json())
  .then(json => json);
  this.copydata = [...this.data];
 
  renderData(data);
}


function renderData(data){
  const newPagoBtn = document.querySelector('#new-pago');
  
  newPagoBtn.value = data[0].id_planilla;
}

