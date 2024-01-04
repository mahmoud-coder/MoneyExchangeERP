<template>
<Transition mode="out-in">
<BasicCard v-if="!data" title="Options:">
    <div class="row">
        <InputDate class="col-6 mt-3" v-model="from" label="From:" hint="what is the begining date for turnover"/>
        <InputDate class="col-6 mt-3" v-model="to" label="To:" hint="what is the ending date for turnover"/>
    </div>
    <button class="btn btn-primary mt-3" @click="show">Show</button>
    <div  v-if="loading" class="border-top d-flex justify-content-center mt-4 pt-4">
        <WaveSpinner />
    </div>
</BasicCard>
<BasicCard v-else>
    <button class="btn btn-outline-primary my-4" @click="data = null" alt="Back">
        <i class="ti ti-arrow-narrow-left"></i>
    </button>
    <template  v-if="data.length">
        <p class="my-2 text-center">Turnover table for the period from: <b>{{from}}</b>, to: <b>{{to}}</b></p>
        <table class="table table-active table-hover table-bordered">
            <thead>
                <tr>
                    <th>Currency</th>
                    <th>Total Purchasings</th>
                    <th>Total Sellings</th>
                    <th>Turnover</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="c in data" :key="c.id">
                    <td>{{ c.name }} - {{ c.symbol }}</td>
                    <td>{{ f(c.total_purchased) }} {{ c.symbol }}</td>
                    <td>{{ f(c.total_selling) }} {{ c.symbol }}</td>
                    <td>{{ f(c.total_purchased) < f(c.total_selling) ? f(c.total_purchased) : f(c.total_selling) }} {{ c.symbol }}</td>
                </tr>
            </tbody>
        </table>
    </template>
    <div v-else class="text-center">
        No Transactions For The Selected Period.
    </div>
</BasicCard>
</Transition>
</template>

<script setup>
import {ref} from 'vue'
import BasicCard from "../../components/basic-card.vue"
import WaveSpinner from "../../components/spinners/wave-spinner.vue"
import InputDate from "../../components/input-date.vue"

const loading = ref(false)
const data = ref()
const from = ref( (new Date()).toISOString().split('T')[0] )
const to = ref( (new Date()).toISOString().split('T')[0] )

function show(){
    loading.value = true
    jQuery.ajax({
        method:'GET',
        url:'/admin/ajax/turnover',
        data:{from: from.value, to: to.value},
        success(res){
            data.value = res
        },
        complete(){
            loading.value=false
        }
    })
}
const f = x => parseFloat( (+x).toPrecision(6) )
</script>