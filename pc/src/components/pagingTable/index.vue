<template>
    <div class="paging-table" v-if="configs&&data">
      <Table
        stripe
        :highlight-row="true"
        :columns="configs.columns"
        :data="data"
        @on-sort-change="sortChange"></Table>
      <div class="paging-box">
        <Page
          class="paging"
          :total="total"
          show-elevator
          show-sizer
          show-total
          :page-size-opts="limits"
          :page-size="limit"
          :current.sync = "page"
          @on-change="pageChange"
          @on-page-size-change="pageSizeChange"/>
      </div>
    </div>
</template>

<script>
import { tableRequest } from '@/utils/request'
export default {
  name: 'pagingTable',
  data () {
    return {
      configs: null,
      data: null,
      total: 0,
      params: null,
      sort_field: null,
      sort_type: null,
      limit: 20,
      page: 1,
      limits: [10, 20, 50, 100]
    }
  },
  props: {
    config: {
      type: Object,
      required: true
    },
    searchData: {
      type: Object,
      default: function () {
        return {}
      }
    }
  },
  created () {
    this.configs = this.$props.config
    this.params = this.$props.searchData
    this.getTableData()
  },
  methods: {
    getTableData () {
      /* fun:数据接口；page:页数；limit:返回数据条数 */
      let options = {
        page: this.page,
        limit: this.limit,
        fun: this.configs.fun
      }
      /* sort_field:排序字段；sort_type：排序类型（升序或者降序） */
      let params = this.params
      params.sort_field = this.sort_field
      params.sort_type = this.sort_type
      tableRequest(options, this.params).then(res => {
        this.data = res.data
        this.total = res.count
      }).catch(err => {
        console.log(err)
      })
    },
    /* 页数改变 */
    pageChange (page) {
      this.page = page
      this.getTableData()
    },
    /* 每页条数改变 */
    pageSizeChange (limit) {
      this.limit = limit
      this.page = 1
      this.getTableData()
    },
    /* 表格排序 */
    sortChange (sortData) {
      this.sort_field = sortData.key
      this.sort_type = sortData.order
      this.page = 1
      this.getTableData()
    },
    /* 搜索 */
    search (searchData) {
      this.params = searchData || ''
      /* 点击搜索按钮重置排序条件为空 */
      this.page = 1
      this.sort_field = ''
      this.sort_type = ''
      this.getTableData()
    },
    /* 刷新 */
    refresh (startSearchData) {
      this.params = startSearchData
      /* 点击刷新按钮重置排序条件为空 */
      this.page = 1
      this.sort_field = ''
      this.sort_type = ''
      this.getTableData()
    },
    /* 保留页码刷新 */
    pageRefresh (startSearchData) {
      this.params = startSearchData
      this.sort_field = ''
      this.sort_type = ''
      this.getTableData()
    }
  },
  computed: {
  }
}
</script>

<style scoped lang="scss">
.paging-table{
  height: 100%;
  position: relative;
  .paging-box{
    position: absolute;
    bottom: 0;
    left: 0;
    background-color: white;
    height: 40px;
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    border-top: 1px solid #E8EAEC;
  }
}
</style>
