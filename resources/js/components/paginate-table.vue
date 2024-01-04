<template>
    <basic-card :title="title">
    <table class="table table-hover">
        <thead>
            <tr>
                <th v-for="head in hds" :key="head">{{ head }}</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody class="table-border-bottom-0">
            <tr v-if="loading" class="loading">
                <td colspan="6"> <fold-cube  style="margin:40px auto;"/> </td>  
            </tr>
            <template v-else v-for="(d, index) in data" :key="d.id">
                <tr v-if="deleting == index">
                    <td :colspan="hds.length + 1"><wave-spinner  style="margin:5px auto;"/></td>
                </tr>
                <tr v-else>
                    <slot :resource="d"></slot>
                    <td>
                        <div class="dropdown">
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="ti ti-dots-vertical"></i></button>
                            <div class="dropdown-menu">
                                <button v-if="! disableEdit" class="dropdown-item" @click="edit(d.id)"><i class="ti ti-pencil me-1"></i>Edit</button>
                                <button v-if="! disableDelete" class="dropdown-item" @click="destroy(d.id, index)"><i class="ti ti-trash me-1"></i>Delete</button>
                                <slot name="actions" :resource="d" />
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
import {ref, computed} from 'vue'
import BasicCard from "./basic-card.vue"
import foldCube from "./spinners/fold-cube.vue"
import waveSpinner from "./spinners/wave-spinner.vue"
import Pagination from "./pagination.vue"
import {usePaginate} from  "../composables/paginate.js"
import {destroy_resource} from "../utils.js"

const props = defineProps({
    title:String,
    headers: [Array, String], // the header of the table columns, if string then the headers should seperated by comma
    urlSegment:String, // the segement in the url for the resource, ex: users
    disableEdit: Boolean,  // disable the edit action , in actions menu
    disableDelete: Boolean, //disable the delete action , in actions menu
})

const deleting = ref(null) // the index of the resource being deleting
const {loading, data, links, currentPage, lastPage, getPage} = usePaginate('/admin/ajax/' + props.urlSegment)

const hds = computed(()=>{
    if(props.headers instanceof Array) return props.headers
    return props.headers.split(',').map(x => x.trim())
})

function destroy(id, index){
    destroy_resource(
        data.value[index].name ?? 'it' , 
        `/admin/${props.urlSegment}/${id}`,
        ()=>{deleting.value = index},
        ()=>{data.value.splice(index, 1)},
        ()=>{deleting.value = null}
    )
}

function edit(id){
    window.location =`/admin/${props.urlSegment}/${id}/edit`
}
</script>
