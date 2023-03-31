<script lang="ts" setup>
import { watch } from 'vue';
import { ref } from 'vue';

const props = defineProps({
  modelValue :String,
  placeholder :String,
  disabled:Boolean,
})

const emit = defineEmits(["update:modelValue"]);

const value = ref(props.modelValue)

watch(() => value.value, (val) => {
  emit("update:modelValue",val)
})

const clear = () => {
  if(props.disabled) return
  value.value = '';
}
</script>
<template>
  <div class="lazarus-viewlist--input">
    <input v-model="value" :placeholder="placeholder" :disabled="disabled"/>
    <a href="#" class="clearable" @click.prevent="clear">
      <svg viewPort="0 0 12 12" version="1.1" xmlns="http://www.w3.org/2000/svg">
        <line x1="1" y1="11" 
              x2="11" y2="1" 
              stroke="black" 
              stroke-width="2"/>
        <line x1="1" y1="1" 
              x2="11" y2="11" 
              stroke="black" 
              stroke-width="2"/>
    </svg>
    </a>
  </div>  
</template>

<style lang="scss">

.lazarus-viewlist--input {
    display: flex;
    margin-left: auto;
    @media(max-width: 900px) {
      width: 100%;
    }
    position: relative;
    .clearable {
      height: 100%;
      width: 50px;
      display: flex;
      align-items: center;
      justify-content: center;
      right: 0;
      font-size: .875rem;
      color: var(--gray_800);
      position: absolute;
      cursor: pointer;
      transition: .5s;

      svg {
        width: 12px;
        height: 12px;

        line {
          stroke : var(--gray_800);
        }
      }

      &:hover {
        svg line {
          stroke : black;
        }
      }
    }

   input {
    font-size: .875rem;
    color: var(--gray_800);
    border: 1px solid var(--gray_600);
    padding: 8px 16px;
    padding-right: 53px;
    border-radius: 8px;
    min-width: 300px;

    @media(max-width: 900px) {
      width: 100%;
      padding: 16px 16px;
    }
   }

   &.small {
    input {
      min-width: 100px;
      font-size: .75rem;
      padding-right: 16px;
    }
    .clearable {
      display: none;
    }
   }
}
</style>