<template>
<div>
    <label class="form-label">{{ label }}</label>
    <input v-model="value" type="text" class="form-control" :placeholder="placeholder">
    <div id="defaultFormControlHelp" class="form-text">
        {{ hint }}
    </div>
</div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps(['label','hint', 'placeholder','modelValue'])
const emit = defineEmits(['update:modelValue'])

const value = computed({
    get: ()=> props.modelValue,
    set: v => emit('update:modelValue', v)
})
</script>