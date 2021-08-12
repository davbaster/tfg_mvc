<link rel="stylesheet" href="<?php echo constant('URL') ?>/public/css/history.css">
    <?php require_once 'views/dashboard/header.php'; ?>

    <div id="main-container">
    
        <div id="history-container" class="container">
            
            <div id="history-options">
                <h2>Historial de pagos</h2>
                <div id="filters-container">
                    <div class="filter-container">
                        <select id="sdate" class="custom-select">
                            <!-- va php code v11min51 -->
                            <option value="">Ver todas las fechas</option>
                            
                        </select>
                    </div>

                    <div class="filter-container">
                        <select id="scategory" class="custom-select">
                            <option value="">Ver todas las planillas</option>
                            
                        </select>
                    </div>
                </div>   
            </div>
            
            <div id="table-container">
                <table width="100%" cellpadding="0">
                    <thead>
                        <tr>
                        <th data-sort="id">ID</th>
                        <th data-sort="title" width="20%">Trabajador</th>
                        <th data-sort="category">Planilla</th>
                        <th data-sort="date">Fecha</th>
                        <th data-sort="amount">Cantidad</th>
                        <th data-sort="amount">Estado</th>
                        <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="databody">
                        
                    </tbody>
                </table>
            </div>
            
        </div>

    </div>

    <script>
        var data = [];
        var copydata = [];
        const sdate     = document.querySelector('#sdate');
        const scategory = document.querySelector('#scategory');
        const sorts = document.querySelectorAll('th');
        const btnPago = document.querySelector('#pagarItem');

        

        sdate.addEventListener('change', e =>{
            const value = e.target.value;
            if(value === '' || value === null){
                this.copydata = [...this.data];
                checkForFilters(scategory);
                //renderData(this.copydata);
                return;
            }
            filterByDate(value);
        });

        scategory.addEventListener('change', e =>{
            const value = e.target.value;
            if(value === '' || value === null){
                this.copydata = [...this.data];
                checkForFilters(sdate);
                //renderData(this.copydata);
                return;
            }
            filterByCategory(value);
        });

        function checkForFilters(object){
            if(object.value != ''){
                //console.log('hay un filtro de ' + object.id);
                switch(object.id){
                    case 'sdate':
                        filterByDate(object.value);
                    break;  

                    case 'scategory':
                        filterByCategory(object.value);
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

        function sortBy(name){
            this.copydata = [...this.data];
            let res;
            switch(name){
                case 'title':
                    res = this.copydata.sort(compareTitle);
                break;
                    
                case 'category':
                    res = this.copydata.sort(compareCategory);
                    break;

                case 'date':
                    res = this.copydata.sort(compareDate);
                    break;
                        
                case 'amount':
                    res = this.copydata.sort(compareAmount);
                    break;

                    default:
                    res = this.copydata;
            }

            renderData(res);
        }

        function compareTitle(a, b){
            if(a.expense_title.toLowerCase() > b.expense_title.toLowerCase()) return 1;
            if(b.expense_title.toLowerCase() > a.expense_title.toLowerCase()) return -1;
            return 0;
        }
        function compareCategory(a, b){
            if(a.category_name.toLowerCase() > b.category_name.toLowerCase()) return 1;
            if(b.category_name.toLowerCase() > a.category_name.toLowerCase()) return -1;
            return 0;
        }
        function compareAmount(a, b){
            if(a.amount > b.amount) return 1;
            if(b.amount > a.amount) return -1;
            return 0;
        }
        function compareDate(a, b){
            if(a.date > b.date) return 1;
            if(b.date > a.date) return -1;
            return 0;
        }

        function filterByDate(value){
            this.copydata = [...this.data];
            const res = this.copydata.filter(item =>{
                return value == item.date.substr(0, 7);
            });
            this.copydata = [...res];
            renderData(res);
        }

        function filterByCategory(value){
            this.copydata = [...this.data];
            const res = this.copydata.filter(item =>{
                return value == item.name;
            });
            this.copydata = [...res];
            renderData(res);
        }

        async function getData(){
            // data = await fetch('http://localhost:41062/www/pagos/getPagosHistoryJSON', {redirect: 'manual'} ) 
            data = await fetch('http://localhost:41062/www/pagos/getPagosHistoryJSON')
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

                    case "open":

                        databody.innerHTML += `<tr>
                        <td>${item.id_pago}</td>
                        <td>${item.nombre}</td>
                        <td>${item.planilla}</td>
                        <td>${item.fecha_creacion}</td>
                        <td>¢${item.adeudado}</td>
                        <td>${item.estado}</td>
                        <td><a id="pagarItem" href="#" onclick="pagar(${item.id_pago})">Pagar</a></td>
                        </tr>`;

                        break;
                
                    default:
                        databody.innerHTML += `<tr>
                        <td>${item.id_pago}</td>
                        <td>${item.nombre}</td>
                        <td>${item.planilla}</td>
                        <td>${item.fecha_creacion}</td>
                        <td>¢${item.adeudado}</td>
                        <td>${item.estado}</td>
                        <td></td>
                        
                        </tr>`;
                        break;
                }
            });
        }
        
    // Esta funcion es llamada desde el boton pagar.
    //se usa asincrona para que no recargue toda la pagina, solo tabla
    async function pagar(id){

        event.preventDefault();

        data = await fetch(`http://localhost:41062/www/pagos/pagar/${id}`)
        .then(res =>res.json())
        .then(json => json);
        this.copydata = [...this.data];
        console.table(data);
        renderData(data);



    };

        
    </script>