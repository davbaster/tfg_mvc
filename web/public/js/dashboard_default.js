
const peticionCard = document.querySelector('#peticionPagoOpen');




//muestra dialogo para crear una planilla.
//cuando se da click en el boton crear peticion
peticionCard.addEventListener('click', async () =>{
  event.preventDefault();
  
  const value = event.currentTarget.lastElementChild.lastElementChild.value;
  

  
  const html = await getContentPeticionPago(value);
  ajaxcontent.innerHTML+= html;
  
});


//localhost se deberia cambiar por la direccion del servidor
async function getContentPeticionPago($id){
        
  data = await fetch(`http://localhost:41062/www/peticionespago/getPeticionPagoJSON/${id}`) 
  .then(res =>res.json())
  .then(json => json);
  this.copydata = [...this.data];
 
  renderData(data);
}


function renderData(data){
  const newPeticion = document.querySelector('#new-peticion-pago');
  
  newPeticion.value = $data.id_planilla;
}

