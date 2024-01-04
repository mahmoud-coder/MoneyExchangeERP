<template>
<label class="form-label w-100">
    {{ label }}
    <select :value="modelValue" @change="$emit('update:modelValue', $event.target.value)" class="form-select">
        <option v-for="(value, key) in options" :value="key">{{ value }}</option>
    </select>
    <p v-if="hint" class="form-text">
        {{ hint }}
    </p>
</label>
</template>

<script>
export default {
    props:{
        label: String,
        modelValue: undefined,
        hint: String,
        options: Object
    },
    emits:["update:modelValue"]
}
</script>