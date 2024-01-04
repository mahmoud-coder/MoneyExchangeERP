<template>
    <basic-card :title="title" class="col-md-8 offset-md-2">
        <select-coin :coins="coins" @selected="c => selected_coin = c" />

        <!-- coin info -->
        <div v-if="selected_coin" class="m-md-5 m-sm-2 p-md-5 p-sm-1 coin-info" :style="{flexDirection: get_type() == 1 ? 'row' : 'row-reverse'}">
            <img class="icon" :src="`/storage/coins/${selected_coin.symbol}.svg`" />
            <span v-if="useStoredExchangeRate" class="text-body">1 {{ selected_coin.symbol }} = {{ exchange_rate }} EURO</span>
            <input-text v-else
                :label="`Rate ( ${selected_coin.symbol}):`"
                :hint="`what is the ${get_type()==1?'buy':'sell'} rate for ${selected_coin.name}?`"
                v-model="exchange_rate"
            />
            <img class="icon" src="/storage/coins/EURO.svg" />
        </div>

        <div class="m-md-5 m-sm-2 p-md-5 p-sm-1 order-data">
            <input-date 
                class="mt-3"
                label="Date"
                v-model = "date"
                hint="The order date"
            />

            <input-text
                class="mt-3"
                :label="selected_coin ? `Amount (${selected_coin.symbol})` : 'Amount:'"
                :hint="`How much ${selected_coin? selected_coin.symbol:''} we will ${get_type() == 1?'buy' : 'sell'}?`"
                v-model.number="total_coins"
            ></input-text>
            
            <input-text
                class="mt-3"
                label="price"
                :hint="`the total ${type !== 'edit' ? type : ''} order value in Euro`"
                v-model.number="total_order_value"
            ></input-text>
            
            <input-text
                class="mt-3"
                label="fees"
                :hint="`additional fees`"
                v-model.number="fees"
            ></input-text>

            <!-- select customer -->
            <label class="form-label mt-3">Customer:</label>
            <select
                class="select2 form-select form-select-lg"
                data-allow-clear="true" 
                id="select2-customers"
                :value="customer_id"
            >
                <option v-for="c in customers" :value="c.id" :key="c.id">
                    {{ c.name }} - ( {{ c.type }} )
                </option>
            </select>
            <div id="defaultFormControlHelp" class="form-text">select the customer, you may need to register him/her first</div>

            <!-- payment method -->
            <div class="mt-3">
                <label for="select-payment-method" class="form-label">Payment Method</label>
                <select v-model="payment_method_id" id="select-payment-method" class="form-select">
                    <option v-for="m in app.payment_methods" :key="m.id" :value="m.id">{{ m.method }}</option>
                </select>
            </div>
            <div id="defaultFormControlHelp" class="form-text">select the payment methods</div>
        </div>

        <!-- summary and excecute -->
        <div
            v-if="total_coins && selected_coin && total_order_value && customer_id && payment_method_id" 
            class="card bg-body border summary"
        >
            <div class="card-body">
                <h5 class="card-title">Summary</h5>
                <hr/>
                <div class="card-text details">
                    <div> <span class="label">Total:</span> €{{ total_order_value }} </div>
                    <div> <span class="label">Fees: </span> €{{ fees }}</div>
                </div>
                <hr/>
                <fold-cube v-if="saving" class="float-end"/>
                <button v-else class="btn btn-primary float-end" @click="save">
                    <i class="ti ti-checks me-2"></i>
                    {{ type == 'edit' ? 'Edit' : 'Confirm' }}
                </button>
            </div>
        </div>
    </basic-card>
</template>

<script setup>
import {ref, watch, computed, onMounted, nextTick} from 'vue'
import BasicCard from "../../components/basic-card.vue"
import InputText from "../../components/input-text.vue"
import InputDate from "../../components/input-date.vue"
import FoldCube from "../../components/spinners/fold-cube.vue"
import SelectCoin from "../../components/select-coin.vue"

const props = defineProps({
    type:String, // 'sell' , 'buy' , 'edit'
    useStoredExchangeRate: Boolean,
    useStoredFees: Boolean,
})

const app = window.app

const coins = ref(window.app.currencies)
const customers = ref(window.app.customers)
const saving = ref(false)
const errors = ref({})

// payload
const date = ref((new Date()).toISOString().split('T')[0])
const selected_coin = ref(null)
const total_coins = ref()
const total_order_value = ref()
const fees = ref(0)
const customer_id = ref()
const payment_method_id = ref()
const exchange_rate = ref()

const title = computed(()=> props.type == 'edit' ? `Edit MBTR${window.app.transaction.id} - ${window.app.transaction.type == 1 ? 'Purchase':'Sell'} - transaction` : `Make a ${props.type} order` )

const fix2 = amount => parseFloat( (+amount).toFixed(2) )
const fix4 = amount => parseFloat( (+amount).toFixed(4) )

watch(selected_coin, (newV)=>{
    if(!newV) return

    //exchange_rate
    if(props.useStoredExchangeRate){
        exchange_rate.value = fix4( props.type == 'sell' ? selected_coin.value.selling_exchange_rate : selected_coin.value.purchase_exchange_rate)
    }else{
        exchange_rate.value = null
    }

    if(props.useStoredFees){
        if(props.type !== 'edit')
            fees.value = fix2(props.type=='sell' ? newV.selling_fees : newV.purchase_fees)
        else
            fees.value = fix2(window.app.transaction.type == 1 ? newV.purchase_fees : newV.selling_fees)
    }

    if(total_coins.value) total_order_value.value = fix2(exchange_rate.value * total_coins.value)
})

watch(exchange_rate, newV => {
    if(!props.useStoredExchangeRate && total_coins.value){
        total_order_value.value = fix2(newV * total_coins.value)
    }
})

watch(total_coins, (newV)=>{
    total_order_value.value = fix2(exchange_rate.value * newV)
})

watch(customer_id, (newV)=>{
    $select_customers.val(newV).trigger('change')
})


// customer
let $select_customers = null
onMounted(()=>{
    $select_customers = jQuery('#select2-customers')
    $select_customers.select2({placeholder: 'Customer'})
    $select_customers.on('select2:select', e => customer_id.value = e.target.value)
    $select_customers.on('select2:unselect', () => customer_id.value = null )
    
    // edit type
    if(props.type == 'edit'){
        let edited_t = window.app.transaction
        let is_purchase = edited_t.type == 1 // is buy type , else is sell
        let coin_id = is_purchase ?  edited_t.to_currency : edited_t.from_currency

        date.value = edited_t.date
        selected_coin.value = coins.value.find(c => c.id == coin_id)
        total_coins.value = parseFloat(is_purchase ? edited_t.to_amount : edited_t.from_amount)
        nextTick(()=> {
            fees.value = parseFloat(edited_t.fees)
            total_order_value.value = parseFloat(is_purchase ? edited_t.from_amount : edited_t.to_amount)
        })
        customer_id.value = edited_t.customer_id
        payment_method_id.value = edited_t.payment_method_id
    }
})

const get_type = () =>  props.type == 'edit' ? window.app.transaction.type : ( props.type == 'buy' ? 1 : 2)

function save(){
    saving.value = true
    let type = get_type()
    const payload = {
        date: date.value,
        type,
        [type == 2 ? 'from_currency' : 'to_currency' ]: selected_coin.value.id,
        [type == 2 ? 'from_amount' : 'to_amount']: total_coins.value,
        [type == 2 ? 'to_amount' : 'from_amount']: total_order_value.value,
        fees: fees.value,
        customer_id: customer_id.value,
        payment_method_id: payment_method_id.value
    }
    jQuery.ajax({
        url: '/admin/transaction' + (props.type == 'edit' ? `/${window.app.transaction.id}` :''),
        method: props.type == 'edit' ? 'PUT' : 'POST',
        data: payload,
        success(res){
            toastr.success(res.message, 'Success')
            // reset inputs
            if(props.type !== 'edit'){
                selected_coin.value = null
                total_coins.value = null
                total_order_value.value = null
                fees.value = null
                customer_id.value = null
                payment_method_id.value = null
            }
        },
        error(xhr){
            if(xhr.status == 422){
                errors.value = xhr.responseJSON.errors
                toastr.warning("invalide data", "Warning")
            }else{
                toastr.error(xhr.responseJSON.message, "Error")
                console.log(xhr)
            }
        },
        complete(){
            saving.value = false
        },
        headers:{
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
}
</script>

<style scoped lang="scss">
.coin-info{
    border:1px solid orange;
    border-radius: 1rem;
    display:flex;
    justify-content: center;
    align-items: center;
    gap:2rem;
    .icon{
        width:3.5rem;
        height: auto;
    }
}

.order-data{
    border: 1px solid rgb(194, 183, 183);
    border-radius: 1rem;
}

.summary{
    .details{
        display:flex;
        flex-direction: column;
        align-items: flex-end;
    }
    .label{font-weight: bold; font-size: 1.1rem;}
}
</style>