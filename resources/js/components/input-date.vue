<template>
    <div>
        <label class="form-label">{{ label }}</label>
        <input
            type="text" 
            class="form-control" 
            :placeholder="placeholder" 
            ref="date" 
            :value="date_value"
            @input="date_value = $event.target.value; $emit('update:modelValue',$event.target.value)"
        >
        <div id="defaultFormControlHelp" class="form-text">
            {{ hint }}
        </div>
    </div>
</template>
    
<script setup>
import { onMounted , ref} from 'vue';


const props = defineProps({
    label:String,
    hint:String,
    modelValue:String,
    today:Boolean, // if true then the init date is today
    placeholder:{
        type:String,
        default:"YYYY-MM-DD"
    }
})
const emit = defineEmits(['update:modelValue'])

const date = ref(null)
const date_value = ref(props.today ? (new Date()).toISOString().split('T')[0] : props.modelValue)

onMounted(()=>{
    date.value.flatpickr()
    if(props.today) emit('update:modelValue', date_value.value)
})
</script>
