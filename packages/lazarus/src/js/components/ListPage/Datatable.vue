<script lang="ts" setup>
import { resourceResolver } from '../../utils';
import HeaderCol from './HeaderCol.vue';
import Paginator from './Paginator.vue';
import { ref, computed, watch } from 'vue';
import { setUrlParam,getUrlParam} from '../../utils';
import ComponentProxy from '../ComponentProxy.vue';
import InputText from '../InputText.vue';

const props = defineProps({
  resource : {
    type : Object,
    required : true
  }
});

const isLoading = ref(true);
const data = ref([]);
const visible = ref(false);
const showBasicFilter = ref(false);
const basicFilter = ref(getUrlParam('_',''));
const searchText = ref('');
const hoverColor = ref('');
const themeColor = ref('');
const noResultText = ref('');
const sort = ref(getUrlParam('sort',''));
const sortType = ref(getUrlParam('sort-type',''));
const columns = ref([]);
const filters = ref([]);
const filterTimeout = ref(0);

const perPageOptions = ref([]);
const page = ref(Number(getUrlParam('page',1)));
const perPage = ref(10);
const total = ref(0);
const totalText = ref('');
const perPageText = ref('');

const fetchData = (pageValue:Number = 1,perPageValue:any = null) => {
  isLoading.value = true;
  resourceResolver({
    resource: props.resource.name,
    action: 'resolveListData',
    page : pageValue,
    per_page : perPageValue ? perPageValue : perPage.value,
    sort : sort.value,
    sort_type : sortType.value,
    filter : basicFilter.value,
  }).then((result) => {
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
}).then((result) => {
  if(result.success){
    columns.value = result.columns;
    showBasicFilter.value = result.show_basic_filter;
    searchText.value = result.basic_filter_placeholder;
    hoverColor.value = result.hover_color;
    filters.value = result.filters;
    themeColor.value = result.theme_color;
    noResultText.value = result.no_result_text;
    perPage.value = Number(getUrlParam('per-page',result.per_page_default));
    perPageOptions.value = result.per_page_options;
    totalText.value = result.total_list_text;
    perPageText.value = result.per_page_text;
    visible.value = true;
    fetchData(1);
  }
})

const colSpan = computed(() => {
  let count =  columns.value.length;  
  return count;
})

const isShowResult = computed(() => {
  return !isLoading.value && data.value.length;
})

const hasFilter = computed(() => {
  return !isLoading.value && filters.value.length ? true : false;
})

const canSort = computed(() => {
  return !isLoading.value && data.value.length ? true : false;
})

watch(() => basicFilter.value, (val) => {
  if(!isLoading.value) { 
    clearTimeout(filterTimeout.value);
    filterTimeout.value = setTimeout(() => {
      setUrlParam('_', val);
      page.value = 1;
      setUrlParam('page', 1);
      fetchData(1);
    }, 800);
  }
})

const setNewPage = (val) => {
  if(isLoading.value) return
  page.value = val;
  setUrlParam('page', val);
  fetchData(val)
}

const setNewPerPage = (val) => {
  if(isLoading.value) return
  perPage.value = val;
  page.value = 1;
  setUrlParam('page', 1);
  setUrlParam('per-page', val);
  fetchData(1,val);
}

const sortClickHandle = (val) => {
  if(isLoading.value) return
  sort.value = val[0];
  setUrlParam('sort', val[0]);
  sortType.value = val[1];
  setUrlParam('sort-type', val[1]);
  fetchData(page.value,perPage.value);
}

const checkType = (val,type) => {
  return typeof val === type;
}
</script>

<template>
    <div class="lazarus-viewlist--datatable" v-if="visible" :style="{'--hover-datatable-color' : hoverColor,'--theme-datatable-color' : themeColor}">
      <div class="lazarus-viewlist--filter-row">
        <InputText v-model="basicFilter" :placeholder="searchText" :disabled="isLoading"/>
        <template v-if="hasFilter">
          Filter here ...
        </template>
      </div>
      <div :class="`lazarus-viewlist--responsive-table ${isLoading ? 'is-loading' : ''}`">
        <table class="lazarus-viewlist--table">
          <thead>
            <tr>
              <HeaderCol v-for="(col,i) in columns" :key="i" :column="col" :canSort="canSort" :sort="sort" :sortType="sortType" @on-click-sort="sortClickHandle"/>
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
                <template v-for="(col,j) in columns">
                  <td  v-if="checkType(row[j],'string') || checkType(row[j],'number')" :key="j" v-html="row[j]" />
                  <td v-else-if="row[j]">
                    <ComponentProxy :name="row[j].component" :attributes="row[j].attributes">
                      {{ row[j].text ? row[j].text : '' }}
                    </ComponentProxy>
                  </td>
                </template>
              </tr>
            </template>
          </tbody>
        </table>
      </div>
      <Paginator 
        :total="total" 
        :perPage="perPage" 
        :page="page" 
        :perPageOptions="perPageOptions" 
        :totalText="totalText" 
        :perPageText="perPageText"
        @on-page-change="setNewPage"
        @on-per-page-change="setNewPerPage"
      />
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

        &.header-filter {
          background-color: white;
        }
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
          font-size: .8rem;
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