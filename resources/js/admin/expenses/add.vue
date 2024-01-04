<template>
     <div class="row">
        <div class="col-md-8 offset-md-2">
            <BasicCard :title="type == 'new' ? 'Add New Expense' : 'Edit Expense'">
            
                <div class="my-3">
                    <InputText v-model="state.title" label="Title:" hint="Enter the expense title"/>
                    <AlertError v-if="errors.title">{{ errors.title[0] }}</AlertError>
                </div>

                <div class="my-3">
                    <label for="periodic-status" class="form-label">Expense Type:</label>
                    <select v-model="state.periodic_status" id="periodic-status" class="form-select">
                        <option value="0">Once</option>
                        <option value="1">Every Month</option>
                    </select>
                </div>
                
                <Transition mode="out-in">
                <div v-if="state.periodic_status == 0" class="my-3">
                    <InputDate v-model="state.date" label="Date:" hint="The expense date" />
                    <AlertError v-if="errors.date">{{ errors.date[0] }}</AlertError>
                </div>
                <div v-else>
                    <div class="my-3">
                        <InputDate v-model="state.from" label="From:" hint="The starting date of the expense, if leaved empty it starts today" />
                        <AlertError v-if="errors.from" >{{ errors.from[0] }}</AlertError>
                    </div>
                    <div class="my-3">
                        <InputDate v-model="state.to" label="To:" hint="The expense stops at that date, leave it empty if the expense hasn't an eding date" />
                        <AlertError v-if="errors.to">{{ errors.to[0] }}</AlertError>
                    </div>
                </div>
                </Transition>

                <div class="my-3">
                    <InputText v-model="state.amount" label="Amount:" hint="Enter the expense amount"/>
                    <AlertError v-if="errors.amount">{{ errors.amount[0] }}</AlertError>
                </div>

                <div class="my-3">
                    <label class="form-label">Description:</label>
                    <textarea v-model="state.desc" cols="30" rows="5" class="form-control"></textarea>
                </div>
                <template #footer>
                    <button @click="add_edit_expense" class="btn btn-primary waves-effect float-end" :disabled="saving">
                        <WaveSpinner v-if="saving" color="white"/>
                        <template v-else>
                            <template v-if="type =='new'">
                                <i class="ti ti-device-floppy"></i> Add
                            </template>
                            <template v-else>
                                <i class="ti ti-pencil"></i> Edit
                            </template>
                        </template>
                    </button>
                </template>
            </BasicCard>
        </div>
    </div>
</template>

<script setup>
import {ref} from 'vue'
import BasicCard from "../../components/basic-card.vue"
import InputDate from "../../components/input-date.vue"
import InputText from "../../components/input-text.vue"
import AlertError from "../../components/alerts/alert-error.vue"
import WaveSpinner from "../../components/spinners/wave-spinner.vue"

const props = defineProps({
    type: String, // 'new' | 'edit'
    expense:Object // the expense to edit or undefined if new
})

const state = ref(
    props.type == 'new' 
    ?{
        periodic_status: 0
    }
    :{
        title: props.expense.title,
        periodic_status: props.expense.periodic_status,
        date: props.expense.date,
        from: props.expense.from,
        to: props.expense.to,
        amount: parseFloat(props.expense.amount),
        desc: props.expense.desc
    }
)
let errors = ref({})
const saving = ref(false)

function add_edit_expense(){
    saving.value = true
    jQuery.ajax(`/admin/accounting/expenses/${props.type == 'edit' ? props.expense.id : ''}`,{
        method: props.type == 'new' ? 'POST' : 'PUT',
        data: state.value,
        success(res){
            if(res.success){
                if(props.type == 'new'){
                    state.value = {
                        periodic_status: 0
                    }
                }
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