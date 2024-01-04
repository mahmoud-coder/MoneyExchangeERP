<template>
<basic-card title="Transactions:">
    <div :style="{display:'flex', justifyContent:'end', margin:'10px 0'}">
        <button class="btn btn-primary btn-sm" @click="use_search = !use_search">{{ use_search ? 'Hide':'Show' }} Search</button>
    </div>
  <table class="table table-hover">
    <thead>
      <tr>
        <th v-if="showMiniSummary"></th>
        <th>ID</th>
        <th>Date</th>
        <th v-if="!forCustomer">Customer</th>
        <th>Type</th>
        <th>Coins</th>
        <th>Price</th>
        <th>Actions</th>
      </tr>
      <tr v-if="use_search">
        <th v-if="showMiniSummary">Search:</th>
        <th><input type="text" placeholder="search ID" v-model="search_id" class="form-control" style="max-width: 100px;"></th>
        <th><input-date v-model="search_date" style="display: flex; max-width: 120px;" placeholder="Search date"/></th>
        <th v-if="!forCustomer">
            <select
                class="select2 form-select form-select-lg"
                data-allow-clear="true" 
                id="select2-customers"
                :value="search_customer_id"
            >
                <option v-for="c in customers" :value="c.id" :key="c.id">
                    {{ c.name }}
                </option>
            </select>
        </th>
        <th></th>
        <th>
            <select v-model="search_coin_id" class="form-control">
                <option value="all">ALL</option>
                <option v-for="c in coins" :value="c.id" :key="c.id">{{ c.symbol }}</option>
            </select>
        </th>
        <th></th>
        <th>
            <button @click="search" class="btn btn-secondary">Search</button>
        </th>
      </tr>
    </thead>
    <tbody class="table-border-bottom-0">
        <tr v-if="loading" class="loading">
            <td :colspan="columnsCount"> <fold-cube  style="margin:40px auto;"/> </td>  
        </tr>
        <template v-else v-for="(t, index) in data" :key="t.id">
            <tr v-if="deleting == index">
                <td :colspan="columnsCount"><wave-spinner  style="margin:5px auto;"/></td>
            </tr>
            <tr v-else>
                <td v-if="showMiniSummary">
                    <button type="button" @click="expanded = (expanded == t)? null : t" class="btn rounded-pill btn-icon btn-outline-secondary waves-effect">
                        <i class="ti" :class="expanded == t?'ti-minus':'ti-plus'"></i>
                    </button>
                </td>
                <td>
                    <a :href="`/admin/transaction/${t.id}`"> MBTR{{ t.id }}</a>
                </td>
                <td>{{ t.date }}</td>
                <td v-if="!forCustomer">{{ t.customer.name }}</td>
                <td>{{ t.type }}</td>
                <td>{{ parseFloat(t.coins.amount) }} {{ t.coins.symbol }}</td>
                <td> € {{ parseFloat(t.price) }}</td>
                <td>
                <div class="dropdown">
                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="ti ti-dots-vertical"></i></button>
                    <div class="dropdown-menu">
                        <button class="dropdown-item" @click="edit(t.id)"><i class="ti ti-pencil me-1"></i>Edit</button>
                        <button class="dropdown-item" @click="destroy(t.id, index)"><i class="ti ti-trash me-1"></i>Delete</button>
                    </div>
                </div>
                </td>
            </tr>
            <transition name="wait">
            <tr v-show="expanded == t" class="aditional-data">
                <td colspan="8">
                    <transition>
                    <div v-if="expanded == t" style="overflow: hidden;">
                        <p>
                            <span class="label">Created By:</span> {{ t.user.name }}
                        </p>
                        <p>
                            <span class="label">Payment Method:</span> {{ t.payment_method }}
                        </p>
                        <p>
                            <span  class="label">Fees:</span> {{ parseFloat(t.fees) }}
                        </p>
                        <p v-if="t.cost">
                            <span  class="label">Cost:</span> {{ parseFloat(t.cost.amount) }}
                            <div class="hint">This transaction  was commited on {{ t.date }}, where the {{ t.coins.symbol }} sell rate was €{{ parseFloat(t.cost.exchange_rate) }}. </div>
                        </p>
                    </div>
                    </transition>
                </td>
            </tr>
            </transition>
        </template>
    </tbody>
  </table>
  <pagination v-if="!loading" :links="links" :current-page="currentPage" :last-page="lastPage" :paginate-function="getPage"/>
</basic-card>
</template>

<script setup>
import {ref, watch, nextTick, computed} from 'vue'
import BasicCard from "../../components/basic-card.vue"
import foldCube from "../../components/spinners/fold-cube.vue"
import waveSpinner from "../../components/spinners/wave-spinner.vue"
import inputDate from "../../components/input-date.vue"
import Pagination from "../../components/pagination.vue"
import {usePaginate} from  "../../composables/paginate.js"
import {destroy_resource} from "../../utils.js"

const props = defineProps({
    type:{
        type:String,
        default:'all'  // all, sell, buy
    },
    showMiniSummary: Boolean,
    forCustomer:[String, Number], // a customer id, if set then show only transactions for this customer, if not defined then show a column for customers
})


const expanded = ref()
const deleting = ref(null) // the index of the transaction being deleting


// SEARCH
const use_search = ref(false)
const customers = ref(props.forCustomer ? null : window.app.customers)
const coins = ref(window.app.currencies)
const search_id = ref()
const search_date = ref()
const search_customer_id = ref()
const search_coin_id = ref('all')

const columnsCount = computed(
    () => props.showMiniSummary 
        ? props.forCustomer ? 7 : 8
        : props.forCustomer ? 6 : 7
)

const {loading, data, links, currentPage, lastPage, getPage} = usePaginate('/admin/ajax/transactions', ()=>{
    const usp = new URLSearchParams({type: props.type})
    if(use_search.value){
        if( search_id.value) usp.set('search_id', search_id.value)
        if( search_date.value) usp.set('search_date', search_date.value)
        if( search_customer_id.value) usp.set('search_customer_id', search_customer_id.value)
        if( search_coin_id.value !== 'all') usp.set('search_coin_id', search_coin_id.value)
    }
    if(props.forCustomer){
        usp.set('search_customer_id', props.forCustomer)
    }
    return usp.toString()
})

function search(){
    getPage('/admin/ajax/transactions?page=1')
}

// customer
let $select_customers = null
watch(use_search, (newV)=>{
    if(!newV) return
    if(props.forCustomer) return
    nextTick(()=>{
        $select_customers = jQuery('#select2-customers')
        $select_customers.select2({placeholder: 'Customer'})
        $select_customers.on('select2:select', e => search_customer_id.value = e.target.value)
        $select_customers.on('select2:unselect', () => search_customer_id.value = null )
    })
})
watch(search_customer_id, (newV)=>{
    $select_customers.val(newV).trigger('change')
})

function destroy(id, index){
    destroy_resource(
        ` MBTR${id}`, 
        '/admin/transaction/' + id,
        ()=>{deleting.value = index},
        ()=>{data.value.splice(index, 1)},
        ()=>{deleting.value = null}
    )
}

function edit(id){
    window.location =`/admin/transaction/${id}/edit`
}

</script>

<style scoped lang="scss">
.aditional-data{
    padding:50px;
    background-color: #eee;
}
.label{
    font-weight: bold;
}
.hint{
    font-style: italic;
}

.v-enter-from, .v-leave-to{
max-height: 0;
}

.v-enter-to, .v-leave-from{
    max-height: 200px;
}
.v-enter-active, .v-leave-active {
    transition: max-height 200ms;
}
 .wait-enter-active, .wait-leave-active{
    animation-duration: 200ms;
 }
</style>