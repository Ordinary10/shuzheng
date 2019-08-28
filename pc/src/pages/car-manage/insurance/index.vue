<template>
    <div>
      <search>
        <div class="search-box">
          <Input class="search-input" v-model="searchData.plate_no" size="large" placeholder="请输入车牌" />
          <Select v-model="searchData.car_status" class="search-input" size="large" placeholder="请选择车辆状态">
            <Option value="">全部（车辆状态）</Option>
            <Option v-for="item in $common.pageInitInfo.car_status" :value="item.id" :key="'car_status'+item.id">{{ item.name }}</Option>
          </Select>
          <Select v-model="searchData.isbuy" class="search-input" size="large" placeholder="请选择是否购保">
            <Option value="">全部（是否购保）</Option>
            <Option :value="1" selected>是</Option>
            <Option :value="2">否</Option>
          </Select>
          <Input v-show="isShow" class="search-input" v-model="searchData.days" size="large" placeholder="请输入脱保天数" />
          <Select v-show="isShow" v-model="searchData.quote_time" class="search-input" size="large" placeholder="报价时间">
            <Option value="">全部（报价时间）</Option>
            <Option value="no">未报价</Option>
            <Option value="week">一周</Option>
            <Option value="month">一月</Option>
          </Select>
          <div class="search-submit">
            <Tooltip content="更多搜索条件" placement="bottom-start">
              <Button class="search-btn " size="large" icon="ios-options-outline" type="primary" @click.native="isShow=!isShow"></Button>
            </Tooltip>
            <Button class="search-btn " size="large" icon="md-search" type="primary" @click.native="search"></Button>
            <Button class="refresh-btn search-btn" size="large" icon="md-refresh" type="info" @click.native="refresh"></Button>
          </div>
          <!--更多操作-->
          <div class="redundant-btn" v-if="redundantList && redundantList.length>0">
            <Dropdown>
              <Button type="primary" size="large" @mouseout.native="iconType='md-arrow-dropdown'" @mouseover.native="iconType='md-arrow-dropup'">
                更多操作
                <Icon :type="iconType" />
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
        fun: 'Car/insuranceWarn',
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
            title: '脱保天数',
            sortable: 'custom',
            render: (h, params) => {
              let d = params.row
              return d.is_overtime > 0
                ? <span class="warnText">{Math.abs(d.days)}</span>
                : <span class="normalText">{Math.abs(d.days)}</span>
            }
          },
          {key: 'etime', title: '商业险到期日'},
          {key: 'jqx_etime', title: '交强险到期日'},
          {key: 'quote_time', title: '报价时间', sortable: 'custom'},
          {
            key: 'caozuo',
            title: '操作',
            width: 240,
            align: 'center',
            render: (h, params) => {
              return <div class="table-btn-box">
                <i-button class="table-btn" type="info" size="small" nativeOnClick={this.tableBtnClick.bind(this, params.row, 'see')}>查看</i-button>
                <i-button class="table-btn" type="primary" size="small" nativeOnClick={this.tableBtnClick.bind(this, params.row, 'offer')}>报价</i-button>
                <i-button class="table-btn" type="success" size="small" nativeOnClick={this.tableBtnClick.bind(this, params.row, 'upload')}>上传</i-button>
              </div>
            }
          }
        ]
      },
      searchData: {
        plate_no: '',
        car_status: '',
        days: '',
        isbuy: 1,
        quote_time: ''
      },
      startSearchData: {
        plate_no: '',
        car_status: '',
        days: '',
        isbuy: 1,
        quote_time: ''
      },
      redundantList: [
        {
          type: 'importPolicyList',
          label: '批量保单',
          isShow: true,
          isExcelModal: true,
          config: {
            'fun': 'BulkImport/insuranceImportData',
            'demo': 'insuranceImport',
            'exts': ['xlsx', 'xls'],
            'str': `日期格式必须设置为yyyy-mm-dd格式，如：2019-07-02
            有录入交强险金额则更新交强险到期时间
            有录入商业险金额则更新商业险到期时间
            保单开始日期和结束日期必填
            商业险与交强险必填其中一项
            填写保单号有则更新,无则添加
            无保单号则直接新增保单
            上传格式支持xlsx或xls`
          }
        },
        {
          type: 'importQuotationList',
          label: '批量报价',
          isShow: true,
          isExcelModal: true,
          config: {
            'fun': 'BulkImport/insuranceQuote',
            'demo': 'insuranceQuote',
            'exts': ['xlsx', 'xls'],
            'str': `日期格式必须设置为yyyy-mm-dd格式，如：2019-07-02
            上传格式支持xlsx或xls`
          }
        },
        {
          type: 'exportList',
          label: '导出列表',
          isShow: true,
          exportFun: 'car/isuranceExportData'
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
        case 'see':
          alert(`查看：${item.id}`)
          break
        case 'offer':
          alert(`报价：${item.id}`)
          break
        case 'upload':
          alert(`上传：${item.id}`)
          break
      }
    }
  }
}
</script>
