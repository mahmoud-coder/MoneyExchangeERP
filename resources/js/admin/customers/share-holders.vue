<template>
    <basic-card title="Share Holders" class="mb-4">
        <transition-group>
        <div v-for="(holder, i) in holders" :key="holder.id" class="d-flex justify-content-between border-bottom align-items-center p-2">
            <div>
                <span class="fw-bold">{{ holder.name }}</span> : <span>{{ holder.share }}</span>
            </div>
            <button v-if="deleting_holder_id !== holder.id"  class="btn btn-danger btn-sm" @click = "delete_share_holder(holder.id, i)" >
                <i class="ti ti-trash"></i>
            </button>
            <wave-spinner v-else />
        </div>
        </transition-group>
        <div class="text-decoration-underline mt-3">Add new share holder:</div>
        <input v-model="newHolderName" type="text" placeholder="name" class="form-control mt-1">
        <input v-model="newHolderShare" type="text" placeholder="shares ratio or shares count" class="form-control mt-3">
        <transition>
        <button v-if="newHolderName && newHolderShare" :disabled="saving_share_holder" @click="add_share_holder" class="btn btn-primary float-end mt-3">
            <waveSpinner v-if="saving_share_holder" color="white" />
            <span v-else>Add Share Holder</span>
        </button>
        </transition>
    </basic-card>
</template>

<script setup>
import {onMounted, ref} from 'vue'
import BasicCard from "../../components/basic-card.vue"
import waveSpinner from "../../components/spinners/wave-spinner.vue"

const props = defineProps(['customerId', 'type'])
const holders = ref([])
const newHolderName = ref()
const newHolderShare = ref()
const saving_share_holder = ref(false)
const deleting_holder_id =ref()

onMounted(()=>{
    // edit mode
    if(props.type == 'edit'){
        jQuery.ajax({
            url: '/admin/customers/info/' + props.customerId,
            method:'GET',
            success(res){
                holders.value = res.customerable.share_holders
            }
        })
    }
})

function add_share_holder(){
    saving_share_holder.value = true
    jQuery.ajax('/admin/customers/info/'  + props.customerId ,{
        method: 'POST',
        data:{share_holder_name: newHolderName.value, share_holder_share: newHolderShare.value},
        success(res){
            toastr.success('Share Holder added', 'Success')
            holders.value.push({id:res.id, name:res.name, share: res.share})
            saving_share_holder.value= false
            newHolderName.value=null
            newHolderShare.value=null
        },
        headers:{
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })

}

function delete_share_holder(id, index){
    deleting_holder_id.value = id
    jQuery.ajax( '/admin/customers/info/'  + props.customerId, {
        method:'DELETE',
        data:{shareHolderId:id},
        success(){
            holders.value.splice(index, 1)
            deleting_holder_id.value = null
        },
        headers:{
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
}
</script>