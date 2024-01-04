<template>
    <div>
    <Transition>
    <div v-if="editing">
        <h5>Edit NPD Equations:</h5>
        <div v-for="(e , i) in new_equations" class="border p-2 mx-2 my-3">
            <div class="row mt-1">
                <div class="col-md-2"> Greater Than:</div>
                <div class="col-md-4"><input type="text" v-model="e.range[0]" class="form-control"></div>
            </div>
            <div v-if="e.range.length == 2" class="row mt-1">
                <div class="col-md-2"> UP To: </div>
                <div class="col-md-4"><input type="text" v-model="e.range[1]" class="form-control"></div>
            </div>
            <div class="row mt-1" v-if="e.npd != '-1'">
                <div class="col-md-2"> Equation: </div>
                <div class="col-md-10"><input type="text" v-model="e.npd" class="form-control"></div>
            </div>
            <div class="row mt-1">
                <div class="col-md-2">Explain:</div>
                <div class="col-md-10"> <input type="text" v-model="e.explain" class="form-control"> </div>
            </div>
        </div>
        <WaveSpinner v-if="saving" />
        <template v-else>
            <button class="btn btn-primary me-2" @click="save">Update NPD Equations</button>
            <button class="btn btn-primary" @click="new_equations=deepClone(equations); editing = false">Cancel</button>
        </template>
    </div>
    <div v-else>
        <h5>Current NPD Equations:</h5>
        <div v-for="e in equations" class="border m-2 p-2">
            <span>Greater than: {{ e.range[0] }}</span>
            <span v-if="e.range.length == 2">
                , Up to: {{ e.range[1] }}
            </span>
            <p v-if="e.npd === '-1'">There are no Tax (GPM)</p>
            <p v-else>
                <b>The NPD equation is:</b> <span> {{ e.npd }} </span>
            </p>
        </div>
        <button class="btn btn-primary" @click="editing = true">Edit Equations</button>
    </div>
    </Transition>
</div>
</template>

<script setup>
import {inject, ref} from "vue"
import WaveSpinner from "../../../components/spinners/wave-spinner.vue"

const deepClone = x => JSON.parse( JSON.stringify(x) )

const editing = ref(false)
const saving = ref(false)
var equations = inject("equations")
const new_equations = ref(deepClone(equations.value))  

function save(){
    saving.value = true
    jQuery.ajax({
        method: 'POST',
        url:'/admin/ajax/set-option',
        data: {
            key: 'salary-tax-free-amount',
            value: JSON.stringify( new_equations.value )
        },
        success(res){
            if(res.success){
                toastr.success('The Equations updated', 'Success')
                equations.value = deepClone(new_equations.value)
                editing.value = false
            } 
            else toastr.error(res.message, 'Error')
        },
        error(xhr){
            toastr.error(xhr.responseJSON.message, "Error")
            saving.value = false
        },
        headers:{
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        complete(){
            saving.value = false
        }
    }) 
}
</script>