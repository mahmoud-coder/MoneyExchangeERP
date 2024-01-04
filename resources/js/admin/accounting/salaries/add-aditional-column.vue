<template>
    <div class="border mx-auto my-3 p-3 w-50">
        <h5>Add Aditional {{ type }}</h5>
        <InputText v-model="name" :hint="`Enter the new ${type} name`" label="Name"/>
        <InputSwitch
            v-if="type == 'Earning'" 
            class="my-2"
            v-model="counted_in_tax_insurance" 
            hint="Does this will count in tax and insurance calculations?" 
            label="Count in Tax & Insurance"
        />
        <div class="d-flex justify-content-end">
            <button
                @click="$emit('canceled')"
                class="btn btn-primary mx-1"
            >Cancel</button>
            <button
                @click="$emit('added', {name, counted_in_tax_insurance})"
                class="btn btn-primary mx-1"
            >Add</button>
        </div>
    </div>
</template>

<script setup>
import {ref} from "vue"
import  InputText from "../../../components/input-text.vue"
import  InputSwitch from "../../../components/input-switch.vue"

const name = ref()
const counted_in_tax_insurance = ref(true)
const props = defineProps({
    type: String, // 'Earning' | 'Deduction'
})
defineEmits(["added", "canceled"])
</script>