<template>
    <div>
      <search>
        <div class="search-box">
          <Input class="search-input" v-model="searchData.brand" size="large" placeholder="请输入汽车品牌" />
          <Input class="search-input" v-model="searchData.car_model" size="large" placeholder="请输入车型" />
          <div class="search-submit">
<!--            <Tooltip content="更多搜索条件" placement="bottom-start">-->
<!--              <Button class="search-btn " size="large" icon="ios-options-outline" type="primary" @click.native="isShow=!isShow"></Button>-->
<!--            </Tooltip>-->
            <Button class="search-btn " size="large" icon="md-search" type="primary" @click.native="search"></Button>
            <Button class="refresh-btn search-btn" size="large" icon="md-refresh" type="info" @click.native="refresh"></Button>
          </div>
          <!--常用操作按钮-->
          <div class="commonly-used-btn-box">
            <Tooltip content="添加车型" placement="bottom-start">
              <Button class="commonly-used-btn" type="warning" size="large" icon="ios-add-circle-outline" @click="addCarModel" style="font-size: 18px"></Button>
            </Tooltip>
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
      config: {
        isShow: false,
        iconType: 'md-arrow-dropdown',
        fun: 'CarVersion/getCarModel',
        columns: [
          {key: 'id', title: '编号'},
          {key: 'brand', title: '品牌'},
          {key: 'car_model', title: '车型'},
          {key: 'car_number', title: '数量'},
          {key: 'guide_price', title: '指导价'},
          {key: 'result_status', title: '状态'},
          {
            key: 'caozuo',
            title: '操作',
            width: 240,
            align: 'center',
            render: (h, params) => {
              return <div class="table-btn-box">
                <i-button class="table-btn" type="info" size="small" nativeOnClick={this.tableBtnClick.bind(this, params.row, 'see')}>查看</i-button>
                <i-button class="table-btn" type="primary" size="small" nativeOnClick={this.tableBtnClick.bind(this, params.row, 'editor')}>编辑</i-button>
                <i-button class="table-btn" type="error" size="small" nativeOnClick={this.tableBtnClick.bind(this, params.row, 'delisting')}>下市</i-button>
              </div>
            }
          }
        ]
      },
      searchData: {
        brand: '',
        car_model: ''
      },
      startSearchData: {
        brand: '',
        car_model: ''
      },
      redundantList: []
    }
  },
  mounted () {

  },
  methods: {
    /* 添加车型 */
    addCarModel () {
      alert('添加车型')
    },
    search () {
      this.$refs.pagingTable.search(this.searchData)
    },
    refresh () {
      /* 注意：不能将searchData引用为startSearchData，否则后续刷新将失效——引用（指针）与内存空间的关系问题 */
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
        case 'see':
          alert(`查看：${item.id}`)
          break
        case 'editor':
          alert(`编辑：${item.id}`)
          break
        case 'delisting':
          alert(`下市：${item.id}`)
          break
      }
    }
  }
}
</script>
