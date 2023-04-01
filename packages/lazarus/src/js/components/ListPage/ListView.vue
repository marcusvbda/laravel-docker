<script lang="ts" setup>
import BtnCreate from './BtnCreate.vue';
import Datatable from './Datatable.vue';
import RenderSlot from '../RenderSlot.vue';
import { ref } from 'vue';
import { resourceResolver } from '../../utils';

const before_list_slot = ref([]);
const after_list_slot = ref([]);

const props = defineProps({
  payload : {
    type : Object,
    required : true
  },
});
const colors = props.payload.colors ?? {};

resourceResolver({
  resource: props.payload.resource.name,
  action: 'resolveBeforeListSlot',
}).then((result) => {
  if(result.success){
    before_list_slot.value = result.slot;
  }
})

resourceResolver({
  resource: props.payload.resource.name,
  action: 'resolveAfterListSlot',
}).then((result) => {
  if(result.success){
    after_list_slot.value = result.slot;
  }
})

</script>

<template>
  <div class="lazarus-viewlist" :style="{...colors}">
    <RenderSlot :slotArray="before_list_slot" class="l-mb-30"/>
    <div class="lazarus-viewlist--responsive-row">
      <h1 class="lazarus-viewlist--title">
        {{payload.appearance.title}}
      </h1>
      <BtnCreate :resource="payload.resource">
        {{payload.appearance.create_btn_text}}
      </BtnCreate>
    </div>
    <Datatable :resource="payload.resource" />
    <RenderSlot :slotArray="after_list_slot" class="l-mt-30" />
  </div>
  
</template>

<style lang="scss" scoped>
.lazarus-viewlist {

  .l-mt-30 {
    margin-top: 30px;
  }

  .l-mb-30 {
    margin-bottom: 30px;
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