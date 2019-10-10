<template>
<div class="container">
        <!-- Formulario para añadir pacientes -->
        <section class="form">

                <form @submit.prevent="searchDate"> 
                <div class="form-group">
                    <label for="Total" >Fecha y Hora</label>
                    <input type="text" class="form-control" placeholder="yyyy-mm-dd" v-model="fecha">
                </div>
                <button class="btn btn-primary" type="submit">Buscar</button>   
                </form>
        </section>
        <!-- Tabla donde se muestran los datos -->
      
        <section class="data">
            <br>
            <table class="table table-responsive">
                <thead>
                    <tr>
                        <th scope="col">Cod</th>
                        <th scope="col">Fecha</th>
                        <th scope="col">Plato</th>
                        <th scope="col">Monto</th>
                            
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(sale, index) in sales" v-bind:key="index">
                        <td>{{index+1}}</td>
                        <td>{{sale.datetime}}</td>
                        <td>{{sale.food_id}}</td>
                        <td>{{sale.amount}}</td>
                    </tr>
                </tbody>
            </table>
        </section>
        <hr>
        <h5>Ganancia</h5>
        <hr>
                <section class="form">

                <form @submit.prevent="ganancia"> 
                <div class="form-group">
                    <label for="Total" >Indica Fecha</label>
                    <input type="text" class="form-control" placeholder="yyyy-mm-dd" v-model="fecha2">
                </div>
                <button class="btn btn-success" type="submit">Buscar</button>   
                </form>
        </section>
        <hr>
        <p>Usted gano:</p>
        <input type="text" class="form-control" v-model="monto" disabled>
    </div>
</template>

<script>
    export default {
        mounted() {
            this.fechaActual();
        },
        data() {
            return{
                sales: [] ,
                sale:{datetime:'',food_id:'',amount:''},
                fecha:'',
                fecha2:'',
                monto:'',
                modoEditar:false,
            }
        },
        methods:{
            searchDate: function() {
                axios.get(`searchsales/${this.fecha}`)
                .then(response => {
                    this.sales = response.data;
                    console.log(response.data);
                });
            },
            ganancia: function() {
                axios.get(`ganancia/${this.fecha2}`)
                .then(response => {
                    console.log(response.data);
                    this.monto = response.data;
                });
            },
            fechaActual: function() {
                var dateFormat = require('dateformat');
                var now = new Date();
                this.sale.datetime = dateFormat(now, "yyyy-mm-dd h:MM:ss");
            }
        }
    }
</script>