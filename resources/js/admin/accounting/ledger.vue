<template>
    <BasicCard title="Ledger">
        <AccountSelector v-model="selected_account" :accounts="accounts"/>
        <div v-if="selected_account" class="mt-4 pt-3 border-top">
            <a target="__blank" :href="'/admin/accounting/ledger/?as_pdf&language=en&account_id=' + selected_account.id" class="btn btn-outline-danger mt-3 ms-2">English - PDF</a>
            <a target="__blank" :href="'/admin/accounting/ledger/?as_pdf&language=lt&account_id=' + selected_account.id" class="btn btn-outline-danger mt-3 ms-2">Lithuanian - PDF</a>
        </div>
        <div v-if="progress" class="d-flex justify-content-center mt-4">
            <WaveSpinner />
        </div>
    </BasicCard>

    <transition>
    <BasicCard v-if="data" :title="'Ledger - ' + e(selected_account.name)" class="mt-4">
        <div v-if="! data.length" class="m-5 text-center"> There are no entry in the General Journal contains the account: {{ e(selected_account.name) }} </div>
        <table v-else class="table table-sm table-bordered table-hover">
            <thead>
                <tr>
                    <th rowspan="2">#</th>
                    <th rowspan="2">Date</th>
                    <th rowspan="2">Content</th>
                    <th rowspan="2">Debit</th>
                    <th rowspan="2">Credit</th>
                    <th colspan="2">Balance</th>
                </tr>
                <tr>
                    <th>Debit</th>
                    <th>Credit</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(row,i) in data" :key="row.id">
                    <td>{{ i+1 }}</td>
                    <td>{{ row.general_journal.date }}</td>
                    
                    <!-- NOTES -->
                    <td v-if="row.general_journal.notes.type == 'text'">{{ row.general_journal.notes.text }}</td>
                    <td v-else v-html="row.general_journal.notes.markup"></td>
                   
                    <!-- DEBIT OR CREDIT -->
                    <template v-if="row.type == 'Debit'">
                        <td>{{ row.amount }}</td>
                        <td></td>
                    </template>
                    <template v-else>
                        <td></td>
                        <td>{{ row.amount }}</td>
                    </template>
                    
                    <!-- BALANCE -->
                    <td>{{ fix3(row.running_balance_debit) }}</td>
                    <td>{{ fix3(row.running_balance_credit) }}</td>
                </tr>
            </tbody>
        </table>
    </BasicCard>
</transition>
</template>

<script setup>
import {ref, watch} from 'vue'
import BasicCard from "../../components/basic-card.vue"
import AccountSelector from "../../components/account-selector.vue"
import WaveSpinner from "../../components/spinners/wave-spinner.vue"

const props = defineProps(['accounts'])
const data = ref()
const selected_account = ref()
const progress = ref(false)

const e = x => JSON.parse(x).en
const f = x => x==' '? ' ' : parseFloat( (x).toPrecision(5) )
const fix3 = x => x==' '? ' ': parseFloat( x.toFixed(3) )

watch(selected_account, (account)=>{
    progress.value = true
    data.value = null
    jQuery.ajax({
        method: 'GET',
        url: '/admin/ajax/ledger/' + account.id,
        success(res){
            data.value = res
        },
        complete(){
            progress.value = false
        }
    })
})
</script>

<style scoped>
td,th{
    vertical-align: middle;
    text-align: center;
}
</style>