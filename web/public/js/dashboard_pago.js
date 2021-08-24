
const btnPago = document.querySelector('#new-pago');


//despliega el formulario para crear un nuevo pago
//obtiene el valor de la peticionPago/planilla del value del boton.
async function desplegarFormularioPago(){
  event.preventDefault();
  
  const value = event.currentTarget.value //value contien la PeticionPagoID/planilla que esta seleccionada 

  const background = document.createElement('div');
  const panel = document.createElement('div');
  const titlebar = document.createElement('div');
  const closeButton = document.createElement('a');
  const closeButtonText = document.createElement('i');
  const ajaxcontent = document.createElement('div');


  background.setAttribute('id', 'background-container');
  panel.setAttribute('id', 'panel-container');
  titlebar.setAttribute('id', 'title-bar-container');
  closeButton.setAttribute('class', 'close-button');
  //closeButton.setAttribute('href', '#');
  closeButtonText.setAttribute('class', 'material-icons');
  ajaxcontent.setAttribute('id', 'ajax-content');

  background.appendChild(panel);
  panel.appendChild(titlebar);
  panel.appendChild(ajaxcontent);
  titlebar.appendChild(closeButton);
  closeButton.appendChild(closeButtonText);
  closeButtonText.appendChild(document.createTextNode('close'));
  document.querySelector('#main-container').appendChild(background);

  closeButton.addEventListener('click', e =>{
    background.remove();
  });

  
  const html = await getContentPago(value);//manda el id de la planilla
  ajaxcontent.innerHTML+= html;
  
};


//localhost se deberia cambiar por la direccion del servidor
async function getContentPago(id){
  //const html = await fetch('http://localhost:41062/www/peticionespago/viewPago').then(res => res.text());
  const html = await fetch(`http://localhost:41062/www/dashboard/viewNewPagoDialog/${id}`).then(res => res.text());

  return html;   
}

//verica que el boton exista antes de asignarle un eventHanlder
if(btnPago){
  btnPago.addEventListener('click', desplegarFormularioPago, false);
}

