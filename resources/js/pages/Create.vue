<template>
    <div class="row">
        <div class="col-lg-12 mt-3 mb-3">
            <div class="card">
                <h5 class="card-header">Order Form </h5>
                <div class="card-body">
                    <div class="alert alert-danger" v-if="errors.length">
                        <b>Please correct the following error(s):</b>
                        <ul>
                            <li v-for="error in errors">{{ error }}</li>
                        </ul>
                    </div>
                    <div class="alert alert-success" v-if="success">
                        {{ this.success }}
                    </div>
                    <form @submit="checkForm">
                        <div class="form-group row">
                            <label for="pml" class="col-md-4 col-form-label text-md-right">Pizza Markup Language</label>

                            <div class="col-md-6">
                                <textarea name="pml" v-model="pml" id="pml" cols="30" rows="15" class="form-control" value=""></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-4"></div>
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary float-right">Submit</button>
                            </div>
                        </div>
                        <div class="form-group row">
                                <label for="firstname" class="col-md-4 col-form-label text-md-right">Output:</label>

                                <div class="col-md-6">
                                    <div class="col-md-12 h-100" id="display"></div>
                                </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
<script>

    export default {
        data() {

            let maxCustomToppingAreas = 3;
            let maxCustomToppingItemsPerArea = 3;
            return {
                success: '',
                errors: [],
                output: null,
                pml: ``,
                pizzaDetails: {
                    sizes: ['small', 'medium', 'large'],
                    crusts: ['thin', 'thick', 'hand-tossed', 'deep dish'],
                    types: ['Hawaiian', 'Chicken Fajita', 'Pepperoni Feast', 'custom'],
                    toppingAreas: {
                        0: 'Whole',
                        1: 'First-Half',
                        2: 'Second-Half'
                    },
                    maxCustomToppingAreas: maxCustomToppingAreas,
                    maxCustomToppingItemsPerArea: maxCustomToppingItemsPerArea
                },
                errorMessages: {
                    invalidFormat: 'Invalid PML format',
                    noOrder: 'There is no order.',
                    oneOrderPerSubmission: 'Only one order per submission is allowed.',
                    noOrderNumber: 'No order number.',
                    pizzaNumberOrder: 'Incorrect order of pizzas.',
                    noSize: 'Please indicate pizza size.',
                    oneSizePerPizza: 'Only one size per pizza.',
                    invalidSize: 'Pizza size must be small, medium, or large.',
                    noCrust: 'Please indicate pizza crust.',
                    oneCrustPerPizza: 'Only one crust type per pizza.',
                    invalidCrust: 'Pizza crust must be thin, thick, hand-tossed, or deep dish.',
                    noType: 'Please indicate pizza type.',
                    oneTypePerPizza: 'Only one type per pizza.',
                    invalidType: 'Pizza type must be Hawaiian, Chicken Fajita, Pepperoni Feast, or custom.',
                    noToppingsAllowedForPizzaType: 'Selected pizza type cannot have custom toppings.',
                    maxCustomToppingAreas: `Up to ${maxCustomToppingAreas} topping areas allowed.`,
                    maxCustomToppingItemsPerArea: `Up to ${maxCustomToppingItemsPerArea} toppings per area allowed.`,
                    invalidToppingArea: 'Topping area must be 0 (whole pizza), 1 (first-half), or 2 (second-half).'
                }
            }
        },
        methods: {
            checkForm: function (e) {
                e.preventDefault();
                this.errors = [];

                if (!this.pml) {
                    this.errors.push("Pizza Markup Language required.");
                }


                const validation = this.validate(this.pml);
                if(validation == 'valid')
                {
                    this.parseOrder(this.pml);
                }
                else{
                    this.errors.push(validation);
                }
            },
            validate(pml) {
                const xml = this.convert(pml);
                if (xml.getElementsByTagName('parsererror').length) {
                    return this.errorMessages.invalidFormat;
                }

                // check if order has number
                const orderTags = xml.getElementsByTagName('order');
                if (orderTags.length === 0) {
                    return this.errorMessages.noOrder;
                }
                const newOrder = orderTags[0];
                if (!newOrder.hasAttribute('number')
                    || newOrder.getAttribute('number').trim() === '') {
                    return this.errorMessages.noOrderNumber;
                }

                const pizzas = newOrder.getElementsByTagName('pizza');
                for (let i = 0; i < pizzas.length; i++) {
                    const pizza = pizzas[i];

                    // check if pizza numbers are in order and starts at 1.
                    if (parseInt(pizza.getAttribute('number')) !== i + 1) {
                    return this.errorMessages.pizzaNumberOrder;
                    }

                    // check if size is small, medium, or large.
                    const size = pizza.getElementsByTagName('size');
                    if (size.length === 0) {
                    return this.errorMessages.noSize;
                    } else if (size.length > 1) {
                    return this.errorMessages.oneSizePerPizza;
                    }
                    for (let j = 0; j < this.pizzaDetails.sizes.length; j++) {
                    if (this.pizzaDetails.sizes[j].toLowerCase() === size[0].textContent.toLowerCase()) {
                        break;
                    }

                    if (j === this.pizzaDetails.sizes.length - 1) {
                        return this.errorMessages.invalidSize;
                    }
                    }

                    // check if crust is thin, thick, hand-tossed, deep dish.
                    const crust = pizza.getElementsByTagName('crust');
                    if (crust.length === 0) {
                    return this.errorMessages.noCrust;
                    } else if (crust.length > 1) {
                    return this.errorMessages.oneCrustPerPizza;
                    }
                    for (let j = 0; j < this.pizzaDetails.crusts.length; j++) {
                    if (this.pizzaDetails.crusts[j].toLowerCase() === crust[0].textContent.toLowerCase()) {
                        break;
                    }

                    if (j === this.pizzaDetails.crusts.length - 1) {
                        return this.errorMessages.invalidCrust;
                    }
                    }

                    // check if type is Hawaiian, Chicken Fajita, Pepperoni Feast, or custom.
                    const pizzaType = pizza.getElementsByTagName('type');
                    if (pizzaType.length === 0) {
                    return this.errorMessages.noType;
                    } else if (pizzaType.length > 1) {
                    return this.errorMessages.oneTypePerPizza;
                    }
                    for (let j = 0; j < this.pizzaDetails.types.length; j++) {
                    if (this.pizzaDetails.types[j].toLowerCase() === pizzaType[0].textContent.toLowerCase()) {
                        break;
                    }

                    if (j === this.pizzaDetails.types.length - 1) {
                        return this.errorMessages.invalidType;
                    }
                    }

                    // check toppings.
                    const toppingAreas = pizza.getElementsByTagName('toppings');
                    if (pizzaType[0].textContent.toLowerCase() !== 'custom'
                    && toppingAreas.length > 0) {
                    return this.errorMessages.noToppingsAllowedForPizzaType;
                    } else if (toppingAreas.length > this.pizzaDetails.maxCustomToppingAreas) {
                    return this.errorMessages.maxCustomToppingAreas;
                    }

                    for (let j = 0; j < toppingAreas.length; j++) {
                    const toppingArea = toppingAreas[j];

                    // check if topping area is valid
                    if (!(parseInt(toppingArea.getAttribute('area')) in this.pizzaDetails.toppingAreas)) {
                        return this.errorMessages.invalidToppingArea;
                    }

                    const toppings = toppingArea.getElementsByTagName('item');
                    if (toppings.length > this.pizzaDetails.maxCustomToppingItemsPerArea) {
                        return this.errorMessages.maxCustomToppingItemsPerArea;
                    }
                    }
                }

                return 'valid';
            },
            convert(pml) {
                pml = pml.replace(/{/g, '<').replace(/\\/g, '/').replace(/}/g, '>');

                const parser = new DOMParser();
                return parser.parseFromString(pml, 'text/xml');
            },
            xmlToJson(xml) {

                var obj = {};

                if (xml.nodeType == 1) {

                    if (xml.attributes.length > 0) {
                    obj["@attributes"] = {};
                        for (var j = 0; j < xml.attributes.length; j++) {
                            var attribute = xml.attributes.item(j);
                            obj["@attributes"][attribute.nodeName] = attribute.nodeValue;
                        }
                    }
                } else if (xml.nodeType == 3) {
                    obj = xml.nodeValue;
                }

                if (xml.hasChildNodes()) {
                    for(var i = 0; i < xml.childNodes.length; i++) {
                        var item = xml.childNodes.item(i);
                        var nodeName = item.nodeName;
                        if (typeof(obj[nodeName]) == "undefined") {
                            obj[nodeName] = this.xmlToJson(item);
                        } else {
                            if (typeof(obj[nodeName].push) == "undefined") {
                                var old = obj[nodeName];
                                obj[nodeName] = [];
                                obj[nodeName].push(old);
                            }
                            obj[nodeName].push(this.xmlToJson(item));
                        }
                    }
                }
                return obj;
            },
            parseOrder(pml) {
                const xml = this.convert(pml);
                const order = xml.getElementsByTagName('order')[0];
                var pizzaArr = []

                let pizzasParsed = order.getElementsByTagName('pizza');
                if (pizzasParsed.length) {
                    for (let i = 0; i < pizzasParsed.length; i++) {

                        const pizza = pizzasParsed[i];
                        const size = pizza.getElementsByTagName('size')[0].textContent;
                        const crust = pizza.getElementsByTagName('crust')[0].textContent;
                        const pizzaType = pizza.getElementsByTagName('type')[0].textContent;
                        const pizzaNumber = pizza.getAttribute('number');
                        let toppingsData = [];
                        if (pizzaType.toLowerCase() === 'custom') {
                            const toppingAreas = pizza.getElementsByTagName('toppings');

                            if (toppingAreas.length) {
                                for (let j = 0; j < toppingAreas.length; j++) {
                                    const toppingArea = toppingAreas[j];
                                    const toppings = toppingArea.getElementsByTagName('item');
                                    let toppingsRaw = [];
                                    if (toppings.length) {

                                        for (let k = 0; k < toppings.length; k++) {
                                            toppingsRaw.push(toppings[k].textContent);
                                        }
                                    }

                                    toppingsData.push({
                                        area: this.pizzaDetails.toppingAreas[toppingArea.getAttribute('area')],
                                        toppingsRaw : toppingsRaw
                                    });
                                }
                            }
                        }

                        const pizzaContent = {
                            size: size,
                            crust: crust,
                            pizzaType: pizzaType,
                            pizzaNumber: pizzaNumber,
                            toppings: toppingsData
                        }

                        pizzaArr.push(pizzaContent);
                    }
                }

                var orderData = {
                  orderNumber: order.getAttribute('number'),
                  pizzas: pizzaArr
                };

                axios.post(`/order`, orderData)
                    .then(result => {
                        const xml = this.convert(pml);
                        const order = xml.getElementsByTagName('order')[0];
                        let parsedString = `<ul class="order">
                            <li>Order ${order.getAttribute('number')}:`;

                        let pizzas = order.getElementsByTagName('pizza');
                        if (pizzas.length) {
                            parsedString += `<ul class="pizzas">`;
                            for (let i = 0; i < pizzas.length; i++) {
                            const pizza = pizzas[i];
                            const size = pizza.getElementsByTagName('size')[0].textContent;
                            const crust = pizza.getElementsByTagName('crust')[0].textContent;
                            const pizzaType = pizza.getElementsByTagName('type')[0].textContent;
                            parsedString += `<li>Pizza ${pizza.getAttribute('number')} - ${size}, ${crust}, ${pizzaType}`;

                            if (pizzaType.toLowerCase() === 'custom') {
                                const toppingAreas = pizza.getElementsByTagName('toppings');

                                if (toppingAreas.length) {
                                parsedString += `<ul class="topping-areas">`;
                                for (let j = 0; j < toppingAreas.length; j++) {
                                    const toppingArea = toppingAreas[j];
                                    parsedString += `<li>Toppings ${this.pizzaDetails.toppingAreas[toppingArea.getAttribute('area')]}:`;

                                    const toppings = toppingArea.getElementsByTagName('item');
                                    if (toppings.length) {
                                    parsedString += `<ul class="toppings">`;
                                    for (let k = 0; k < toppings.length; k++) {
                                        parsedString += `<li>${toppings[k].textContent}</li>`;
                                    }
                                    parsedString += `</ul>`;
                                    }

                                    parsedString += `</li>`;
                                }
                                parsedString += `</ul>`;
                                }
                            }

                            parsedString += `</li>`;
                            }
                            parsedString += `</ul>`;
                        }

                        parsedString += `</li>
                            </ul>`;

                        const displayElement = document.getElementById('display');
                        displayElement.innerHTML = parsedString;
                        this.success = 'Order Successfully Saved!';
                    })

            },

        },
        mounted() {

        },
    }
</script>
