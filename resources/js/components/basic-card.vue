<template>
<div class="card">
  <div class="card-body">
    <h5 class="card-title my-4 text-primary">{{ title }}</h5>
    <hr>
    <p class="card-text">
        <slot />
    </p>
    <slot name="footer" />
  </div>
</div>
</template>

<script setup>
defineProps(['title'])
</script>