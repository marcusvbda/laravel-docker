<script>
export default {
  props: {
    name: {
      type: String,
      required: true
    },
    attributes: {
      type: Object,
      default: () => {}
    }
  }
}
</script>
<template>
  <component :is="name" v-bind="attributes">
    <slot />
  </component>
</template>