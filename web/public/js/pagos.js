// LOGICA PARA RELLENAR UNA TABLA CON DATOS QUE VIENEN DE UN JSON DEL SERVIDOR
//Hay algoritmos para ordenar por fecha, por planilla, por estado


    var data = [];
    var copydata = [];
    const sdatePagosAutorizados         = document.querySelector('#sdate-pagos-autorizados');
    const speticionpagoPagosAutorizados = document.querySelector('#speticionpago-pagos-autorizados');
    const sorts = document.querySelectorAll('th');
   


    //cuando hay algun cambio en el id=sdate
    //revise que el valor no es nulo
    //copie la data desde el promise
    //
    sdatePagosAutorizados.addEventListener('change', e =>{
        const value = e.target.value;
        if(value === '' || value === null){
            this.copydata = [...this.data];
            checkForFilters(speticionpagoPagosAutorizados);

            return;
        }
        filterByDate(value);
    });

    speticionpagoPagosAutorizados.addEventListener('change', e =>{
        const value = e.target.value;
        if(value === '' || value === null){
            this.copydata = [...this.data];
            checkForFilters(sdatePagosAutorizados);

            return;
        }
        filterByPlanilla(value);
    });


    function checkForFilters(object){
        if(object.value != ''){
            //console.log('hay un filtro de ' + object.id);
            switch(object.id){
                case 'sdate-pagos-autorizados':
                    filterByDate(object.value);
                break;  

                case 'speticionpago-pagos-autorizados':
                    filterByPlanilla(object.value);
                break;

                default:
            }
        }else{
            this.datacopy = [...this.data]; 
            renderData(this.datacopy);
        }
    }

    sorts.forEach(item =>{
        item.addEventListener('click', e =>{
            if(item.dataset.sort){  
                    sortBy(item.dataset.sort);        
            }
        });
    });



    //item es un objeto que contiene un array
    //.fecha_creacion es un key de un array, item.fecha_creacion esta accediendo a un valor del array.
    function filterByDate(value){
        this.copydata = [...this.data];
        const res = this.copydata.filter(item =>{
           
            return value == item.fecha_creacion.substr(0, 7);
        });
        this.copydata = [...res];
        renderData(res);
    }

    //.planilla es un key de un array, item.planilla esta accediendo a un valor del array.
    function filterByPlanilla(value){
        this.copydata = [...this.data];
        const res = this.copydata.filter(item =>{
            return value == item.planilla_id;
        });
        this.copydata = [...res];
        renderData(res);
    }



    //Esta funcion al ser asyncrona usa promises
    //solo se puede acceder al array que viene dentro de la promise [...this.data] si la funcion es async, y se llama desde otra parte
    //https://web.dev/promises/ , https://stackoverflow.com/questions/37533929/how-to-return-data-from-promise
    async function getData(){
        
        data = await fetch('http://localhost:41062/www/pagos/getPagosAutorizados')
        .then(res =>res.json())
        .then(json => json);
        this.copydata = [...this.data];
        console.table(data);
        renderData(data);
    }

    getData();





    function numberWithCommas(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }


    //pone nuevas filas con informacion en la tabla
    function renderData(data){
        var databody = document.querySelector('#databody');
        //TODO Aqui deberia ir una funcion que actualice tambien la info del dropdown menu, o que actualice la pagina sin que se vaya al primer tab
        let total = 0;
        databody.innerHTML = '';
        data.forEach(item => { 
            //total += item.amount;
            switch (item.estado) {

                case "autorizado":

                    databody.innerHTML += `<tr>
                    <td>${item.id_pago}</td>
                    <td>${item.nombre}</td>
                    <td>${item.planilla_id}</td>
                    <td>${item.fecha_creacion}</td>
                    <td>??${item.adeudado}</td>
                    <td>${item.estado}</td>
                    <td><a id="pagarItem" href="#" onclick="pagar(${item.id_pago})">Pagar</a></td>
                    </tr>`;

                    break;
            
                default:
                    databody.innerHTML += `<tr>
                    <td>${item.id_pago}</td>
                    <td>${item.nombre}</td>
                    <td>${item.planilla_id}</td>
                    <td>${item.fecha_creacion}</td>
                    <td>??${item.adeudado}</td>
                    <td>${item.estado}</td>
                    <td></td>
                    
                    </tr>`;
                    break;
            }
        });
    }

    // Esta funcion es llamada desde el boton pagar.
    //se usa asincrona para que no recargue toda la pagina, solo tabla
    //Esta funcion al ser asyncrona usa promises
    //https://web.dev/promises/ , https://stackoverflow.com/questions/37533929/how-to-return-data-from-promise
    async function pagar(id){

    event.preventDefault();

    data = await fetch(`http://localhost:41062/www/pagos/pagar/${id}`)
    .then(res =>res.json())
    .then(json => json);
    this.copydata = [...this.data];
    console.table(data);
    renderData(data);



    };



        
 