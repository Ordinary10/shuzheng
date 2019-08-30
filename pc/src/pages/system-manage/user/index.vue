<template>
  <div>
    <search>
      <div class="search-box">
        <!--搜索输入框-->
        <Input class="search-input" v-model="searchData.name" size="large" placeholder="请输入姓名" />
        <Select v-model="searchData.store" class="search-input" size="large">
<!--          <Option value="">请输入门店</Option>-->
<!--          <Option v-for="item in $common.pageInitInfo.car_type" :value="item.id" :key="'car_type'+item.id">{{ item.name }}</Option>-->
        </Select>
        <!--搜索按钮-->
        <div class="search-submit">
          <Tooltip content="更多搜索条件" placement="bottom-start">
            <Button class="search-btn " size="large" icon="ios-options-outline" type="primary" @click.native="isShow=!isShow"></Button>
          </Tooltip>
          <Button class="search-btn " size="large" icon="md-search" type="primary" @click.native="search"></Button>
          <Button class="refresh-btn search-btn" size="large" icon="md-refresh" type="info" @click.native="refresh"></Button>
        </div>
        <!--常用操作按钮-->
        <div class="commonly-used-btn-box">
          <Tooltip content="添加车辆" placement="bottom-start">
            <Button class="commonly-used-btn" type="warning" size="large" icon="ios-add-circle-outline" @click="addCar" style="font-size: 18px"></Button>
          </Tooltip>
        </div>
      </div>
    </search>
    <div class="content-block">
      <paging-table ref="pagingTable" :config="config" :searchData="searchData"></paging-table>
    </div>
    <!--查看详情-->
    <Modal
      v-model="modal1"
      title="查看详情"
      :footer-hide="true"
      :width='1350'
      class-name="vertical-center-modal"
    >
      <div class="mod-body"
           v-if="modal1"
      >
        查看详情
      </div>
    </Modal>
  </div>
</template>
<script type="text/jsx">
export default {
  data () {
    /*
      * isShow: 用于折叠搜索框的显示隐藏
      * iconType： 用于更多操作的icon变化
      * config: table的配置
      *     --> fun: table数据的接口
      *     --> columns: table的具体配置
      * searchData： 搜索栏的数据存储对象
      * startSearchData： 存储searchData的初始值，用于重置table
      * redundantList: 更多操作按钮的配置对象
      *            --> isShow 为true时按钮才显示，其余状态皆不可用.用于用户权限相关操作的隐藏显示
      *            --> type 作为触发点击操作的识别参数
      *            --> name 点击按钮功能描述
      *            --> isExcelModal 是否是批量导入按钮
      *            --> config 如果isExcelModal为true则必须设置
      *                   --> fun 批量上传的接口
      *                   --> demo 批量上传的模板下载接口
      *                   --> exts 批量上传文件格式
      *                   --> str 批量上传的注意说明
      * */
    return {
      isShow: false,
      modal1: false,
      add: '',
      popupData: {},
      config: {
        fun: 'User/getUserLists ',
        columns: [
          {
            key: 'name',
            title: '门店'
          },
          {
            key: 'plate_no',
            title: '车牌',
            width: 250,
            render: (h, params) => {
              // console.log(params.row)
              return <div>
              </div>
            }
          },
          {key: 'carVersion', title: '车型'},
          {key: 'expire_time', title: '保险到期时间'},
          {key: 'annual', title: '年审日期'},
          {key: 'status', title: '状态'},
          {
            key: 'caozuo',
            title: '操作',
            width: 160,
            align: 'center',
            render: (h, params) => {
              return <div class="table-btn-box">
                <i-button class="table-btn" type="info" size="small" nativeOnClick={this.tableBtnClick.bind(this, params.row, 'see')}>查看</i-button>
                <i-button class="table-btn" type="primary" size="small" nativeOnClick={this.tableBtnClick.bind(this, params.row, 'editor')}>编辑</i-button>
              </div>
            }
          }
        ]
      },
      searchData: {
        name: '',
        store: '',
      },
      startSearchData: {
        plate_no: '',
        vin: '',
        status: '',
        type: '',
        department: '',
        carVersion: ''
      }
    }
  },
  components: {
  },
  created () {
  },
  mounted () {
  },
  methods: {
    ok () {
      this.$Message.info('Clicked ok')
    },
    cancel () {
      this.$Message.info('Clicked cancel')
    },
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
      /* 不保留page刷新table（同刷新按钮） */
      // this.refresh()
    },
    /* 新增车辆 */
    addCar () {
      alert('添加车辆')
    },
    /* 搜索按钮 */
    search () {
      this.$refs.pagingTable.search(this.searchData)
    },
    /* 刷新按钮 */
    refresh () {
      /* 注意：不能将searchData引用为startSearchData，否则后续刷新将失效——引用（指针）与内存空间的问题 */
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
    /* table操作栏 */
    tableBtnClick (item, type) {
      switch (type) {
        case 'see':
          this.modal1 = !this.modal1
          this.add = item.plate_no
          this.popupData = {car_id: item.id}
          break
        case 'editor':
          alert(`编辑：${item.id}`)
          break
      }
    }
  }
}
</script>
<style lang="scss" scoped>

</style>
