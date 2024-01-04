<template>
<div class="row">
    <div class="col-lg-6 offset-lg-3">
        <BasicCard :title="'Employee - ' + type">
            <InputText class="mt-3" v-model="state.name" label="Name"/>
            <InputText class="mt-3" v-model="state.surname" label="Surname"/>
            <InputText class="mt-3" v-model="state.salary" label="Set Salary"/>
            <InputText class="mt-3" v-model="state.duty" label="Main Duty"/>
            <InputText class="mt-3" v-model="state.pension" label="Pension" hint="example: 3 for 3% pension, 1.5 for 1.5% pension"/>
            <BasicSelector 
                class="mt-3" 
                label="Social Insurance"
                v-model="state.social_insurance" 
                :options="{
                    '1.77': '1.77% insurance',
                    '2.49': '2.49% insurance'
                }" 
                hint="1.77% for Indefinetly employees, 2.49% for a limited time employees"
            />
            <BasicSelector 
                class="mt-3"
                label="Apply Tax-free amount (NPD)?"
                hint = "if the employee apply NPD on onther job, then don't apply here"
                :options="{1: 'Apply', 0:'Don\'t Apply'}"
                v-model="state.apply_tax_free_amount"
            />
            <InputDate :today="type == 'new'" class="mt-3" v-model="state.joined_at" label="Joined at" />
            <InputDate v-if="type == 'edit'" class="mt-3" v-model="state.left_at" label="Left at" />
            <div class="mt-3 float-end">
                <WaveSpinner v-if="loading" />
                <button v-else @click="send" class="text-uppercase btn btn-primary">{{type}}</button>
            </div>
        </BasicCard>
    </div>
</div>
</template>

<script setup>
import {reactive, ref} from 'vue'
import BasicCard from "../../components/basic-card.vue"
import InputText from "../../components/input-text.vue"
import InputDate from "../../components/input-date.vue"
import BasicSelector from "../../components/basic-selector.vue"
import WaveSpinner from "../../components/spinners/wave-spinner.vue"

const props = defineProps({
    type: String, // new | edit
    employee: String // undefined or employee object in json encoded string
})

let default_state = ()=> ({social_insurance: 1.77, apply_tax_free_amount: 1, left_at:null})
let state = reactive( props.type == 'new' ? default_state() : JSON.parse(props.employee))
const loading = ref(false)

function send(){
    loading.value=true
    jQuery.ajax({
        method: props.type == 'new' ? 'POST' : 'PUT',
        url: `/admin/employees${props.type == 'edit' ? '/' + state.id : ''}`,
        data: state,
        success(res){
            toastr.success(res.message, 'Success')
            if(props.type == 'new'){
                state = default_state()
            }
        },
        error(xhr){
            if(xhr.status == 422){
                let message = ""
                for(const err of Object.keys(xhr.responseJSON.errors) ){
                    message += `<p>${xhr.responseJSON.errors[err][0]}</p>`
                }
                toastr.warning(message, "Invalid data:")
            }else{
                toastr.error(xhr.responseJSON.message, "Error")
                console.log(xhr)
            }
        },
        complete(){
            loading.value = false
        },
        headers:{
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
}
</script>