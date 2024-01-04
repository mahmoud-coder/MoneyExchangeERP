<template>
<BasicCard title="FIFO analysis">
    <SelectCoin v-if="coins" :coins="coins" @selected="select_coin" />
    <p v-if="selected_coin" class="mt-3 text-center">
        FIFO cost for  {{ selected_coin.name }} ( {{ selected_coin.symbol }})
    </p>
    <WaveSpinner v-if="show_spinner" class="mt-3 mx-auto" />
    <div class="w-100" style="overflow-x: auto;">
        <div v-if="markup" v-html="markup" class="mt-3" />
    </div>
    <div v-if="has_more"  class="pt-3" style="text-align: center">
        <button class="btn btn-primary" @click="load_data(selected_coin)">Next</button>
    </div>
</BasicCard>
</template>

<script setup>
import {reactive, ref, onMounted, computed} from 'vue'
import BasicCard from "../../components/basic-card.vue"
import WaveSpinner from "../../components/spinners/wave-spinner.vue"
import SelectCoin from "../../components/select-coin.vue"


const payload = reactive({
    currency:null,
    from:null,
    to:null,
})
const markup = ref()
const coins = ref()
const selected_coin = ref()
const has_more = ref()
const ending_inventory = ref()
const last_invoice_id = ref()

const show_spinner = computed(()=>  !coins.value)


onMounted(()=>{
    jQuery.get('/admin/ajax/coins', d => coins.value = d)
})
function load_data(coin){
    markup.value = null
    selected_coin.value = coin
    payload.currency = coin.id
    payload.asHTML = true
    payload.begining_inventory = has_more.value ? ending_inventory.value : null
    payload.invoice = has_more.value ? last_invoice_id.value : null
    payload.maximum_invoices = 10
    jQuery.ajax({
        url:'/admin/ajax/fifo',
        method:'POST',
        data: payload,
        success(res){
            markup.value=res.markup
            has_more.value = res.meta.has_more
            ending_inventory.value = res.meta.ending_inventory
            last_invoice_id.value = res.meta.last_invoice_id
        },
        headers:{
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
}
function select_coin(c){
    has_more.value = null
    ending_inventory.value = null
    last_invoice_id.value = null
    load_data(c)
}
</script>