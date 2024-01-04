<template>
    <div class="sk-wave sk-primary">
      <div class="sk-wave-rect" :style="[color ? {backgroundColor:color}:null]"></div>
      <div class="sk-wave-rect" :style="[color ? {backgroundColor:color}:null]"></div>
      <div class="sk-wave-rect" :style="[color ? {backgroundColor:color}:null]"></div>
      <div class="sk-wave-rect" :style="[color ? {backgroundColor:color}:null]"></div>
      <div class="sk-wave-rect" :style="[color ? {backgroundColor:color}:null]"></div>
    </div>
</template>

<script setup>
defineProps(['color'])
</script>