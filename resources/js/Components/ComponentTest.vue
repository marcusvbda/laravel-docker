<script lang="ts" setup>
const props = defineProps({
  color: String
})
</script>
<template>
  <h1 :style="{color}">
    <slot />
  </h1>
</template>