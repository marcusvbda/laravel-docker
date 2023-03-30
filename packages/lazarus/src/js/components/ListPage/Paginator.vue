<script lang="ts" setup>
import { ref, computed, watch } from 'vue';
import { setUrlParam } from '../../utils';

const props = defineProps({
  total : Number,
  perPage : Number,
  page : Number,
  perPageOptions : Array,
  totalText : String,
  perPageText : String,
});

const selectedPerPage = ref(props.perPage);
const selectedPage = ref(props.page);
const emit = defineEmits(["on-page-change","on-per-page-change"])

const computedTotalText = computed(() => {
  return props.totalText.replace('{total}', String(props.total));
})

const computedBtnList = computed(() => {
  const btnList = [];
  const maxPage = Math.ceil(props.total / selectedPerPage.value);
  const currentPage = selectedPage.value;
  const startPage = currentPage - 2;
  const endPage = currentPage + 2;
  if (startPage > 1) {
    btnList.push(1);
    if (startPage > 2) btnList.push('...');
  }
  for (let i = startPage; i <= endPage; i++) {
    if (i > 0 && i <= maxPage) btnList.push(i);
  }
  if (endPage < maxPage) {
    if (endPage < maxPage - 1) btnList.push('...');
    btnList.push(maxPage);
  }
  return btnList;
})

const btnPaginateClick = (value) => {
  if(value == 'prev'){
    selectedPage.value = selectedPage.value - 1;
  }else if(value == 'next'){
    selectedPage.value = selectedPage.value + 1;
  }else if(value == '...'){
    return;
  }else{
    selectedPage.value = value;
  }
}

watch(() => props.page, (val) => {
  selectedPage.value = val
})

watch(() => selectedPage.value, (val) => {
  setUrlParam('page',val);
  emit('on-page-change', val)
})

watch(() => selectedPerPage.value, (val) => {
  setUrlParam('per-page',val);
  emit('on-per-page-change', val)
})

</script>

<template>
   <div class="paginator-section">
    <template v-if="total > 0">
      <span class="per-page-description">
        {{ computedTotalText }}
      </span>
      <div class="per-page-section">
        <select v-model="selectedPerPage">
          <option v-for="option in perPageOptions" :value="option">{{ option }}</option>
        </select>
        {{perPageText}}
      </div>
      <div class="paginator-btns">
        <template v-if="computedBtnList.length > 1" >
          <ol class="paginator-btn-list">
            <li v-if="selectedPage > 1" @click="btnPaginateClick('prev')">
              <span>{{ '<' }}</span>
            </li>
            <li v-for="(btn,i) in computedBtnList" :class="`${selectedPage == btn ? 'active' : ''}`" @click="btnPaginateClick(btn)">
              <span >{{btn}}</span>
            </li>
            <li v-if="selectedPage < computedBtnList[computedBtnList.length - 1]" class="prev-next"  @click="btnPaginateClick('next')">
              <span>{{ '>' }}</span>
            </li>
          </ol>
        </template>
      </div>
    </template>
   </div>
</template>

<style lang="scss" scoped>
.lazarus-viewlist .lazarus-viewlist--datatable .paginator-section {
  display: flex;
  padding: 8px 16px;
  justify-content: space-between;
  align-items: center;

  @media(max-width: 900px) {
    margin: 20px 0;
    flex-direction: column;
  }

  .per-page-description {
    color : var(--gray_700);
    font-size: .875rem;
    @media(max-width: 900px) {
      font-size: 1rem;
      order: 2;
    }
  }

  .per-page-section {
    color : var(--gray_700);
    margin: 0 auto;
    display: flex;
    font-size: .875rem;
    align-items: center;
    @media(max-width: 900px) {
      font-size: 1rem;
      order: 1;
      margin: 20px 0;
    }

    select {
      margin-right: 10px;
      height: 32px;
      padding: 3px 30px;
      border-radius: 8px;
      font-size: .875rem;
      border-color: var(--gray_600);

      @media(max-width: 900px) {
        font-size: 1rem;
      }
    }
  }
  .paginator-btns {
    .paginator-btn-list {
      border: 1px solid var(--gray_600);
      border-radius: 8px;
      display: flex;
      list-style: none;
      li {
        font-family: sans-serif;
        min-width: 32px;
        height: 32px;
        font-size: .875rem;
        @media(max-width: 900px) {
          font-size: 1rem;
          min-width: 50px;
          height: 50px;
        }
        line-height: 1.25rem;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        padding: 5px 10px;
        position: relative;
        color: var(--gray_700);

        &.prev-next {
          color: var(--theme-datatable-color);
          font-weight: bold;
          &::after {
            display: none;
          }
        }

        &::after {
          content: '';
          display: block;
          width: 1px;
          height: 40%;
          background-color: var(--gray_600);
          position: absolute;
          right: 0;
        }

        &.active {
          outline: 3px solid var(--theme-datatable-color);
          color: var(--theme-datatable-color);
          border-radius: 8px;
          span {
            z-index: 1;
            font-weight: bold;
          }
          &::after {
            position: absolute;
            height: 100%;
            width: 100%;
            background: red;
            z-index: 0;
            background: var(--theme-datatable-color);
            opacity: .08;
          }
          &::before {
            display: none;
          }
        }
      }
    }
  }
}
</style>