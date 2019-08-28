<template>
    <div>
      <search>
        <div class="search-box">
          <Input class="search-input" v-model="searchData.plate_no" size="large" placeholder="请输入车牌" />
          <Input class="search-input" v-model="searchData.editor_name" size="large" placeholder="请输入录入员" />
          <Select v-model="searchData.type" class="search-input" size="large" placeholder="请选择类型">
            <Option value="">全部（类型）</Option>
            <Option value="1">保养</Option>
            <Option value="2">维修</Option>
          </Select>
          <div class="search-submit">
<!--            <Tooltip content="更多搜索条件" placement="bottom-start">-->
<!--              <Button class="search-btn " size="large" icon="ios-options-outline" type="primary" @click.native="isShow=!isShow"></Button>-->
<!--            </Tooltip>-->
            <Button class="search-btn " size="large" icon="md-search" type="primary" @click.native="search"></Button>
            <Button class="refresh-btn search-btn" size="large" icon="md-refresh" type="info" @click.native="refresh"></Button>
          </div>
          <!--常用操作按钮-->
          <div class="commonly-used-btn-box">
            <Tooltip content="新增维修" placement="bottom-start">
              <Button class="commonly-used-btn" type="warning" size="large" icon="ios-add-circle-outline" @click="addMaintain">维修</Button>
            </Tooltip>
            <Tooltip content="新增保养" placement="bottom-start">
              <Button class="commonly-used-btn" type="warning" size="large" icon="ios-add-circle-outline" @click="addUpkeep">保养</Button>
            </Tooltip>
          </div>
          <div class="redundant-btn" v-if="redundantList && redundantList.length>0">
            <Dropdown>
              <Button type="primary" size="large">
                更多操作
                <Icon type="ios-arrow-down"></Icon>
              </Button>
              <DropdownMenu slot="list">
                <DropdownItem v-for="(item,index) in redundantList" :key="item.type" @click.native="redundant(index)">{{item.label}}</DropdownItem>
              </DropdownMenu>
            </Dropdown>
          </div>
        </div>
      </search>
      <div class="content-block">
        <paging-table ref="pagingTable" :config="config" :searchData="searchData"></paging-table>
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
        fun: 'CarUpkeep/getCarUpkeep',
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
          {key: 'name', title: '姓名'},
          {key: 'mobile', title: '手机'},
          {key: 'type_name', title: '类型'},
          {key: 'editor_name', title: '录入员'},
          {key: 'do_time', title: '日期'},
          {key: 'mile', title: '公里数'},
          {key: 'content', title: '内容'},
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
        editor_name: '',
        type: ''
      },
      startSearchData: {
        plate_no: '',
        editor_name: '',
        type: ''
      },
      redundantList: [
        {
          type: 'addUpkeepList',
          label: '批量保养',
          isExcelModal: true,
          isShow: true,
          config: {
            'fun': 'BulkImport/carUpkeepImportData',
            'demo': 'carUpkeep',
            'exts': ['xlsx', 'xls'],
            'str': `日期格式必须设置为yyyy-mm-dd格式，如：2019-07-02
                    上传格式支持xlsx或xls`
          }
        },
        {
          type: 'addMaintainList',
          label: '批量维修',
          isExcelModal: true,
          isShow: true,
          config: {
            'fun': 'BulkImport/carRepairsImportData',
            'demo': 'carRepairs',
            'exts': ['xlsx', 'xls'],
            'str': `日期格式必须设置为yyyy-mm-dd格式，如：2019-07-02
                    上传格式支持xlsx或xls`
          }
        },
        {
          type: 'exportList',
          label: '导出列表',
          isShow: true,
          exportFun: 'CarUpkeep/exportCarUpkeep'
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
    /* 新增维修保养 */
    addUpkeep () {
      alert('新增保养')
    },
    addMaintain () {
      alert('新增维修')
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
