
const btnPago = document.querySelector('#new-pago');

btnPago.addEventListener('click', async () =>{
  event.preventDefault();
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

  
  const html = await getContentPago();
  ajaxcontent.innerHTML+= html;
  
});

//localhost se deberia cambiar por la direccion del servidor
async function getContentPago(){
  //const html = await fetch('http://localhost:41062/www/peticionespago/viewPago').then(res => res.text());
  const html = await fetch('http://localhost:41062/www/dashboard/viewNewPagoDialog').then(res => res.text());

  return html;   
}

