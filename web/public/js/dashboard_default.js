
const peticionCard = document.querySelector('#peticionPagoOpen');




//muestra dialogo para crear una planilla.
//cuando se da click en el boton crear peticion
// peticionCard.addEventListener('click', async () =>{
//   event.preventDefault();
  
//   const value = event.currentTarget.lastElementChild.lastElementChild.value;
  

  
//   const html = await getContentPeticionPago(value);
//   // ajaxcontent.innerHTML+= html;
  
// });

peticionCard.addEventListener('click', e =>{
  event.preventDefault();
  
  const value = event.currentTarget.lastElementChild.lastElementChild.value;
  

  
  getContentPeticionPago(value);
  // ajaxcontent.innerHTML+= html;
  
});




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

