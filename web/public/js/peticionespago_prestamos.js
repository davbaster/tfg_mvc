// LOGICA PARA RELLENAR UNA TABLA CON DATOS QUE VIENEN DE UN JSON DEL SERVIDOR
//Hay algoritmos para ordenar por fecha, por planilla, por estado


    //var data = [];
    //var copydata = [];








    //se inicia automaticamente cuando se carga la pagina
    //Esta funcion al ser asyncrona usa promises
    //solo se puede acceder al array que viene dentro de la promise [...this.data] si la funcion es async, y se llama desde otra parte
    //https://web.dev/promises/ , https://stackoverflow.com/questions/37533929/how-to-return-data-from-promise
    async function getPrestamos(){
        
        data = await fetch('http://localhost:41062/www/prestamos/getPrestamos')
        .then(res =>res.json())
        .then(json => json);
        // this.copydata = [...this.data];
        // console.table(data);
        renderDataPrestamos(data);
    }

    getPrestamos();





    function numberWithCommas(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    //renderiza la informacion de prestamos en la tabla cuando se recarga la pagina

    function renderDataPrestamos(data){

        var databodyPre = document.querySelector('#databody-prestamos');
        databodyPre.innerHTML = '';


        if (data[0].cedula == 'false') {
    
            infoPrestamos.innerHTML = data[0].mensaje;
            
        }else{

            data.forEach(item => { 

                switch (item.estado) {
    
                    case "pendiente":
    
                        databodyPre.innerHTML += `<tr>
                        <td data-titulo="ID Prestamo:">${item.id_prestamo}</td data-titulo="Numero:">
                        <td data-titulo="Nombre:">${item.nombre}</td data-titulo="Numero:">
                        <td data-titulo="Fecha Creacion:">${item.fecha_creacion}</td data-titulo="Numero:">
                        <td data-titulo="Monto:">¢${item.monto}</td data-titulo="Numero:">
                        <td data-titulo="Estado:">${item.estado}</td data-titulo="Numero:">
                        <td>
                            <a class="btnVer show" id="btnVerItem_pre_${item.id_prestamo}" href="#" onclick="verPrestamo(${item.id_prestamo})">Ver</a>
                        </td>
                        </tr>
                        <tr>
                            <div class="planilla-container hide" id="info_pre_${item.id_prestamo}" value="${item.id_prestamo}"></div>
                        </tr>
                        `;
                        
    
                        break;
                
                    default:
                        databodyPre.innerHTML += `<tr>
                        <td data-titulo="ID Prestamo:">${item.id_prestamo}</td>
                        <td data-titulo="Nombre:">${item.nombre}</td>
                        <td data-titulo="Fecha Creacion:">${item.fecha_creacion}</td>
                        <td data-titulo="Monto:">¢${item.monto}</td>
                        <td data-titulo="Estado:">${item.estado}</td>
                        <td>
                            <a class="btnVer show" id="btnVerItem_pre_${item.id_prestamo}" href="#" onclick="verPrestamo(${item.id_prestamo})">Ver</a>
                        </td>                  
                        </tr>
                        <div class="planilla-container hide" id="info_pre_${item.id_prestamo}" value="${item.id_prestamo}"></div>
                        `;
                        
                        break;
                }
            });


        }   



        
    }


    //renderiza un objeto prestamo en un DIV debajo de un row de la tabla prestamos
    function renderPrestamo(data, id_prestamo){
        
        var divInfoPrestamoId = `info_pre_${id_prestamo}`;
        const divInfoPrestamo = document.querySelector(`#${divInfoPrestamoId}`);
        divInfoPrestamo.classList.replace("hide","show");


        divInfoPrestamo.innerHTML =

        `<div class="prestamo-item view-prestamo-info">
            <div class="">
                <label for="planillaIdPr">Planilla No.: ${data[0].peticion_pago_id}</label>
                <a id="verPlanillaInfo" href="#" onclick="getDataPlanilla(${data[0].id_planilla})">Ver Planilla</a>
            </div>
            <div class="">
                <label for="cedulaPr">Contratista: ${data[0].cedula}</label>
            </div>
            <div class="">
                <label for="descripcionPr">Razon: ${data[0].detalles}</label>
            </div>
            <div class="">
                <label for="estadoPr">Estado: ${data[0].estado}</label>
            </div>
            <div class="">
                <label for="nombrePr">Nombre: ${data[0].nombre}</label>
            </div>
            <div class="">
                <label for="apellido1Pr">Primer Apellido: ${data[0].apellido1}</label>
            </div>
            <div class="">
                <label for="apellido2Pr">Segundo Apellido: ${data[0].apellido2}</label>
            </div>
            <div class="">
                <label for="apellido1Pr">Monto: ${data[0].monto}</label>
            </div>
            <div class="">
                <label for="fecha_creacionPr">Creada: ${data[0].fecha_creacion}</label>
            </div>
        </div>
        <div class="prestamo-item" id="view-info-planilla_${data[0].id_planilla}"></div>
           `;


        switch (data[0].estado) {

            case "pendiente":

                divInfoPrestamo.innerHTML +=
                    `<div class="planilla-item" id="view-info-acciones">
                        <a id="verUsuario href="#" onclick="cerrarFormularioPrestamo(${id_prestamo})">Cerrar</a>
                        <a id="verUsuario href="#" onclick="rechazarPrestamo(${id_prestamo})">Rechazar</a>
                        <a id="pagarItem" href="#" onclick="autorizarPrestamo(${id_prestamo})">Aprobar</a>
                        
                        
                    </div>`;
                
                break;
        
            default:

                divInfoPrestamo.innerHTML +=
                    `<div class="planilla-item" id="view-info-acciones">
                        <a id="verUsuario href="#" onclick="cerrarFormularioPrestamo(${id_prestamo})">Cerrar</a>
                       
                    </div>`;

                break;
        }
        
        
      }




    // Esta funcion es llamada desde el boton pagar.
    //se usa asincrona para que no recargue toda la pagina, solo tabla
    //Esta funcion al ser asyncrona usa promises
    //https://web.dev/promises/ , https://stackoverflow.com/questions/37533929/how-to-return-data-from-promise
    async function autorizarPrestamo(idPrestamo){

    event.preventDefault();

    data = await fetch(`http://localhost:41062/www/prestamos/autorizar/${idPrestamo}`)
    .then(res =>res.json())
    .then(json => json);
    // this.copydata = [...this.data];
    // console.table(data);
    renderPrestamo(data, idPrestamo);



    };


    // Esta funcion es llamada desde el boton ver.
    //se usa asincrona para que no recargue toda la pagina, 
    //Esta funcion al ser asyncrona usa promises
    //https://web.dev/promises/ , https://stackoverflow.com/questions/37533929/how-to-return-data-from-promise
    async function verPrestamo(id){

        event.preventDefault();
        var botonVer = document.querySelector(`#btnVerItem_pre_${id}`);
        botonVer.classList.replace('show','hide');
    
        data = await fetch(`http://localhost:41062/www/prestamos/buscar/${id}`)
        .then(res =>res.json())
        .then(json => json);
        //this.copydata = [...this.data];
        //console.table(data);
        renderPrestamo(data, id);
        //userContainerView.removeAttribute("hidden");
    
        };


    //rechaza la peticion de pago, 
    //manda a cambiar el estado de pendiente a OPEN
    async function rechazarPrestamo(id){

        event.preventDefault();
        var botonVer = document.querySelector(`#btnVerItem_pre_${id}`);
        botonVer.classList.replace('show','hide');
    
        data = await fetch(`http://localhost:41062/www/prestamos/rechazarPrestamo/${id}`)
        .then(res =>res.json())
        .then(json => json);

        renderPrestamo(data, id);//devuelve los datos, y el id del prestamo rechazado
    
    };
  
    //recibe un objeto del dom
    //borra el codigo html de ese objeto      
    function cerrarFormularioPrestamo(id){
        
        //formulario.innerHTML = ``;

        //muestra boton Ver planilla
        var botonVer = document.querySelector(`#btnVerItem_pre_${id}`);
        botonVer.classList.replace("hide","show");

        //esconde formulario ver planilla
        const formulario = document.querySelector(`#info_pre_${id}`);          
        formulario.classList.replace("show","hide");

        getPrestamos(); //actualizar la tabla
    }


    


        
 