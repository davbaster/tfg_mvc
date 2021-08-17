// LOGICA PARA RELLENAR UNA TABLA CON DATOS QUE VIENEN DE UN JSON DEL SERVIDOR
//Hay algoritmos para ordenar por fecha, por planilla, por estado


    var data = [];
    var copydata = [];
    const sdate     = document.querySelector('#sdate');
    const scontratista = document.querySelector('#scontratista');
    const sestado = document.querySelector('#sestado');
    const sorts = document.querySelectorAll('th');
   


    //cuando hay algun cambio en el id=sdate
    //revise que el valor no es nulo
    //copie la data desde el promise
    //
    sdate.addEventListener('change', e =>{
        const value = e.target.value;
        if(value === '' || value === null){
            this.copydata = [...this.data];
            checkForFilters(scontratista);

            return;
        }
        filterByDate(value);
    });

    scontratista.addEventListener('change', e =>{
        const value = e.target.value;
        if(value === '' || value === null){
            this.copydata = [...this.data];
            checkForFilters(sdate);

            return;
        }
        filterByPlanilla(value);
    });

    //
    sestado.addEventListener('change', e =>{
        const value = e.target.value;
        if(value === '' || value === null){
            this.copydata = [...this.data];
            checkForFilters(sestado);

            return;
        }
        filterByEstado(value);
    });

    function checkForFilters(object){
        if(object.value != ''){
            //console.log('hay un filtro de ' + object.id);
            switch(object.id){
                case 'sdate':
                    filterByDate(object.value);
                break;  

                case 'scontratista':
                    filterByPlanilla(object.value);
                break;

                case 'sestado':
                    filterByEstado(object.value);
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
            return value == item.nombre;
        });
        this.copydata = [...res];
        renderData(res);
    }


    //.estado es un key de un array, item.estado esta accediendo a un valor del array.
    function filterByEstado(value){
        this.copydata = [...this.data];
        const res = this.copydata.filter(item =>{
            return value == item.estado;
        });
        this.copydata = [...res];
        renderData(res);
    }

    //Esta funcion al ser asyncrona usa promises
    //solo se puede acceder al array que viene dentro de la promise [...this.data] si la funcion es async, y se llama desde otra parte
    //https://web.dev/promises/ , https://stackoverflow.com/questions/37533929/how-to-return-data-from-promise
    async function getData(){
        
        data = await fetch('http://localhost:41062/www/peticionespago/getPeticionesPagoHistoryJSON')
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

    function renderData(data){
        var databody = document.querySelector('#databody');
        let total = 0;
        databody.innerHTML = '';
        data.forEach(item => { 
            //total += item.amount;
            switch (item.estado) {

                case "pendiente":

                    databody.innerHTML += `<tr>
                    <td>${item.id_planilla}</td>
                    <td>${item.nombre}</td>
                    <td>${item.fecha_creacion}</td>
                    <td>¢${item.monto}</td>
                    <td>${item.estado}</td>
                    <td><a id="pagarItem" href="#" onclick="autorizarPago(${item.id_planilla})">Aprobar</a></td>
                    </tr>`;

                    break;
            
                default:
                    databody.innerHTML += `<tr>
                    <td>${item.id_planilla}</td>
                    <td>${item.nombre}</td>
                    <td>${item.fecha_creacion}</td>
                    <td>¢${item.monto}</td>
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
    async function autorizarPago(id){

    event.preventDefault();

    data = await fetch(`http://localhost:41062/www/peticionespago/autorizarPeticion/${id}`)
    .then(res =>res.json())
    .then(json => json);
    this.copydata = [...this.data];
    console.table(data);
    renderData(data);



    };


    


        
 