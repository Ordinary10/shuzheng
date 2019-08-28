<template>
    <div>
      <search>
        <div class="search-box">
          <Input class="search-input" v-model="searchData.plate_no" size="large" placeholder="请输入车牌" />
          <Input class="search-input" v-model="searchData.days" size="large" placeholder="请输入天数(0-90天)" />
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
        <paging-table ref="pagingTable" :config="config" :searchData="searchData" ></paging-table>
      </div>
      <!--批量导入弹出框-->
      <Modal
        v-model="excelModal.isShow"
        :title="excelModal.title"
        :mask-closable="false"
      >
        <excelUpload :config="excelModal.config" v-if="excelModal.isShow" @excelUploadSuccess="excelUploadSuccess"></excelUpload>
        <div slot="footer"></div>
      </Modal>
    </div>
</template>
<script type="text/jsx">
export default {
  data () {
    return {
      isShow: false,
      iconType: 'md-arrow-dropdown',
      config: {
        fun: 'Car/reviewWarn',
        columns: [
          {key: 'company_name', title: '门店'},
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
          {key: 'carVersion', title: '车型'},
          {
            key: 'days',
            title: '逾期天数',
            render: (h, params) => {
              let d = params.row
              return d.is_overtime > 0
                ? <span class="warnText">{Math.abs(d.days)}</span>
                : <span class="normalText">{Math.abs(d.days)}</span>
            }
          },
          {key: 'annual', title: '车审到期日期', sortable: 'custom'},
          {key: 'license_annual', title: '证审到期日期', sortable: 'custom'},
          {key: 'gastank_annual', title: '气罐审到期日期', sortable: 'custom'},
          {
            key: 'caozuo',
            title: '操作',
            width: 160,
            align: 'center',
            render: (h, params) => {
              return <div class="table-btn-box">
                <i-button class="table-btn" type="primary" size="small" nativeOnClick={this.tableBtnClick.bind(this, params.row, 'editor')}>编辑</i-button>
              </div>
            }
          }
        ]
      },
      searchData: {
        plate_no: '',
        days: ''
      },
      startSearchData: {
        plate_no: '',
        days: ''
      },
      redundantList: [
        {
          type: 'importAuditList',
          label: '导入年审',
          isExcelModal: true,
          isShow: true,
          config: {
            'fun': 'BulkImport/annualImportData',
            'demo': 'annualImport',
            'exts': ['xlsx', 'xls'],
            'str': `日期格式必须设置为yyyy-mm-dd格式，如：2019-07-02
                    上传格式支持xlsx或xls `
          }
        },
        {
          type: 'exportList',
          label: '导出列表',
          isShow: true,
          exportFun: 'car/exportAnnual'
        }
      ],
      excelModal: {
        title: '',
        isShow: false,
        config: ''
      }
    }
  },
  mounted () {

  },
  methods: {
    /* 批量上传成功后执行该回调函数 */
    excelUploadSuccess () {
      /* 关闭弹窗 */
      this.excelModal = {
        title: '',
        isShow: false,
        config: ''
      }
      /* 保留page刷新table */
      this.pageRefresh()
    },
    /* 更多操作 */
    redundant (index) {
      let d = this.redundantList[index]
      if (d.isExcelModal === true) {
        this.excelModal.title = d.label
        this.excelModal.config = d.config
        this.excelModal.isShow = true
      } else if (d.type === 'exportList' && d.exportFun) {
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
        case 'editor':
          alert(`编辑：${item.id}`)
          break
      }
    }
  }
}
</script>
