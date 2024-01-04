<template>
    <customer-info :info="info" />
    <basic-card title="All Customers">
        <div :style="{display:'flex', justifyContent:'end', margin:'10px 0'}">
            <button class="btn btn-primary btn-sm" @click="use_search = !use_search">{{ use_search ? 'Hide':'Show' }} Search</button>
        </div>
        <table class="table table-hover">
            <thead>
            <tr>
                <th></th>
                <th>ID</th>
                <th>Type</th>
                <th>Name</th>
                <th>Created By</th>
                <th>Actions</th>
            </tr>
            <tr v-if="use_search">
                <th>Search:</th>
                <th><input type="text" placeholder="search ID" v-model="search_id" class="form-control"></th>
                <th></th>
                <th><input type="text" placeholder="search Name" v-model="search_name" class="form-control"></th>
                <th></th>
                <th><button @click="search" class="btn btn-secondary">Search</button></th>
            </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                <tr v-if="loading" class="loading">
                    <td colspan="6"> <fold-cube  style="margin:40px auto;"/> </td>  
                </tr>
                <template v-else v-for="(c, index) in data" :key="c.id">
                    <tr v-if="deleting == index">
                        <td colspan="6"><wave-spinner  style="margin:5px auto;"/></td>
                    </tr>
                    <tr v-else>
                        <td>
                            <button type="button" @click="show_all_info(c.id, index)"  data-bs-toggle="modal" data-bs-target="#view-customer-info" class="btn rounded-pill btn-icon btn-outline-secondary waves-effect">
                                <i class="ti ti-plus"></i>
                            </button>
                        </td>
                        <td>
                            <a :href="`/admin/customers/${c.id}/details`"> MBCT{{ c.id }}</a>
                        </td>
                        <td>{{ c.type }}</td>
                        <td>{{ c.name }}</td>
                        <td>{{ c.creator }}</td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="ti ti-dots-vertical"></i></button>
                                <div class="dropdown-menu">
                                    <button class="dropdown-item" @click="edit(c.id)"><i class="ti ti-pencil me-1"></i>Edit</button>
                                    <button class="dropdown-item" @click="destroy(c.id, index)"><i class="ti ti-trash me-1"></i>Delete</button>
                                </div>
                            </div>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
        <pagination v-if="!loading" :links="links" :current-page="currentPage" :last-page="lastPage" :paginate-function="getPage"/>
    </basic-card>
</template>

<script setup>
import {ref} from 'vue'
import BasicCard from "../../components/basic-card.vue"
import customerInfo from "./customer-info.vue"
import foldCube from "../../components/spinners/fold-cube.vue"
import waveSpinner from "../../components/spinners/wave-spinner.vue"
import Pagination from "../../components/pagination.vue"
import {usePaginate} from  "../../composables/paginate.js"
import {destroy_resource} from "../../utils.js"

const deleting = ref(null) // the index of the transaction being deleting
const info = ref()
const {loading, data, links, currentPage, lastPage, getPage} = usePaginate('/admin/ajax/customers', ()=>{
    const usp = new URLSearchParams()
    if(use_search.value){
        if(search_id.value) usp.set('search_id', search_id.value)
        if(search_name.value) usp.set('search_name', search_name.value)
    }
    return usp.toString()
})

// SEARCH
const use_search = ref(false)
const search_id = ref()
const search_name = ref()
function search(){
    getPage('/admin/ajax/customers?page=1')
}

function destroy(id, index){
    destroy_resource(
        `MBCT${id}`, 
        '/admin/customers/' + id,
        ()=>{deleting.value = index},
        ()=>{data.value.splice(index, 1)},
        ()=>{deleting.value=null}
    )
}

function edit(id){
    window.location =`/admin/customers/${id}/edit`
}

function show_all_info(id, index){
    info.value = null
    jQuery.ajax('/admin/customers/info/'+id, {
        method:'GET',
        success(res){
            info.value = res
            info.value.type = data.value[index].type
            info.value.name = data.value[index].name
            info.value.email = data.value[index].email
            info.value.creator = data.value[index].creator
        }
    })

}
</script>