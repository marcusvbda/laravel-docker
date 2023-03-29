<script lang="ts" setup>
import { resourceResolver } from '../../utils';
import HeaderCol from './HeaderCol.vue';
import { ref, computed } from 'vue';

const props = defineProps({
  resource : {
    type : Object,
    required : true
  }
});
const isLoading = ref(true);
const data = ref([]);
const visible = ref(false);
const searchText = ref('');
const hoverColor = ref('');
const loadingColor = ref('');
const noResultText = ref('');
const columns = ref([]);

resourceResolver({
  resource: props.resource.name,
  action: 'resolveDataTable'
},(result) => {
  if(result.success){
    columns.value = result.columns;
    searchText.value = result.basic_filter_placeholder;
    hoverColor.value = result.hover_color;
    loadingColor.value = result.loading_color;
    noResultText.value = result.no_result_text;
    visible.value = true;
  }
})

const colSpan = computed(() => {
  let count =  columns.value.length;  
  return count;
})

const isShowResult = computed(() => {
  return !isLoading.value && data.value.length;
})

const canSort = computed(() => {
  return !isLoading.value && data.value.length ? true : false;
})

resourceResolver({
  resource: props.resource.name,
  action: 'resolveListData',
  page : 1,
  per_page : 10,
  sort : '',
  sort_type : '',
  filter : '',
},(result) => {
  if(result.success){
    data.value = result.list;
    isLoading.value = false;
  }
})
</script>

<template>
    <div class="lazarus-viewlist--datatable" v-if="visible" :style="{'--hover-datatable-color' : hoverColor,'--loading-datatable-color' : loadingColor}">
      <div class="lazarus-viewlist--filter-row">
        <input class="lazarus-viewlist--filter-input"  :placeholder="searchText" :disabled="isLoading"/>
      </div>
      <div :class="`lazarus-viewlist--responsive-table ${isLoading ? 'is-loading' : ''}`">
        <table class="lazarus-viewlist--table">
          <thead>
            <tr>
              <HeaderCol  v-for="(col,i) in columns" :key="i" :column="col" :canSort="canSort"/>
            </tr>
          </thead>
          <tbody>
            <tr v-if="!isShowResult" >
              <td v-if="isLoading" :colspan="colSpan" class="td-loading" />
              <td v-else-if="!data.length" :colspan="colSpan" class="td-nothing"> 
                <span>{{ noResultText }}</span>
              </td>
            </tr>
            <template v-else>
              <tr v-for="(row,i) in data" :key="i" class="showing-result">
                <td v-for="(col,j) in columns" :key="j" v-html="row[j]" />
              </tr>
            </template>
          </tbody>
        </table>
      </div>
      conteudo bottom 
    </div>  
</template>

<style lang="scss" scoped>


.lazarus-viewlist {
    .lazarus-viewlist--datatable {
      background-color: white;
      border-radius: 8px;
      border : 1px solid #e2e8f0;
      .lazarus-viewlist--filter-row {
        display: flex;
        padding: 8px;
        .lazarus-viewlist--filter-input {
          margin-left: auto;
          border: 1px solid var(--gray_600);
          padding: 8px 16px;
          border-radius: 8px;
          min-width: 300px;

          &:focus,&:active,&:hover,&:visited{
            border-color:var(--gray_700);
          }

          @media(max-width: 900px) {
            width: 100%;
            padding: 16px 16px;
          }
        }
      }
    .lazarus-viewlist--responsive-table {
      width: 100%;
      overflow-x: auto;

      &.is-loading {
        overflow-x: hidden;
      }
      .lazarus-viewlist--table {
        width: 100%;
        margin-bottom: 20px;

        thead tr th {
          background-color: var(--gray_500);
          border-top: 1px solid var(--gray_600);
          border-bottom: 1px solid var(--gray_600);
          color : var(--gray_700);
          padding:  0.5rem 1rem;
          text-align: left;
          white-space: nowrap;
        }
        tbody tr {
          &.showing-result:hover {
            background-color: var(--hover-datatable-color);
          }
          td {
            padding:  1.2rem 1rem;
            text-align: left;
            border-top: 1px solid var(--gray_600);
            border-bottom: 1px solid var(--gray_600);
            white-space: nowrap;

            &.td-nothing {
              span {
                display: flex;
                align-self: center;
                justify-content: center;
                padding: 50px 0;
                color : var(--gray_700);
              }
            }

            &.td-loading {
              padding: 7px 20px ;

              @keyframes loading-animation {
                0%   {
                  transform: rotateY(0deg);
                }
                33%   {
                  transform: rotateY(110deg);
                }
                99%   {
                  transform: rotateY(0deg);
                }
              }

              &::after {
                content: "";
                display: block;
                background-color: var(--loading-datatable-color);
                animation: loading-animation 2.3s infinite;
                height: 5px;
                width: 100%;
              }
            }
          }
        }
      }
      
    }
  }
}
</style>