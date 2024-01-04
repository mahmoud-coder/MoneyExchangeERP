<template>
    <!-- CUSTOMER FILES -->
    <basic-card title="Customer Files" class="mb-4">
        <div id="customer-files" class="dropzone"></div>
        <div v-if="type =='edit'">
            <customer-files :files="files" />
        </div>
    </basic-card>

    <!-- CUSTOMER PHONES -->
    <basic-card title="Customer Phones" class="mb-4">
        <transition-group>
        <div v-for="(p, i) in phones" :key="p.id" class="d-flex justify-content-between border-bottom align-items-center p-2">
            <span>{{ p.phone }}</span>
            <button v-if="deleting_phone_id !== p.id"  class="btn btn-danger btn-sm" @click = "delete_phone(p.id, i)" >
                <i class="ti ti-trash"></i>
            </button>
            <wave-spinner v-else />
        </div>
        </transition-group>
        <input v-model.trim="newPhone" type="text" placeholder="Add phone" class="form-control mt-3">
        <transition>
        <button v-if="newPhone" :disabled="saving_phone" @click="add_phone" class="btn btn-primary float-end mt-3">
            <waveSpinner v-if="saving_phone" color="white" />
            <span v-else>Add Phone</span>
        </button>
        </transition>
    </basic-card>

    <!-- COMMENT ON THE CUSTOMER -->
    <basic-card title="Comment">
        <template v-if="type == 'edit'">
            <table v-if="comments.length" class="table table-bordered">
                <thead>
                    <tr>
                        <th>Comment</th>
                        <th>Added by</th>
                        <th style="width:0"></th>
                    </tr>
                </thead>
                <tbody>
                    <transition-group>
                    <tr v-for="(c,i) in comments" :key="c.id">
                        <td>{{ c.comment }}</td>
                        <td>{{ c.user.name }}</td>
                        <td  style="text-align: center; padding:5px">
                            <button v-if="c.id !== deleting_comment_id" class="btn btn-danger btn-sm" @click="delete_comment(c.id, i)">
                                <i class="ti ti-trash"></i>
                            </button>
                            <wave-spinner v-else />
                        </td>
                    </tr>
                    </transition-group>
                </tbody>

            </table>
        </template>
        <textarea v-model.trim="comment" placeholder="Add a comment on the customer" class="form-control mt-3" rows="3"></textarea>
        <transition>
        <button v-if="comment" :disabled="saving_comment" @click="add_comment" class="btn btn-primary float-end mt-3">
            <waveSpinner v-if="saving_comment" color="white" />
            <span v-else>Add Comment</span>
        </button>
        </transition>
    </basic-card>
</template>

<script setup>
import {onMounted, ref} from 'vue'
import BasicCard from "../../components/basic-card.vue"
import waveSpinner from "../../components/spinners/wave-spinner.vue"
import customerFiles from "./customer-files.vue"

const props = defineProps(['customerId', 'type'])
const phones = ref([])
const newPhone = ref()
const saving_phone = ref(false)
const deleting_phone_id=ref()
const comment = ref()
const comments = ref([]) // all the comments for edit mode: {id, comment, user:{name}}
const saving_comment = ref(false)
const deleting_comment_id = ref()
const files=ref()

onMounted(()=>{
    const dz = new Dropzone("#customer-files", {
        url: '/admin/customers/info/' + props.customerId,
        params(){
            return {
                '_token': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        }
    })
    dz.on("success", ()=>{
        toastr.success('The customer files have been uploaded', 'Success')
            // edit mode
        if(props.type == 'edit'){
            jQuery.ajax({
                url: '/admin/customers/info/' + props.customerId,
                method:'GET',
                success(res){
                    files.value = res.files
                }
            })
        }
    })

    // edit mode
    if(props.type == 'edit'){
        jQuery.ajax({
            url: '/admin/customers/info/' + props.customerId,
            method:'GET',
            success(res){
                phones.value = res.phones.map(p => ({id:p.id, phone:p.phone}))
                comments.value = res.comments.map(c => ({id: c.id, comment: c.comment, user:{name:c.user.name}}))
                files.value = res.files
            }
        })
    }
})

function add_phone(){
    saving_phone.value = true
    jQuery.ajax('/admin/customers/info/'  + props.customerId ,{
        method: 'POST',
        data:{phone: newPhone.value},
        success(res){
            toastr.success('Phone added to the customer', 'Success')
            phones.value.push({id:res.id, phone:res.phone})
            saving_phone.value= false
            newPhone.value=null
        },
        headers:{
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })

}
function delete_phone(id, index){
    deleting_phone_id.value = id
    jQuery.ajax( '/admin/customers/info/'  + props.customerId, {
        method:'DELETE',
        data:{phoneId:id},
        success(){
            phones.value.splice(index, 1)
            deleting_phone_id.value = null
        },
        headers:{
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
}
function add_comment(){
    saving_comment.value=true
    jQuery.ajax('/admin/customers/info/'  + props.customerId ,{
        method: 'POST',
        data:{comment: comment.value},
        success(res){
            toastr.success('The comment has been saved', 'Success')
            if(props.type == 'edit'){
                comments.value.push( {id: res.id, comment:comment.value, user:{name:res.userName}})
            }
            comment.value = null
            saving_comment.value= false
        },
        headers:{
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
}

function delete_comment(id, index){
    deleting_comment_id.value = id
    jQuery.ajax( '/admin/customers/info/'  + props.customerId, {
        method:'DELETE',
        data:{commentId:id},
        success(){
            comments.value.splice(index, 1)
            deleting_comment_id.value = null
        },
        headers:{
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
}
</script>

<style>
.dropzone{
    margin:10px 0;
    border:1px dashed #999;
}
</style>