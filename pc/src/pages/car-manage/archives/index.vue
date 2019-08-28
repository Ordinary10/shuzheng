<template>
    <div>
      <search>
        <div class="search-box">
          <Input class="search-input" v-model="searchData.plate_no" size="large" placeholder="请输入车牌" />
          <Input class="search-input" v-model="searchData.material_no" size="large" placeholder="请输入档案编号" />
          <Select v-model="searchData.ls_full" v-if="$common.pageInitInfo.car_status" class="search-input" size="large" placeholder="是否录入档案">
            <Option value="">全部（录入档案）</Option>
            <Option value="1">已录入</Option>
            <Option value="2">未录入</Option>
          </Select>
          <Select v-model="searchData.is_borrow" v-if="$common.pageInitInfo.car_status" class="search-input" size="large" placeholder="是否在借">
            <Option value="">全部（在借）</Option>
            <Option value="1">是</Option>
            <Option value="2">否</Option>
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
        fun: 'CarArchives/getCarArchives',
        columns: [
          {
            key: 'plate_no',
            title: '车牌',
            width: 100,
            render: (h, params) => {
              return <div>
                <license-plate row={params.row} key={'plate_no' + params.row.id}></license-plate>
              </div>
            }
          },
          {key: 'company_name', title: '所属公司', width: 160},
          {
            title: '档案编号',
            width: 120,
            render: (h, params) => {
              let d = params.row.category_data
              return <span>{d.material_no || ''}</span>
            }
          },
          {
            title: '登记证书',
            align: 'center',
            render: (h, params) => {
              let d = params.row.category_data
              return d.register_certificate === 'N'
                ? <span style="color:red;">{d.register_certificate || ''}</span>
                : <span>{d.register_certificate || ''}</span>
            }
          },
          {
            title: '交强险保单',
            align: 'center',
            render: (h, params) => {
              let d = params.row.category_data
              return d.jqx_insurance === 'N'
                ? <span style="color:red;">{d.jqx_insurance || ''}</span>
                : <span>{d.jqx_insurance || ''}</span>
            }
          },
          {
            title: '商业险保单',
            align: 'center',
            render: (h, params) => {
              let d = params.row.category_data
              return d.syx_insurance === 'N'
                ? <span style="color:red;">{d.syx_insurance || ''}</span>
                : <span>{d.syx_insurance || ''}</span>
            }
          },
          {
            title: '营运险保单',
            align: 'center',
            render: (h, params) => {
              let d = params.row.category_data
              return d.yy_insurance === 'N'
                ? <span style="color:red;">{d.yy_insurance || ''}</span>
                : <span>{d.yy_insurance || ''}</span>
            }
          },
          {
            title: '保险批单',
            align: 'center',
            render: (h, params) => {
              let d = params.row.category_data
              return d.pd_insurance === 'N'
                ? <span style="color:red;">{d.pd_insurance || ''}</span>
                : <span>{d.pd_insurance || ''}</span>
            }
          },
          {
            title: '购车发票',
            align: 'center',
            render: (h, params) => {
              let d = params.row.category_data
              return d.buy_bill === 'N'
                ? <span style="color:red;">{d.buy_bill || ''}</span>
                : <span>{d.buy_bill || ''}</span>
            }
          },
          {
            title: '购车发票存根联',
            align: 'center',
            render: (h, params) => {
              let d = params.row.category_data
              return d.buy_bill_cgl === 'N'
                ? <span style="color:red;">{d.buy_bill_cgl || ''}</span>
                : <span>{d.buy_bill_cgl || ''}</span>
            }
          },
          {
            title: '购车发票记账联',
            align: 'center',
            render: (h, params) => {
              let d = params.row.category_data
              return d.buy_bill_jzl === 'N'
                ? <span style="color:red;">{d.buy_bill_jzl || ''}</span>
                : <span>{d.buy_bill_jzl || ''}</span>
            }
          },
          {title: '购车发票抵扣联',
            align: 'center',
            render: (h, params) => {
              let d = params.row.category_data
              return d.buy_bill_dkl === 'N'
                ? <span style="color:red;">{d.buy_bill_dkl || ''}</span>
                : <span>{d.buy_bill_dkl || ''}</span>
            }
          },
          {
            title: '购置税发票',
            align: 'center',
            render: (h, params) => {
              let d = params.row.category_data
              return d.buy_tax === 'N'
                ? <span style="color:red;">{d.buy_tax || ''}</span>
                : <span>{d.buy_tax || ''}</span>
            }
          },
          {
            title: '购置税正本',
            align: 'center',
            render: (h, params) => {
              let d = params.row.category_data
              return d.buy_tax_zb === 'N'
                ? <span style="color:red;">{d.buy_tax_zb || ''}</span>
                : <span>{d.buy_tax_zb || ''}</span>
            }
          },
          {
            title: '行驶证',
            align: 'center',
            render: (h, params) => {
              let d = params.row.category_data
              return d.license === 'N'
                ? <span style="color:red;">{d.license || ''}</span>
                : <span>{d.license || ''}</span>
            }
          },
          {
            title: '交车表',
            align: 'center',
            render: (h, params) => {
              let d = params.row.category_data
              return d.lift_car === 'N'
                ? <span style="color:red;">{d.lift_car || ''}</span>
                : <span>{d.lift_car || ''}</span>
            }
          },
          {
            title: '工本费发票',
            align: 'center',
            render: (h, params) => {
              let d = params.row.category_data
              return d.cost_bill === 'N'
                ? <span style="color:red;">{d.cost_bill || ''}</span>
                : <span>{d.cost_bill || ''}</span>
            }
          },
          {
            title: '车证',
            align: 'center',
            render: (h, params) => {
              let d = params.row.category_data
              return d.car_license === 'N'
                ? <span style="color:red;">{d.car_license || ''}</span>
                : <span>{d.car_license || ''}</span>
            }
          },
          {
            title: '保养维修协议',
            align: 'center',
            render: (h, params) => {
              let d = params.row.category_data
              return d.repair_agreement === 'N'
                ? <span style="color:red;">{d.repair_agreement || ''}</span>
                : <span>{d.repair_agreement || ''}</span>
            }
          },
          {
            title: '挂靠协议',
            align: 'center',
            render: (h, params) => {
              let d = params.row.category_data
              return d.rely_agreement === 'N'
                ? <span style="color:red;">{d.rely_agreement || ''}</span>
                : <span>{d.rely_agreement || ''}</span>
            }
          },
          {
            title: '租赁合同',
            align: 'center',
            render: (h, params) => {
              let d = params.row.category_data
              return d.rent_contract === 'N'
                ? <span style="color:red;">{d.rent_contract || ''}</span>
                : <span>{d.rent_contract || ''}</span>
            }
          },
          {
            title: '是否在借',
            align: 'center',
            render: (h, params) => {
              let d = params.row.category_data
              return d.is_borrow ? <span>是</span> : <span>否</span>
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
                <i-button class="table-btn" type="primary" size="small" nativeOnClick={this.tableBtnClick.bind(this, params.row, 'borrowed')}>借还</i-button>
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
        case 'borrowed':
          alert(`借还：${item.id}`)
          break
      }
    }
  }
}
</script>
