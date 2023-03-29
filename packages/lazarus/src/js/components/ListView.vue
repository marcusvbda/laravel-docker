<script lang="ts" setup>
import BtnCreate from './BtnCreate.vue';
import Datatable from './Datatable.vue';

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
  .lazarus-viewlist--responsive-row {
    display: flex;
    flex-direction: row;
    align-items: center;
    margin-bottom: 40px;
    @media(max-width: 900px) {
      flex-direction: column;
      align-items: flex-start;
    }
  } 

  .lazarus-viewlist--title {
    color: var(--gray_800);
    font-weight: 600;
    font-size: 1.7rem;
    @media(max-width: 900px) {
      order: 1;
      text-align: center;
      width: 100%;
      font-size: 2.5rem;
    }
  }
  
}
</style>