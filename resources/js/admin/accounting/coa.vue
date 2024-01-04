<template>
<transition mode="out-in">
    <BasicCard v-if="new_mode || edit_mode" :title=" new_mode ? 'New Account':'Edit'">
        <InputText v-model="account.name_en" class="mt-3" label="English Name" hint="The Account Name In English"/>
        <InputText v-model="account.name_lt" class="mt-3" label="Lithuanian Name" hint="The Account Name In Lithuanian"/>
        <InputText v-model="account.code" class="mt-3" label="Code" hint="The Account code"/>
        <div class="mt-3">
            <div>Type:</div>
            <div class="form-check ms-4" v-for="(v,k) in {1:'Current Asset', 2:'Fixed Asset', 3:'Current Liability', 4:'Long Term Liability', 5:'Equity', 6:'Revenue', 7:'Expense'}">
                <input type="radio" class="form-check-input" :value="k" v-model="account.type" :id="`account-type-${k}`">
                <label class="form-check-label" :for="`account-type-${k}`">{{ v }}</label>
            </div>
        </div>
        <button class="btn btn-primary mt-5" @click="new_edit" :disabled="saving">
            <WaveSpinner v-if="saving" color="white" /> <span v-else>Save</span>
        </button>
        <button class="btn mx-2 btn-label-primary mt-5" @click="new_mode = false; edit_mode = false; account = {type: 1}" :disabled="saving">Cancel</button>
    </BasicCard>
    <BasicCard v-else title="Accounts">
        <button class="btn btn-primary my-3" @click="new_mode = true">New</button>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>English Name</th>
                    <th>Lithuanian Name</th>
                    <th>Code</th>
                    <th>Type</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <template  v-for="(account, index) in financial_accounts" :key="account.id">
                    <tr v-if="deleting == index">
                        <td :colspan="6"><wave-spinner  style="margin:5px auto;"/></td>
                    </tr>
                    <tr v-else>
                        <td>{{ account.id }}</td>
                        <td>{{ en(account.name) }}</td>
                        <td>{{ lt(account.name) }}</td>
                        <td>{{ account.code }}</td>
                        <td>{{ type(account.type) }}</td>
                        <td>
                            <div class="dropdown">
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="ti ti-dots-vertical"></i></button>
                            <div class="dropdown-menu">
                                <button class="dropdown-item" @click="edit(account.id, index)"><i class="ti ti-pencil me-1"></i>Edit</button>
                                <button class="dropdown-item" @click="destroy(account.id, index)"><i class="ti ti-trash me-1"></i>Delete</button>
                            </div>
                            </div>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </BasicCard>
</transition>
</template>

<script setup>
import {ref} from 'vue'
import BasicCard from "../../components/basic-card.vue"
import InputText from "../../components/input-text.vue"
import WaveSpinner from "../../components/spinners/wave-spinner.vue"
import {destroy_resource} from "../../utils.js"

const props = defineProps(['accounts'])

const financial_accounts = ref([...props.accounts])
const new_mode = ref(false)
const edit_mode = ref(false)
const edit_account_id = ref()
const edit_account_index = ref()
const saving = ref(false)
const deleting = ref()
const account = ref({
    type:1
})

const en = x => JSON.parse(x).en
const lt = x => JSON.parse(x).lt
function type(type){
    switch(type & 7){
        case 1: 
            return 'Current Asset'
        case 2:
            return 'Fixed Asset'
        case 3:
            return 'Current Liability'
        case 4:
            return 'Long Term Liability'
        case 5:
            return 'Equity'
        case 6:
            return 'Revenue'
        case 7:
            return 'Expense'
    }
}
function new_edit(){
    saving.value = true
    jQuery.ajax({
        url:'/admin/ajax/account' + (edit_mode.value ? `/${edit_account_id.value}` : ''),
        data: account.value,
        method: new_mode.value ? 'POST' : 'PUT',
        success(res){
            toastr.success(res.message, 'Success')
            if(new_mode.value){
                financial_accounts.value.push(res.new_account)
            }else{
                console.log(res.account)
                financial_accounts.value[edit_account_index.value] = res.account
            }
        },
        error(xhr){
            if(xhr.status == 422){
                toastr.warning("invalide data", "Warning")
            }else{
                toastr.error(xhr.responseJSON.message, "Error")
                console.log(xhr)
            }
        },
        complete(){
            saving.value = false
            account.value = {type: 1}
            new_mode.value = false
            edit_mode.value = false
        },
        headers:{
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
}
function destroy(id, index){
    destroy_resource(
        `Account#${id}`, 
        `/admin/ajax/account/${id}`,
        ()=>{deleting.value = index},
        ()=>{financial_accounts.value.splice(index, 1)},
        ()=>{deleting.value=null}
    )
}

function edit(id, index)
{
    edit_account_id.value = id
    edit_account_index.value = index
    account.value = {
        name_en : en(financial_accounts.value[index].name),
        name_lt : lt(financial_accounts.value[index].name),
        code: financial_accounts.value[index].code,
        type: financial_accounts.value[index].type & 7
    }
    edit_mode.value = true
}
</script>