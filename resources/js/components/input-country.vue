<template>
    <div>
        <label class="form-label">{{ label }}</label>
        <select
            class="select2 form-select form-select-lg"
            data-allow-clear="true" 
            ref="country_select"
            :value="modelValue"
        >
            <option v-for="country in countries" :value="country.id" :key="country.id">
                {{ country.name }} - ({{ country.symbol }})
            </option>
        </select>
        <div id="defaultFormControlHelp" class="form-text">
            {{ hint }}
        </div>
    </div>
</template>
    
<script setup>
import {ref, onMounted, onUpdated} from 'vue';

const props = defineProps({
    label:String,
    hint:String,
    modelValue:[String, Number],
    placeholder:{
        type:String,
        default:'Country'
    }
})
const emit = defineEmits(['update:modelValue'])

const country_select = ref(null)
const countries = ref(window.app.countries)
let $select = null

onMounted(()=>{
    $select = jQuery(country_select.value)
    $select.select2({
        placeholder: props.placeholder
    })
    $select.on('select2:select', e => emit('update:modelValue', e.target.value))
   
})

onUpdated(()=>{
    $select.val(props.modelValue).trigger('change')
})
</script>
