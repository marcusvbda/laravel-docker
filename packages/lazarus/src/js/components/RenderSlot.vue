<script setup>
import { checkType } from '../utils';
import ComponentProxy from './ComponentProxy.vue';

defineProps({
  slotArray : {
    type : Array,
    default : (() => [])
  },
});
</script>

<template>
    <div class="render-slot" v-if="slotArray.length">
      <template v-for="(row,i) in slotArray">
        <div  v-if="checkType(row,'string') || checkType(row,'number')" :key="i" v-html="row" />
        <ComponentProxy v-else-if="row" :name="row.component" :attributes="row.attributes">
          {{ row.text ? row.text : '' }}
        </ComponentProxy>
      </template>
    </div>
</template>

<style lang="scss">
.render-slot {
  display: flex;
  flex-direction: row;
  gap: 20px;
}
</style>