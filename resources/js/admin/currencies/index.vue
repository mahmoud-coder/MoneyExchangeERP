<template>
<BasicCard title="All Currencies">
    <table class="table table-hover">
        <thead>
            <tr>
                <th></th>
                <th>ID</th>
                <th>Name</th>
                <th>Symbol</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody class="table-border-bottom-0">
            <tr v-if="loading" class="loading">
                    <td colspan="5"> <fold-cube  style="margin:40px auto;"/> </td>  
            </tr>
            <template v-else v-for="(c, index) in data" :key="c.id">
                <tr v-if="deleting == index">
                    <td colspan="5"><wave-spinner  style="margin:5px auto;"/></td>
                </tr>
                <tr v-else>
                    <td>
                        <img :src="`/storage/coins/${c.symbol}.svg`" style="width:50px;">
                    </td>
                    <td>{{ c.id }}</td>
                    <td>{{ c.name }}</td>
                    <td>{{ c.symbol }}</td>
                    <td>
                        <div class="dropdown">
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="ti ti-dots-vertical"></i></button>
                            <div class="dropdown-menu">
                                <button class="dropdown-item" @click="edit(c.id)"><i class="ti ti-pencil me-1"></i>Edit</button>
                                <button class="dropdown-item" @click="destroy(c.id, index, c.name)"><i class="ti ti-trash me-1"></i>Delete</button>
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

const deleting = ref(null) // the index of the currency being deleting
const {loading, data, links, currentPage, lastPage, getPage} = usePaginate('/admin/ajax/currencies')

function destroy(id, index, name){
    destroy_resource(
        name + ' currency', 
        '/admin/currency/' + id,
        ()=>{deleting.value = index},
        ()=>{data.value.splice(index, 1)},
        ()=>{deleting.value=null}
    )
}
function edit(id){
    window.location =`/admin/currency/${id}/edit`
}
</script>