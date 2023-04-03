<script lang="ts" setup>
import { computed } from 'vue';

const props = defineProps({
  column : {
    type : Object,
    required : true
  },
  canSort : {
    type : Boolean,
    required : true
  },
  sort : String,
  sortType : String
});

const emit = defineEmits(["on-click-sort"])


const isSorting = computed(() => {
  return props.sort === props.column.index
});

const showAsc = computed(() => {
  return isSorting.value && props.sortType == "asc";
});

const handleSort = () =>{
  if(!props.canSort) return;
  emit('on-click-sort',[props.column.index,showAsc.value || !isSorting.value ? 'desc' : 'asc'])
}

</script>

<template>
    <th :style="{width: column.width}">
      <a v-if="column.sortable && props.column.index" class="lazarus-viewlist--hlabel" href="#" :style="{cursor : column.sortable ? 'pointer' : 'default'}" @click.prevent="handleSort">
        {{column.label}}        
        <svg v-if="showAsc" :class="`lazarus-viewlist--arrow-sort asc ${isSorting ? 'op-1' : 'op-03'}`" xmlns="http://www.w3.org/2000/svg " viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
          <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"></path>
        </svg>
        <svg v-else :class="`lazarus-viewlist--arrow-sort desc ${isSorting ? 'op-1' : 'op-03'}`" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
          <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
        </svg>
      </a>
      <span class="lazarus-viewlist--hlabel" v-else>{{column.label}}</span>
    </th>
</template>

<style lang="scss">
.lazarus-viewlist--hlabel {
  display: flex;
  align-items: center;
  text-decoration: none;
  font-size: 0.875rem;
  &a {
    &:hover {
      transition: .5s;
      filter : brightness(130%);
    }
  }

  .lazarus-viewlist--arrow-sort {
    height: 18px;
    margin-left: 8px;
    fill: var(--gray_800);

    &.op-03 {
      opacity: 0.3;
    }

    &.op-1 {
      opacity: 1;
    }
  }
}
</style>