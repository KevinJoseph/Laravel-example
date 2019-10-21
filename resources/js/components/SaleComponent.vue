<template>
<div class="container">
        <!-- Formulario para añadir pacientes -->
        <section class="form">
            <form @submit.prevent="editSale(sale)" v-if="modoEditar">
                <!--h3>Editar Venta</h3-->
                <div class="form-group">
                    <label for="Total">Fecha y Hora</label>
                    <input type="text" class="form-control" placeholder="dd/mm/yyy HH:mm:ss" v-model="sale.datetime">
                </div>
                <div class="form-group">
                    <label for="exampleFormControlSelect1">Platos</label>
                     <select class="form-control" v-model="sale.food_id" v-on:click="addPrice(sale.food_id)" required>
                        <option>Seleccionar un Plato</option>    
                        <option v-for="(food,index) in foods" v-bind:key="index" v-on:click="addPrice(sale.food_id)" :value="food.id" >{{food.name}}</option>
                    </select>  
                </div>
                <div class="form-group">
                    <label for="Total">Monto</label>
                    <input type="text" class="form-control" placeholder="00.00" v-model="sale.amount" disabled>  
                </div>
                <button class="btn btn-success" type="submit">Guardar</button>
                <button class="btn btn-default" type="submit" @click="cancelarEdicion">Cancelar</button>
            </form>
                <form @submit.prevent="addSale" v-else> 
                <div class="form-group">
                    <label for="Total" >Fecha y Hora</label>
                    <input type="text" class="form-control" placeholder="dd/mm/yyy HH:mm:ss" v-model="sale.datetime">
                </div>
                <div class="form-group">
                    <label for="exampleFormControlSelect1">Platos</label>
                    <select class="form-control" v-model="sale.food_id" @change="addPrice(sale.food_id)">
                        <option>Seleccionar un Plato</option>    
                        <option v-for="(food,index) in foods" v-bind:key="index" @change="addPrice(sale.food_id)" :value="food.id" >{{food.name}}</option>
                    </select>  
                </div>
                <div class="form-group">
                    <label for="Total">Monto</label>
                    <input type="text" class="form-control" placeholder="00.00" v-model="sale.amount" disabled>  
                </div>
                <button class="btn btn-primary" type="submit">Agregar</button>   
                </form>
        </section>
        <!-- Tabla donde se muestran los datos -->
      
        <section class="data">
            <br>
            <table class="table table-responsive">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Fecha</th>
                        <th scope="col">Plato</th>
                        <th scope="col">Monto</th>
                        <th scope="col">Acciones</th>         
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(sale, index) in sales" v-bind:key="index">
                        <td>{{index+1}}</td>
                        <td>{{sale.datetime}}</td>
                        <td>{{sale.food_name}}</td>
                        <td>{{sale.amount.toFixed(2)}}</td>
                     
                        <td>
                                <!-- Botón para mostrar el formulario de actualizar -->
                                <button @click="editarFormulario(sale)" class="btn btn-warning"> <i class="fa fa-pencil" aria-hidden="true"></i>  </button>
                                <!-- Botón para borrar -->
                                <button @click="deleteSale(sale,index)" class="btn btn-danger"> <i class="fa fa-trash" aria-hidden="true"></i> </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        {{sumarMonto()}}
         <h4>Total: S/ {{monto.toFixed(2)}} </h4>
        </section>
         
                   
    </div>
</template>

<script>
    export default {
        mounted() {
            this.fechaActual();
            axios.get('foods').then((response) => {
                this.foods = response.data;
            });
            axios.get('indexsales').then((response) => {
                this.sales = response.data;
            });
        },
        data() {
            return{
                sales: [] ,
                sale:{datetime:'',food_id:'',amount:'',food_name:''},
                foods:[],
                food:{id:'',name:'',price:'',category:''},
                modoEditar:false,
                monto:'',
            }
        },
        methods:{
            addSale: function() {
                const params = this.sale; 
                axios.post('sales', params)
                .then((response) => {
                this.sales.push(response.data);
                axios.get('indexsales').then((response) => {
                this.sales = response.data;
                this.fechaActual();
                });
                });    
                //this.sale = {datetime:'',amount:'',food_id:''};     
                //var dateFormat = require('dateformat');
                //var now = new Date();
                //this.sale.datetime = dateFormat(now, "yyyy-mm-dd HH:MM:ss"); 
            },
            editarFormulario(sale){
                this.sale.id = sale.id;
                this.sale.datetime = sale.datetime;
                this.sale.food_id = sale.food_id;
                this.sale.amount = sale.amount;
                this.modoEditar = true;
            },
            editSale: function (sale) {
                const params = {datetime: sale.datetime, food_id: sale.food_id, amount: sale.amount};
                axios.put(`sales/${sale.id}`, params)
                    .then(response => {
                         this.modoEditar = false;
                         const index = this.sales.findIndex(item => item.id === sale.id);
                         this.sales[index] = response.data;
                            axios.get('sales')
                            .then( response => {
                               this.sales = response.data;     
                            });
                    });
            },
            deleteSale: function (sale, index) {
                const confirmation = confirm(`Se eliminara la venta: ${sale.id}`);
                if(confirmation){
                    axios.delete(`sales/${sale.id}`)
                    .then(()=>{
                        this.sales.splice(index,1)
                        var dateFormat = require('dateformat');
                        var now = new Date();
                        this.sale.datetime = dateFormat(now, "yyyy-mm-dd HH:MM:ss");
                    });
                }
            },
            cancelarEdicion: function () {
                this.modoEditar = false;
                this.sale = {food_id:'',amount:''};
                var dateFormat = require('dateformat');
                var now = new Date();
                this.sale.datetime = dateFormat(now, "yyyy-mm-dd HH:MM:ss");
            },
            addPrice: function(id) {
                axios.get(`foods/${id}`).then((response) => {
                   this.food = response.data;
                   this.sale.amount = this.food.price;
                });
            },
            fechaActual: function() {
                var dateFormat = require('dateformat');
                var now = new Date();
                this.sale.datetime = dateFormat(now, "yyyy-mm-dd HH:MM:ss");
            },
            sumarMonto: function(){
                let sum = 0;
                this.monto = this.sales.reduce(function (total, sale) {
                return total + Number(sale.amount);
                }, 0);
            }
        }
    }
</script>