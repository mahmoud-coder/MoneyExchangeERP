<template>
    <div>
        <div v-if="label">{{ label }}</div>
        <div v-for="(value, key) in list" class="mt-2 ms-3 form-check">
            <label class="form-check-label">
                <input type="radio" class="form-check-input" :value="key" :checked="modelValue == key" @change="$emit('update:modelValue', $event.target.value)">
                {{ value }}
            </label>
        </div>
    </div>
</template>

<script>
export default {
    props:{
        modelValue: undefined,
        label: String,
        list: Object
    },
    emits:["update:modelValue"]
}
</script>