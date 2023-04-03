<script lang="ts" setup>
import HeaderCol from './HeaderCol.vue';
import Paginator from './Paginator.vue';
import { ref, computed, watch } from 'vue';
import { setUrlParam,getUrlParam,checkType,resourceResolver} from '../../utils';
import ComponentProxy from '../ComponentProxy.vue';
import DropdownBtn from '../DropdownBtn.vue';
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
const preventDeepShowColumn = ref(true);
const showBasicFilter = ref(false);
const basicFilter = ref(getUrlParam('_',''));
const searchText = ref('');
const hoverColor = ref('');
const themeColor = ref('');
const noResultText = ref('');
const beforeListSlot = ref([]);
const afterListSlop = ref([]);
const sort = ref(getUrlParam('sort',''));
const sortType = ref(getUrlParam('sort-type',''));
const columns = ref([]);
const filters = ref([]);
const filterTimeout = ref(0);
const showColumns = ref({});

const perPageOptions = ref([]);
const page = ref(Number(getUrlParam('page',1)));
const perPage = ref(10);
const total = ref(0);
const totalText = ref('');
const perPageText = ref('');

const fetchData = (pageValue:Number = 1,perPageValue:any = null) => {
  isLoading.value = true;
  resourceResolver("resolveListData",{
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

resourceResolver("resolveDataTable",{
  resource: props.resource.name,
}).then((result) => {
  if(result.success){
    beforeListSlot.value = result.before_list_slot;
    afterListSlop.value = result.after_list_slot;
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

const makeColumnIndex = (columns,filter) => {
  return columns.map((col,index) => {
      if(filter(col)) return ({
        column : col,
        index : index
      });
    }).filter(x=>x)
}

const showColumn = (index:any):boolean => {
  return showColumns.value[`index_${String(index)}`] === undefined || showColumns.value[`index_${String(index)}`] == true;
}

const hideableColumns = computed(() => {
  preventDeepShowColumn.value = true;
  const result = makeColumnIndex(columns.value,(col) => col.hideable && col.label)
  let oldConfig = localStorage.getItem('LzShowColumns');
  oldConfig = oldConfig ? JSON.parse(oldConfig) : {};
  

  for(let col of result) {
    const index = `index_${col.index}`;
    showColumns.value[index] = oldConfig[index] ?? true;
  }

  setTimeout(() => preventDeepShowColumn.value = false, 100);
  return result
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

const hasHideableColumns = computed(() => {
  return !isLoading.value && hideableColumns.value.length ? true : false;
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

watch(() => showColumns, (config) => {
  if(preventDeepShowColumn.value) return;
  localStorage.setItem('LzShowColumns',JSON.stringify(config.value));
},{deep: true})
</script>

<template>
    <div class="lazarus-viewlist--datatable" v-if="visible" :style="{'--hover-datatable-color' : hoverColor,'--theme-datatable-color' : themeColor}">
      <div class="lazarus-viewlist--filter-row">
        <InputText v-model="basicFilter" :placeholder="searchText" :disabled="isLoading"/>
        <template v-if="hasHideableColumns">
          <DropdownBtn class="list-column-dropdown">
              <template v-slot:btn-content>
                <svg wire:loading.remove.delay="" wire:target="" class="filament-icon-button-icon w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"></path>
                </svg>
              </template>
              <template v-slot:list-content>
                <label v-for="(col, i ) in hideableColumns" :key="i" class="row-item">
                  <input type="checkbox"  v-model="showColumns[`index_${col.index}`]"/>
                  {{col.column.label}}
                </label>
              </template>
          </DropdownBtn>
        </template>
        <template v-if="hasFilter">
          <!-- Filter here ... -->
        </template>
      </div>
      <div :class="`lazarus-viewlist--responsive-table ${isLoading ? 'is-loading' : ''}`">
        <table class="lazarus-viewlist--table">
          <thead>
            <tr>
              <template  v-for="(col,i) in columns">
                <HeaderCol v-if="showColumn(i)" :key="i" :column="col" :canSort="canSort" :sort="sort" :sortType="sortType" @on-click-sort="sortClickHandle"/>
              </template>
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
                  <template v-if="showColumn(j)">
                    <td  v-if="checkType(row[j],'string') || checkType(row[j],'number')" :key="j" v-html="row[j]" />
                    <td v-else-if="row[j]">
                      <ComponentProxy :name="row[j].component" :attributes="row[j].attributes">
                        {{ row[j].text ? row[j].text : '' }}
                      </ComponentProxy>
                    </td>
                  </template>
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

.lazarus-dropdown-btn.hover {
  svg {
    transition: .5s;
    filter: brightness(120%);
  }
  
}
.lazarus-viewlist .lazarus-viewlist--datatable {
    background-color: white;
    border-radius: 8px;
    border : 1px solid #e2e8f0;
    .lazarus-viewlist--filter-row {
      display: flex;
      padding: 8px 16px;
      gap: 25px;

      .list-column-dropdown {

        .row-item {
          display: flex;
          gap: 15px;
          align-items: center;
          height: 45px;
          cursor: pointer;
          width: 100%;
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
          font-size: .875rem;
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