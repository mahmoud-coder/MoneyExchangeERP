<template>
    <div class="row">
        <div class="col-md-6">
            <basic-card title="New Entity Customer">
                <div>
                    <input-text v-model="state.name" class="my-3" label="Name:" hint="Enter the company name"/>
                    <alert-error v-if="errors.name">{{ errors.name[0] }}</alert-error>
                    
                    <input-text v-model="state.director_name" class="my-3" label="Director Name:"/>
                    <alert-error v-if="errors.director_name">{{ errors.director_name[0] }}</alert-error>

                    <input-text v-model="state.email" class="my-3" label="eMail:" hint="Enter the company email address" placeholder="name@example.com"/>
                    <alert-error v-if="errors.email">{{ errors.email[0] }}</alert-error>

                    <input-text v-model="state.registration_number" class="my-3" label="Registration Number:" hint="Enter the company registration number"/>
                    <alert-error v-if="errors.registration_number">{{ errors.registration_number[0] }}</alert-error>
                    
                    <input-text v-model="state.TIN" class="my-3" label="TIN Number:" hint="Taxpayer Identification Numbers"/>
                    <alert-error v-if="errors.TIN">{{ errors.TIN[0] }}</alert-error>
                    
                    <input-text v-model.number="state.share_capital" class="my-3" label="Share Capital:"/>
                    <alert-error v-if="errors.share_capital">{{ errors.share_capital[0] }}</alert-error>

                    <input-country v-model="state.country_id" class="my-3" label="Country:" hint="Choose the company country"/>
                    <alert-error v-if="errors.country_id">{{ errors.country_id[0] }}</alert-error>

                    <input-text v-model="state.address" class="my-3" label="Address:" hint="What is the customer complete address?"/>
                    <alert-error v-if="errors.address">{{ errors.address[0] }}</alert-error>

                    <!-- activity -->
                    <label class="form-label">Activity:</label>
                    <select class="form-select" v-model="state.activity_id">
                        <option v-for="activity in activities" :value="activity.id">{{ activity.activity }}</option>
                    </select>
                    <alert-error v-if="errors.activity_id">{{ errors.activity_id[0] }}</alert-error>

                    <hr/>
                    <fold-cube v-if="saving" class="float-end"/>
                    <button v-else @click="save" type="button" class="btn btn-primary me-2 float-end">
                        <span class="ti ti-device-floppy me-1"></span>Save
                    </button>
                </div>     
            </basic-card>
        </div>
        <div v-if="customerId" class="col-md-6">
            <ShareHolders :customer-id="customerId" :type="type" />
            <files-phones-comments :customer-id = "customerId" :type="type"/>
        </div>
    </div>
</template>

<script setup>
import {ref, onMounted} from 'vue'
import BasicCard from "../../components/basic-card.vue"
// import InputDate from "../../components/input-date.vue"
import InputText from "../../components/input-text.vue"
import InputCountry from "../../components/input-country.vue"
// import InputSwitch from "../../components/input-switch.vue"
import foldCube from "../../components/spinners/fold-cube.vue"
import alertError from "../../components/alerts/alert-error.vue"
import filesPhonesComments from "./files-phones-comments.vue"
import ShareHolders from "./share-holders.vue"

const props = defineProps({
    type:{
        type:String, // 'new' , 'edit'
        default: 'new'
    }
})

const state = ref({})
const errors = ref({})
const saving = ref(false)
const customerId  = ref(props.type == 'edit' ? window.app.customer.id : null)
const activities = ref(window.app.activities)

onMounted(()=>{
    if(props.type == 'edit'){
        state.value = window.app.customer.customerable
    }
})

function save(){
    saving.value = true
    const payload = {...state.value}
    
    jQuery.ajax('/admin/customers/entity'  + (props.type == 'edit' ? `/${window.app.customer.customerable.id}` :''),{
        method: props.type == 'edit' ? 'PUT' : 'POST',
        data: payload,
        success(res){
            if(props.type == 'new') customerId.value = res.customer_id
            toastr.success(res.message, 'Success')
            if(props.type == 'new') state.value = {}
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