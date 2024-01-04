<template>
<BasicCard title="All Payment Methods">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Method</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody class="table-border-bottom-0">
            <tr v-if="loading" class="loading">
                    <td colspan="4"> <fold-cube  style="margin:40px auto;"/> </td>  
            </tr>
            <template v-else v-for="(pm, index) in data" :key="pm.id">
                <tr v-if="deleting == index">
                    <td colspan="4"><wave-spinner  style="margin:5px auto;"/></td>
                </tr>
                <tr v-else>
                    <td>{{ pm.id }}</td>
                    <td>{{ pm.method }}</td>
                    <td>{{ pm.desc }}</td>
                    <td>
                        <div class="dropdown">
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="ti ti-dots-vertical"></i></button>
                            <div class="dropdown-menu">
                                <button class="dropdown-item" @click="edit(pm.id)"><i class="ti ti-pencil me-1"></i>Edit</button>
                                <button class="dropdown-item" @click="destroy(pm.id, index, pm.method)"><i class="ti ti-trash me-1"></i>Delete</button>
                            </div>
                        </div>
                    </td>
                </tr>
            </template>
        </tbody>
    </table>
    <pagination v-if="!loading" :links="links" :current-page="currentPage" :last-page="lastPage" :paginate-function="getPage"/>
</BasicCard>
</template>

<script setup>
import {ref} from 'vue'
import BasicCard from "../../components/basic-card.vue"
import waveSpinner from "../../components/spinners/wave-spinner.vue"
import foldCube from "../../components/spinners/fold-cube.vue"
import Pagination from "../../components/pagination.vue"
import {usePaginate} from  "../../composables/paginate.js"
import {destroy_resource} from "../../utils.js"

const deleting = ref(null) // the index of the payment methods being deleting
const {loading, data, links, currentPage, lastPage, getPage} = usePaginate('/admin/ajax/payment-methods')

function destroy(id, index, method){
    destroy_resource(
        method + ' payment method', 
        '/admin/payment-methods/' + id,
        ()=>{deleting.value = index},
        ()=>{data.value.splice(index, 1)},
        ()=>{deleting.value=null}
    )
}
function edit(id){
    window.location =`/admin/payment-methods/${id}/edit`
}
</script>