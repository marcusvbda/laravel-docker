<script setup>
import { ref } from 'vue';
import "../../scss/theme.scss";

const visible = ref(false);
</script>
<template>
  <div :class="`lazarus-dropdown-btn ${visible ? 'hover' : ''}`" @mouseenter="visible = true" @mouseleave="visible = true">
    <div class="lazarus-dropdown-button" >
      <slot name="btn-content" />
    </div>
    <div v-if="visible" class="lazarus-dropdown-btn--dropdown lazarus-shadow" >
      <slot name="list-content" />
    </div>
  </div>
</template>
<style lang="scss">
.lazarus-dropdown-btn {
  position: relative;

  .lazarus-dropdown-button {
    cursor: pointer;
    height: 100%;
    padding: 5px 10px;
    display: flex;
    align-items: center;
  }

  .lazarus-dropdown-btn--dropdown {
    background-color: white;
    display: flex;
    flex-direction: column;
    padding: 1rem;
    border:  1px solid var(--gray_600);
    position: absolute;
    right: 0;
    min-width: 200px;
    border-radius: 8px;
    min-width: 300px;
  }
}
</style>