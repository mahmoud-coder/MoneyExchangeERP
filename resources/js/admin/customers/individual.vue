<template>
    <div class="row">
        <div class="col-md-6">
            <basic-card title="New Individual Customer">
                <div>
                    <input-text v-model="state.name" class="my-3" label="Name:" hint="Enter the customer first name"/>
                    <alert-error v-if="errors.name">{{ errors.name[0] }}</alert-error>

                    <input-text v-model="state.surname" class="my-3" label="Surname:" hint="Enter the customer last name"/>
                    <alert-error v-if="errors.surname">{{ errors.surname[0] }}</alert-error>

                    <input-text v-model="state.email" class="my-3" label="eMail:" hint="Enter the customer email address" placeholder="name@example.com"/>
                    <alert-error v-if="errors.email">{{ errors.email[0] }}</alert-error>

                    <input-date v-model="state.birthday" class="my-3" label="Birthday:" hint="Enter the customer birthday"/>
                    <alert-error v-if="errors.birthday">{{ errors.birthday[0] }}</alert-error>
                    
                    <input-country v-model="state.country_id" class="my-3" label="Country:" hint="Choose the customer nationality"/>
                    <alert-error v-if="errors.country_id">{{ errors.country_id[0] }}</alert-error>

                    <input-country v-model="state.residence" class="my-3" label="Residence:" hint="What country do the customer mostly live in?"/>
                    <alert-error v-if="errors.residence">{{ errors.residence[0] }}</alert-error>

                    <input-text v-model="state.id_card" class="my-3" label="ID Card:" hint="The customer national identification card"/>
                    <alert-error v-if="errors.id_card">{{ errors.id_card[0] }}</alert-error>

                    <input-text v-model="state.address" class="my-3" label="Address:" hint="What is the customer complete address?"/>
                    <alert-error v-if="errors.address">{{ errors.address[0] }}</alert-error>

                    <input-switch v-model="state.PEP" class="my-3" label="PEP:" hint="Is the customer a Politically Exposed Person ?" />
                    <alert-error v-if="errors.PEP">{{ errors.PEP[0] }}</alert-error>

                    <hr/>
                    <fold-cube v-if="saving" class="float-end"/>
                    <button v-else @click="save" type="button" class="btn btn-primary me-2 float-end">
                        <span class="ti ti-device-floppy me-1"></span>Save
                    </button>
                </div>     
            </basic-card>
        </div>
        <div v-if="customerId" class="col-md-6">
            <files-phones-comments :customer-id = "customerId" :type="type"/>
        </div>
    </div>
</template>

<script setup>
import {ref, onMounted} from 'vue'
import BasicCard from "../../components/basic-card.vue"
import InputDate from "../../components/input-date.vue"
import InputText from "../../components/input-text.vue"
import InputCountry from "../../components/input-country.vue"
import InputSwitch from "../../components/input-switch.vue"
import foldCube from "../../components/spinners/fold-cube.vue"
import alertError from "../../components/alerts/alert-error.vue"
import filesPhonesComments from "./files-phones-comments.vue"

const props = defineProps({
    type:{
        type:String, // 'new' , 'edit'
        default: 'new'
    }
})

const state = ref({
    PEP:false
})
const errors = ref({})
const saving = ref(false)
const customerId  = ref(props.type == 'edit' ? window.app.customer.id : null)

onMounted(()=>{
    if(props.type == 'edit'){
        state.value = window.app.customer.customerable
    }
})

function save(){
    saving.value = true
    const payload = {...state.value}
    payload.PEP =payload.PEP ? 1 : 0
    
    jQuery.ajax('/admin/customers/individual'  + (props.type == 'edit' ? `/${window.app.customer.customerable.id}` :''),{
        method: props.type == 'edit' ? 'PUT' : 'POST',
        data: payload,
        success(res){
            if(props.type == 'new') customerId.value = res.customer_id
            toastr.success(res.message, 'Success')
            if(props.type == 'new') state.value = {PEP:false}
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