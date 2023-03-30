<script lang="ts" setup>
import { resourceResolver } from '../../utils';
import HeaderCol from './HeaderCol.vue';
import Paginator from './Paginator.vue';
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
const themeColor = ref('');
const noResultText = ref('');
const sort = ref('');
const sortType = ref('');
const columns = ref([]);

const perPageOptions = ref([]);
const page = ref(1);
const perPage = ref(10);
const total = ref(0);
const totalText = ref('');
const perPageText = ref('');

const fetchData = () => {
  resourceResolver({
    resource: props.resource.name,
    action: 'resolveListData',
    page : page.value,
    per_page : perPage.value,
    sort : '',
    sort_type : '',
    filter : '',
  },(result) => {
    if(result.success){
      data.value = result.list;
      sort.value = result.sort;
      sortType.value = result.sort_type;
      total.value = result.total;
      isLoading.value = false;
    }
  })
}

resourceResolver({
  resource: props.resource.name,
  action: 'resolveDataTable'
},(result) => {
  if(result.success){
    columns.value = result.columns;
    searchText.value = result.basic_filter_placeholder;
    hoverColor.value = result.hover_color;
    themeColor.value = result.theme_color;
    noResultText.value = result.no_result_text;
    perPage.value = result.per_page_default;
    perPageOptions.value = result.per_page_options;
    totalText.value = result.total_list_text;
    perPageText.value = result.per_page_text;
    visible.value = true;
    fetchData();
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

</script>

<template>
    <div class="lazarus-viewlist--datatable" v-if="visible" :style="{'--hover-datatable-color' : hoverColor,'--theme-datatable-color' : themeColor}">
      <div class="lazarus-viewlist--filter-row">
        <input class="lazarus-viewlist--filter-input"  :placeholder="searchText" :disabled="isLoading"/>
      </div>
      <div :class="`lazarus-viewlist--responsive-table ${isLoading ? 'is-loading' : ''}`">
        <table class="lazarus-viewlist--table">
          <thead>
            <tr>
              <HeaderCol  v-for="(col,i) in columns" :key="i" :column="col" :canSort="canSort" :sort="sort" :sortType="sortType"/>
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
      <Paginator :total="total" :perPage="perPage" :page="page" :perPageOptions="perPageOptions" :totalText="totalText" :perPageText="perPageText"/>
    </div>  
</template>

<style lang="scss" scoped>
.lazarus-viewlist .lazarus-viewlist--datatable {
    background-color: white;
    border-radius: 8px;
    border : 1px solid #e2e8f0;
    .lazarus-viewlist--filter-row {
      display: flex;
      padding: 8px 16px;
      .lazarus-viewlist--filter-input {
        margin-left: auto;
        border: 1px solid var(--gray_600);
        padding: 8px 16px;
        border-radius: 8px;
        min-width: 300px;

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

      thead tr th {
        background-color: var(--gray_500);
        border-top: 1px solid var(--gray_600);
        border-bottom: 1px solid var(--gray_600);
        color : var(--gray_800);
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
          font-size: .9rem;
          color: var(--gray_900);
          @media(max-width: 900px) {
            font-size: 1rem;
          }

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
              background-color: var(--theme-datatable-color);
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
</style>