<template>
<div class="container">
        <!-- Formulario para añadir pacientes -->
        <section class="form">
              <form @submit.prevent="editFood(food)" v-if="modoEditar">
                <div class="form-group">
                    <label for="Nombre">Nombre</label>
                    <input type="text" class="form-control mb-2" placeholder="Nombre del plato" v-model="food.name">
                </div>
                <div class="form-group  ">
                    <label for="Precio">Precio</label>
                    <input type="text" class="form-control mb-2" placeholder="00.00" v-model="food.price">
                </div>
                <div class="form-group">
                     <label for="exampleFormControlSelect1">Categoria</label>
                     <select class="form-control" v-model="food.category">
                        <option value="Menu">Menu</option>    
                        <option value="Extra">Extra</option>
                    </select>  
                </div>
                <button class="btn btn-success" type="submit">Guardar</button>
                <button class="btn btn-default" type="submit" 
                    @click="cancelarEdicion">Cancelar</button>
                </form>
                <form @submit.prevent="addFood" v-else>
                <div class="form-group">
                    <label for="Nombre">Nombre</label>
                    <input type="text" class="form-control mb-2" placeholder="Nombre del plato" v-model="food.name">
                </div>
                <div class="form-grou">
                    <label for="Precio">Precio</label>
                    <input type="text" class="form-control mb-2" placeholder="00.00" v-model="food.price">
                </div>
                <div class="form-group">
                     <label for="exampleFormControlSelect1">Categoria</label>
                     <select class="form-control" v-model="food.category">
                        <option value="Menu">Menu</option>    
                        <option value="Extra">Extra</option>
                    </select>  
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
                        <th scope="col">id</th>
                        <th scope="col">index</th>
                        <th scope="col">Nombre</th>  
                        <th scope="col">Precio</th>
                        <th scope="col">Categoria</th>
                        <th scope="col">Acciones</th>         
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(food, index) in foods" v-bind:key="index">
                        <td>{{food.id }}</td>
                        <td>{{index}}</td>
                        <td>{{food.name}}</td>
                        <td>{{food.price}}</td>
                        <td>{{food.category}}</td>
                        <td>
                                <!-- Botón para mostrar el formulario de actualizar -->
                                <button @click="editarFormulario(food)" class="btn btn-warning"> <i class="fa fa-pencil" aria-hidden="true"></i>  </button>
                                <!-- Botón para borrar -->
                                <button @click="deleteFood(food,index)" class="btn btn-danger"> <i class="fa fa-trash" aria-hidden="true"></i>  </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </section>
    </div>
</template>

<script>
    export default {
        mounted() {
               axios.get('foods').then((response) => {
                this.foods = response.data;
                  console.log(this.foods);
            });
        },
        data() {
            return{
                foods: [] ,
                food:{name:'',price:'',category:''},
                modoEditar:false
            }
        },
        methods:{
            addFood: function() {
                const params = this.food; 
                 console.log('params: '+this.params);
                axios.post('foods', params)
                .then((response) => {
                this.foods.push(response.data);
                console.log(this.foods);
                });    
                this.food = {name:'',price:'',category:''};      
            },
            editarFormulario(food){
                this.food.name = food.name;
                this.food.id = food.id;
                this.food.price = food.price;
                this.food.category = food.category;
                this.modoEditar = true;
            },
            editFood: function (food) {
                const params = {name: food.name, price: food.price, category: food.category};
                axios.put(`foods/${food.id}`, params)
                    .then(response => {
                         this.modoEditar = false;
                         const index = this.foods.findIndex(item => item.id === food.id);
                         this.foods[index] = response.data;
                            axios.get('foods')
                            .then( response => {
                               this.foods = response.data;     
                            });
                    });
            },
            deleteFood: function (food, index) {
                const confirmation = confirm(`Se eliminara el plato: ${food.name}`);
                if(confirmation){
                    axios.delete(`foods/${food.id}`)
                    .then(()=>{
                        this.foods.splice(index,1)
                    });
                }
            },
            cancelarEdicion: function () {
                this.modoEditar = false;
                this.food = {name:'',price:''};
            }
        }
    }
</script>