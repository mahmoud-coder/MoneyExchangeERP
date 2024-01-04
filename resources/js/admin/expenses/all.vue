<template>
    <BasicCard title="All Expenses">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Date</th>
                    <th v-if="controls">Actions</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                <tr v-if="loading" class="loading">
                    <td :colspan="columnsCount"> <wave-spinner  style="margin:40px auto;"/> </td>  
                </tr>
                <template v-else v-for="(expense, index) in data" :key="expense.id">
                    <tr v-if="deleting == index">
                        <td :colspan="columnsCount"><wave-spinner  style="margin:5px auto;"/></td>
                    </tr>
                    <tr v-else>
                        <td>{{ expense.id }}</td>
                        <td>{{ expense.title }}</td>
                        <td>{{ expense.periodic_status == 0 ? 'Once' : 'Every Month' }}</td>
                        <td>{{ parseFloat( expense.amount ) }}</td>
                        <td v-if="expense.periodic_status == 0">{{ expense.date }}</td>
                        <td v-else>
                            <span class="fw-bold">Started From:</span> {{ expense.from }}
                            <template v-if="expense.to">
                                , <span class="fw-bold">Ended On:</span> {{ expense.to }}
                            </template>
                        </td>
                        <td v-if="controls">
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="ti ti-dots-vertical"></i></button>
                                <div class="dropdown-menu">
                                    <button class="dropdown-item" @click="edit(expense.id)"><i class="ti ti-pencil me-1"></i>Edit</button>
                                    <button class="dropdown-item" @click="destroy(expense.id, index)"><i class="ti ti-trash me-1"></i>Delete</button>
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
import {ref, computed} from 'vue'
import BasicCard from "../../components/basic-card.vue"
import waveSpinner from "../../components/spinners/wave-spinner.vue"
import Pagination from "../../components/pagination.vue"
import {usePaginate} from  "../../composables/paginate.js"
import {destroy_resource} from "../../utils.js"

const props = defineProps({
    controls:Boolean
})
const deleting = ref(null)
const {loading, data, links, currentPage, lastPage, getPage} = usePaginate('/admin/ajax/expenses')

const columnsCount = computed(()=> props.controls ? 6 : 5)

function edit(id){
    window.location =`/admin/accounting/expenses/${id}/edit`
}
function destroy(id, index){
    destroy_resource(
        `expense#${id}`, 
        `/admin/accounting/expenses/${id}`,
        ()=>{deleting.value = index},
        ()=>{data.value.splice(index, 1)},
        ()=>{deleting.value=null}
    )
}

</script>