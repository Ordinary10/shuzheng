<template>
    <div>
      <search>
        <div class="search-box">
          <Input class="search-input" v-model="searchData.plate_no" size="large" placeholder="请输入车牌" />
          <Select v-model="searchData.car_status" v-if="$common.pageInitInfo.car_status" class="search-input" size="large" placeholder="请选择车辆状态">
            <Option value="">全部（车辆状态）</Option>
            <Option v-for="item in $common.pageInitInfo.car_status" :value="item.id" :key="'car_status'+item.id">{{ item.name }}</Option>
          </Select>
          <Select v-model="searchData.department" v-if="$common.pageInitInfo.company_info" class="search-input" size="large" placeholder="请选择门店">
            <Option value="">全部（门店）</Option>
            <Option v-for="item in $common.pageInitInfo.company_info" :value="item.id" :key="'company_info'+item.id">{{ item.name }}</Option>
          </Select>
          <Input class="search-input" v-model="searchData.customer_name" size="large" placeholder="请输入姓名" />
          <Input v-show="isShow" type="number" min="0" class="search-input" v-model="searchData.num" size="large" placeholder="次数" />
          <Input v-show="isShow" type="number" min="0" class="search-input" v-model="searchData.score" size="large" placeholder="分数" />
          <Input v-show="isShow" type="number" min="0" class="search-input" v-model="searchData.fine" size="large" placeholder="金额" />
          <Input v-show="isShow" type="number" min="0" class="search-input" v-model="searchData.overScore" size="large" placeholder="12分" />
          <div class="search-submit">
            <Tooltip content="更多搜索条件" placement="bottom-start">
              <Button class="search-btn " size="large" icon="ios-options-outline" type="primary" @click.native="isShow=!isShow"></Button>
            </Tooltip>
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
        fun: 'IllegalSearch/getAutoList',
        columns: [
          {key: 'company_name', title: '门店'},
          {key: 'ctname', title: '姓名'},
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
          {
            key: 'ig_nums',
            title: '违章次数',
            sortable: 'custom',
            render: (h, params) => {
              let d = params.row
              let color = '#666666'
              if (d.ig_nums >= d.num) {
                color = 'red'
              }
              return d.urgent_nums
                ? <span style= {'color:' + color}>{d.ig_nums}({d.urgent_nums})</span>
                : <span style= {'color:' + color}>{d.ig_nums}</span>
            }
          },
          {
            key: 'ig_score',
            title: '违章分数',
            sortable: 'custom',
            render: (h, params) => {
              let d = params.row
              return d.ig_score >= d.score
                ? <span style="color: red">{d.ig_score}</span>
                : <span>{d.ig_score}</span>
            }
          },
          {
            key: 'ig_fine',
            title: '违章罚金',
            sortable: 'custom',
            render: (h, params) => {
              let d = params.row
              return d.ig_fine >= d.fine
                ? <span style="color: red">{d.ig_fine}</span>
                : <span>{d.ig_fine}</span>
            }
          },
          {key: 'query_time', title: '更新日期'},
          {
            key: 'is_error',
            title: '查询结果',
            render: (h, params) => {
              let d = params.row
              return d.is_error === 0 ? <span style="color:#1E9FFF;">成功</span> : <span>{d.error_reason}</span>
            }
          },
          {
            key: 'caozuo',
            title: '操作',
            width: 160,
            align: 'center',
            render: (h, params) => {
              return <div class="table-btn-box">
                <i-button class="table-btn" type="info" size="small" nativeOnClick={this.tableBtnClick.bind(this, params.row, 'see')}>查看</i-button>
              </div>
            }
          }
        ]
      },
      searchData: {
        plate_no: '',
        car_status: '',
        department: '',
        customer_name: '',
        num: '',
        score: '',
        fine: '',
        overScore: ''
      },
      startSearchData: {
        plate_no: '',
        car_status: '',
        department: '',
        customer_name: '',
        num: '',
        score: '',
        fine: '',
        overScore: ''
      },
      redundantList: [
        {
          type: 'setThreshold',
          label: '设置阀值',
          isShow: true
        },
        {
          type: 'exportList',
          label: '导出列表',
          isShow: true,
          exportFun: 'IllegalSearch/DerivedTable'
        }
      ]
    }
  },
  mounted () {
  },
  methods: {
    /* 更多操作 */
    redundant (index) {
      let d = this.redundantList[index]
      if (d.type === 'exportList' && d.exportFun) {
        const _this = this
        let str = ''
        Object.keys(_this.searchData).forEach(key => {
          str += `&params[${key}]=${_this.searchData[key]}`
        })
        window.open(`${_this.$common.API_PATH}?fun=${d.exportFun}&token=${sessionStorage.getItem('token')}${str}`)
      } else {
        alert(d.label)
      }
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
      }
    }
  }
}
</script>
