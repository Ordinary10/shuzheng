<template>
  <div class="search-wrapper" >
    <div class="search-input-wrapper">
      <slot></slot>
    </div>
    <template>
      <Breadcrumb v-if="TabPage !== 1">
        <BreadcrumbItem  v-for="(item,index) in crumbList" :key="item.path"
                         :class="{'BreadcrumbItemLast':index === crumbList.length-1}">
          {{item.meta.name}}
        </BreadcrumbItem>
      </Breadcrumb>
    </template>
  </div>
</template>

<script>
import { mapState } from 'vuex'

export default {
  name: 'search',
  computed: {
    ...mapState(['crumbList', 'TabPage'])
  }
}
</script>

<style lang="scss"  scoped>
  .search-wrapper{
    margin-bottom: 15px;
    background:rgba(255,255,255,1);
    box-shadow:0px 3px 18px 0px rgba(0, 0, 0, 0.11);
    border-radius:15px;
    display: flex;
  /*url设置*/
  .ivu-breadcrumb{
    padding: 15px;
    color: inherit;
  .BreadcrumbItemLast{
    /*color: #ccc;*/
  }
  }
  .search-input-wrapper{
    flex: 1;
    display: flex;
    align-items: center;
  }
  }
</style>
