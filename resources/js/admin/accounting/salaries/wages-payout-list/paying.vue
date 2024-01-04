<template>
    <basicCard :title="`Paying the salaries incurred at: ${payout.incurred_at}`">
        <inputDate v-model="paid_at" today hint="The date of paying the wages" label="Paid Date" />
        <table class="my-5 table tb-small">
            <colgroup>
                <col span="1" class="w-px-100 bg-body">
            </colgroup>
            <thead>
                <tr>
                    <th>choose</th>
                    <th>employee</th>
                    <th>net pat</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="d in payout.details">
                    <td v-if="d.wages_paid_id == null">
                        <input class="form-check-input" type="checkbox" v-model="choosed_employees[d.id]">
                    </td>
                    <td v-else>Paid</td>
                    <td>{{ d.employee.name }} {{ d.employee.surname }}</td>
                    <td>{{ d.net_pay }}</td>
                </tr>
            </tbody>
        </table>
        <generalJournalEntry
            class="mt-3"
            for-paying 
            :totals="totals"
        />
        <waveSpinner v-if="saving" class="my-4" />
        <div v-else class="my-4 d-flex justify-content-end">
            <button class="btn btn-primary mx-2" @click="$emit('canceled')">
                <i class="ti ti-circle-x me-2"></i> Cancel
            </button>
            <button class="btn btn-primary mx-2" @click="save">
                <i class="ti ti-device-floppy me-2"></i> Pay the wages, and create the journal entry
            </button>
        </div>
    </basicCard>
</template>

<script setup>
import {ref, watch, reactive} from "vue"
import inputDate from '../../../../components/input-date.vue';
import basicCard from "../../../../components/basic-card.vue";
import generalJournalEntry from "../general-journal-entry.vue";
import waveSpinner from "../../../../components/spinners/wave-spinner.vue";

const props = defineProps({
                    payout: Object // type of wages_payout
                })
const emit = defineEmits(["canceled", "created"])

const paid_at = ref()
const saving = ref(false)
const choosed_employees = ref({})

const totals= reactive({net_pay:0, all_insurance:0, tax:0})

watch(choosed_employees.value, ()=>{
    let choosed_wages_details =  props.payout.details.filter(d => choosed_employees.value[d.id] )
    totals.net_pay = fix2(choosed_wages_details.reduce((a,b)=> a + +b.net_pay ,0))
    totals.all_insurance = fix2(choosed_wages_details.reduce((a,b)=> a + +b.pension + +b.insurance + +b.h_s_insurance ,0))
    totals.tax = fix2(choosed_wages_details.reduce((a,b)=> a + +b.tax ,0))
})

function save(){
    saving.value = true
    jQuery.ajax({
        method: 'POST',
        data: {
            date: paid_at.value,
            choosed_employees: Object.keys( choosed_employees.value ).filter(id => choosed_employees.value[id]),
            ...totals
        },
        url: `/admin/wages-payout/${props.payout.id}/pay`,
        success(res){
            if(res.success){
                toastr.success(res.message, 'Success')
            }else{
                toastr.error(res.message, "Error")
            }
        },
        error(xhr){
            toastr.error(xhr.responseJSON.message, "Error")
        },
        complete(){
            saving.value = false
            emit("created")
        },
        headers:{
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
}
</script>