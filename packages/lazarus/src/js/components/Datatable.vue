<script lang="ts" setup>
import { resourceResolver } from '../utils';
import { ref } from 'vue';

const props = defineProps({
  resource : {
    type : Object,
    required : true
  }
});
const visible = ref(false);
const searchText = ref('');
const hoverColor = ref('');

resourceResolver({
  resource: props.resource.name,
  action: 'resolveDataTable'
},(result) => {
  if(result.success){
    searchText.value = result.basic_filter_placeholder;
    hoverColor.value = result.hover_color;
    visible.value = true;
  }
})

</script>

<template>
    <div class="lazarus-viewlist--datatable" v-if="visible" :style="{'--hover-datatable-color' : hoverColor}">
      <div class="lazarus-viewlist--filter-row">
        <input class="lazarus-viewlist--filter-input"  :placeholder="searchText"/>
      </div>
      <div class="lazarus-viewlist--responsive-table">
        <table class="lazarus-viewlist--table">
          <thead>
            <tr>
              <th>#</th>
              <th>#</th>
              <th>#</th>
              <th>#</th>
              <th>#</th>
              <th>#</th>
              <th>#</th>
              <th>#</th>
              <th>#</th>
              <th>#</th>
              <th>#</th>
              <th>#</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="i in 10" :key="i">
              <td>12asdasdasdasdasdad1</td>
              <td>asdasdasdada</td>
              <td>1asdadasdasdadsdas21</td>
              <td>asdadasdasda</td>
              <td>asdadasdasda</td>
              <td>asdadasdasda</td>
              <td>asdadasdasda</td>
              <td>asdadasdasda</td>
              <td>asdadasdasda</td>
              <td>asdadasdasda</td>
              <td>asdadasdasda</td>
              <td>asdadasdasda</td>
            </tr>
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
      .lazarus-viewlist--table {
        width: 100%;

        thead tr th {
          background-color: var(--gray_500);
          border-top: 1px solid var(--gray_600);
          border-bottom: 1px solid var(--gray_600);
          color : var(--gray_700);
          padding:  0.5rem 1rem;
          text-align: left;
        }
        tbody tr {
          &:hover {
            background-color: var(--hover-datatable-color);
          }
          td {
            padding:  1.2rem 1rem;
            text-align: left;
            border-top: 1px solid var(--gray_600);
            border-bottom: 1px solid var(--gray_600);
          }
        }
      }
      
    }
  }
}
</style>