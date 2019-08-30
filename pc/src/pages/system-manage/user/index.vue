<template>
  <div>
    <search>
      <div class="search-box">
        <!--搜索输入框-->
        <Input class="search-input" v-model="searchData.username" size="large" placeholder="请输入姓名" />
        <Input class="search-input" v-model="searchData.account" size="large" placeholder="请输入账号" />
        <Select v-model="searchData.role" class="search-input" size="large">
<!--          <Option value="">请输入门店</Option>-->
<!--          <Option v-for="item in $common.pageInitInfo.car_type" :value="item.id" :key="'car_type'+item.id">{{ item.name }}</Option>-->
        </Select>
        <Select v-model="searchData.status" class="search-input" size="large">
          <Option value="1">正常</Option>
          <Option value="0">禁用</Option>
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
          <Tooltip content="添加用户" placement="bottom-start">
            <Button class="commonly-used-btn" type="warning" size="large" icon="ios-add-circle-outline" @click="addUser" style="font-size: 18px"></Button>
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
            key: 'uid',
            title: '编号',
            align: 'center'
          },
          {
            key: 'account',
            title: '账号',
            align: 'center'
          },
          {
            key: 'position',
            title: '职位',
            align: 'center'
          },
          {
            key: 'group_name',
            title: '角色',
            align: 'center'
          },
          {
            key: 'ctime',
            title: '创建时间',
            align: 'center'
          },
          {
            title: '状态',
            align: 'center',
            render: (h, params) => {
              if (params.row.status === 1) {
                return <span class="green-color">正常</span>
              } else {
                return <span class="redtext">禁用</span>
              }
            }
          },
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
        username: '',
        account: '',
        status: '1',
        role: ''
      },
      startSearchData: {
        username: '',
        account: '',
        status: '',
        role: ''
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
    /* 新增用户 */
    addUser () {
      alert('添加')
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
