<script lang="ts" setup>
import { ref } from 'vue';
import { resourceResolver } from '../../utils';

const props = defineProps({
  resource : {
    type : Object,
    required : true
  }
});

const visible = ref(false);

resourceResolver({
  resource: props.resource.name,
  action: 'resolveCreateBtn'
}).then((result) => {
  if(result.can_create) {
    visible.value = true;
  }
})

</script>

<template>
  <button v-if="visible" class="lazarus-viewlist--create-btn">
    <slot />
  </button>
</template>

<style lang="scss">
.lazarus-viewlist {  
  .lazarus-viewlist--create-btn {
    margin-left: auto;
    padding: 10px 20px;
    color: white;
    border-radius: 8px;
    font-weight: 500;
    line-height: 1.25rem;
    background-color: var(--primary);
    &:hover:enabled {
      filter: brightness(150%);
      transition: .5s;
    }
    @media(max-width: 900px) {
      order: 0;
      width: 100%;
      padding: 16px 20px;
      line-height: 1.5rem;
      margin-bottom: 30px;
    }
  }

}
</style>