<template>

    <div class="row">
        <div class="col-lg-12 mt-3 mb-3">
            <div class="row">
                <div class="col-md-12 mb-2">
                    <form @submit="filterRecord">
                        <div class="form-row">

                            <div class="col-5">
                                <input type="text" class="form-control" name="keyword" v-model="keyword"  placeholder="Keyword">
                            </div>

                            <div class="col-3">
                                <select name="filter" id="filter" v-model="filter" class="form-control">
                                    <option value="">- Filter Type -</option>
                                    <option value="size">Size</option>
                                    <option value="crust">Crust</option>
                                    <option value="type">Type</option>
                                    <option value="total_toppings_used">Total Toppings Used</option>
                                </select>
                            </div>
                            <div class="col-2">
                                <button class="btn btn-primary" type="submit">
                                    Search
                                </button>
                                <button class="btn btn-danger reset" type="button">
                                    Reset
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <table class="table">
                        <thead>
                            <tr>
                                <td>Order Number</td>
                                <td>Pizza Number</td>
                                <td>Size</td>
                                <td>Crust</td>
                                <td>Type</td>
                                <td>Total Toppings Used</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="pizza in pizzas" :key="pizza.id">
                                <td>{{ pizza.order.order_number }}</td>
                                <td>{{ pizza.pizza_number }}</td>
                                <td>{{ pizza.size }}</td>
                                <td>{{ pizza.crust }}</td>
                                <td>{{ pizza.type }}</td>
                                <td>{{ pizza.total_toppings_used }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</template>

<script>

    export default {
        data() {

            return {
                keyword: '',
                filter: '',
                pizzas: []
            }
        },
        methods: {
            filterRecord: function (e) {
                e.preventDefault();
                axios.post('/filter', {
                    keyword: this.keyword,
                    filter: this.filter
                }).then(response => {
                    this.pizzas = response.data.data;
                    console.log(response);
                });
            }

        },
        mounted() {
            axios.get('/api/orders')
                .then(response => {
                    this.pizzas = response.data.data;
                });
        },
    }
</script>
