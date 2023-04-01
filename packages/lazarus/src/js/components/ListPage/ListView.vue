<script lang="ts" setup>
import BtnCreate from './BtnCreate.vue';
import Datatable from './Datatable.vue';
import { checkType } from '../../utils';
import ComponentProxy from '../ComponentProxy.vue';

const props = defineProps({
  payload : {
    type : Object,
    required : true
  },
});
const colors = props.payload.colors ?? {};
</script>

<template>
  <div class="lazarus-viewlist" :style="{...colors}">
    <div class="lazarus-viewlist-before-slot" v-if="payload.slots.before_list_slot.length">
      <template v-for="(slot,i) in payload.slots.before_list_slot">
        <div  v-if="checkType(payload.slots.before_list_slot[i],'string') || checkType(payload.slots.before_list_slot[i],'number')" :key="i" v-html="payload.slots.before_list_slot[i]" />
        <ComponentProxy v-else-if="payload.slots.before_list_slot[i]" :name="payload.slots.before_list_slot[i].component" :attributes="payload.slots.before_list_slot[i].attributes">
          {{ payload.slots.before_list_slot[i].text ? payload.slots.before_list_slot[i].text : '' }}
        </ComponentProxy>
      </template>
    </div>
    <div class="lazarus-viewlist--responsive-row">
      <h1 class="lazarus-viewlist--title">
        {{payload.appearance.title}}
      </h1>
      <BtnCreate :resource="payload.resource">
        {{payload.appearance.create_btn_text}}
      </BtnCreate>
    </div>
    <Datatable :resource="payload.resource" />
  </div>
</template>

<style lang="scss" scoped>
.lazarus-viewlist {
  .lazarus-viewlist-before-slot {
    display: flex;
    flex-direction: row;
    gap: 20px;
  }
  .lazarus-viewlist--responsive-row {
    display: flex;
    flex-direction: row;
    align-items: center;
    margin-bottom: 30px;
    @media(max-width: 900px) {
      flex-direction: column;
      align-items: flex-start;
    }
  } 
}
.lazarus-viewlist--title {
  color: var(--gray_900);
  font-weight: 600;
  font-size: 1.8rem;
  @media(max-width: 900px) {
    order: 1;
    text-align: center;
    width: 100%;
    font-size: 2.5rem;
  }
}
</style>