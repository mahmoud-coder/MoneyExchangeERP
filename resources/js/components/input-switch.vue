<template>
<div>
    <label class="form-label">{{ label }}</label>
    <div>
    <label class="switch switch-lg mr-3">
      <input type="checkbox" class="switch-input" v-model="choice"/>
      <span class="switch-toggle-slider">
        <span class="switch-on">
          <i class="ti ti-check"></i>
        </span>
        <span class="switch-off">
          <i class="ti ti-x"></i>
        </span>
      </span>
      <span class="switch-label">{{ choice ? choices[0] : choices[1] }}</span>
    </label>
    <div id="defaultFormControlHelp" class="form-text">
        {{ hint }}
    </div>
</div>
</div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
                    label: String,
                    hint: String,
                    modelValue: [Boolean, Number],
                    choices:{
                        type: Array,
                        default: ['YES', 'NO']  // [true , false]
                    }
                })
const emit = defineEmits('update:modelValue')

const choice = computed({
    get:()=> props.modelValue,
    set: v => emit('update:modelValue', v )
})
</script>