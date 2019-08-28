<template>
    <div>
      <search>
        <div class="search-box">
          <Input class="search-input" v-model="searchData.plate_no" size="large" placeholder="请输入车牌" />
          <Select v-model="searchData.status" class="search-input" size="large" placeholder="请选择在库状态">
            <Option value="">全部（在库状态）</Option>
            <Option value="in_dealing">在库待处理</Option>
            <Option value="in_wait">在库待销售</Option>
          </Select>
          <div class="search-submit">
<!--            <Tooltip content="更多搜索条件" placement="bottom-start">-->
<!--              <Button class="search-btn " size="large" icon="ios-options-outline" type="primary" @click.native="isShow=!isShow"></Button>-->
<!--            </Tooltip>-->
            <Button class="search-btn " size="large" icon="md-search" type="primary" @click.native="search"></Button>
            <Button class="refresh-btn search-btn" size="large" icon="md-refresh" type="info" @click.native="refresh"></Button>
          </div>
          <div class="redundant-btn" v-if="redundantList && redundantList.length>0">
            <Dropdown>
              <Button type="primary" size="large">
                更多操作
                <Icon type="ios-arrow-down"></Icon>
              </Button>
              <DropdownMenu slot="list">
                <DropdownItem v-if="item.isShow === true" v-for="(item,index) in redundantList" :key="item.type" @click.native="redundant(index)">{{item.label}}</DropdownItem>
              </DropdownMenu>
            </Dropdown>
          </div>
        </div>
      </search>
      <div class="content-block">
        <paging-table ref="pagingTable" :config="config" :searchData="searchData"></paging-table>
      </div>
    </div>
</template>
<script type="text/jsx">
export default {
  data () {
    return {
      isShow: false,
      iconType: 'md-arrow-dropdown',
      config: {
        fun: 'Carstore/carStoreList',
        columns: [
          {key: 'name', title: '仓库'},
          {
            key: 'plate_no',
            title: '车牌',
            width: 250,
            render: (h, params) => {
              return <div>
                <license-plate row={params.row} key={'plate_no' + params.row.id}></license-plate>
              </div>
            }
          },
          {key: 'brand', title: '车型'},
          {
            key: 'status',
            title: '在库状态',
            render: (h, params) => {
              let d = params.row
              return <span>{d.status === 'in_dealing' ? '在库待处理' : '在库待销售'}</span>
            }
          },
          {
            key: 'caozuo',
            title: '操作',
            width: 240,
            align: 'center',
            render: (h, params) => {
              return <div class="table-btn-box">
                <i-button class="table-btn" type="info" size="small" nativeOnClick={this.tableBtnClick.bind(this, params.row, 'seeIllegal')}>违章记录</i-button>
                <i-button class="table-btn" type="primary" size="small" nativeOnClick={this.tableBtnClick.bind(this, params.row, 'seeLease')}>租赁记录</i-button>
              </div>
            }
          }
        ]
      },
      searchData: {
        plate_no: '',
        status: ''
      },
      startSearchData: {
        plate_no: '',
        status: ''
      },
      redundantList: []
    }
  },
  mounted () {

  },
  methods: {
    search () {
      this.$refs.pagingTable.search(this.searchData)
    },
    refresh () {
      let obj = {}
      Object.keys(this.startSearchData).forEach(key => {
        obj[key] = this.startSearchData[key]
      })
      this.searchData = obj
      this.$refs.pagingTable.refresh(this.searchData)
    },
    /* 保留page刷新table */
    pageRefresh () {
      let obj = {}
      Object.keys(this.startSearchData).forEach(key => {
        obj[key] = this.startSearchData[key]
      })
      this.searchData = obj
      this.$refs.pagingTable.pageRefresh(this.searchData)
    },
    tableBtnClick (item, type) {
      switch (type) {
        case 'seeIllegal':
          alert(`查看违章：${item.id}`)
          break
        case 'seeLease':
          alert(`查看租赁：${item.id}`)
          break
      }
    }
  }
}
</script>
<!--render中的元素样式不能加scoped-->
<style lang="scss">
</style>
<style scoped lang="scss">
</style>
