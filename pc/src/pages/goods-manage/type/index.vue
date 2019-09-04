<template>
  <div>
    <search>
      <div class="search-box">
        <!--搜索输入框-->
        <Input class="search-input" v-model="searchData.name" size="large" placeholder="请输入类目名称" />
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
          <Tooltip content="添加类目" placement="bottom-start">
            <Button class="commonly-used-btn" type="warning" size="large" icon="ios-add-circle-outline" @click="add" style="font-size: 18px"></Button>
          </Tooltip>
        </div>
      </div>
    </search>
    <div class="content-block">
      <Table
        stripe
        :highlight-row="true"
        :columns="table.columns"
        :data="table.data"
       ></Table>
    </div>
    <!--查看详情-->
    <Modal
      v-model="modal1"
      :title="modal1Title"
      :width='600'
      class-name="vertical-center-modal"
    >
        <Form :model="formItem" :label-width="100" :rules="rule" ref="form" >
          <FormItem label="类目名称" prop="name">
            <Input v-model="formItem.name" type="text" placeholder="请输入类目名"></Input>
          </FormItem>
        </Form>
        <div slot="footer">
        <Button type="text" size="large" @click="cancel">取消</Button>
        <Button type="primary" size="large" @click="save">确定</Button>
        </div>
    </Modal>
  </div>
</template>
<script type="text/jsx">
import { Table } from 'iview'
import { tableRequest } from '@/utils/request'
export default {
  data () {
    /*
      * isShow: 用于折叠搜索框的显示隐藏
      * config: table的配置
      *     --> fun: table数据的接口
      *     --> columns: table的具体配置
      * searchData： 搜索栏的数据存储对象
      * startSearchData： 存储searchData的初始值，用于重置table
      * */
    return {
      isShow: false,
      modal1: false,
      modal1Title: '',
      table: {
        columns: [
          {
            key: 'type_name',
            maxWidth: 300,
            title: '类目名称',
            align: 'center'
          },
          {
            title: '子类目',
            align: 'center',
            render: (h, params) => {
              if (params.row.children) {
                if (params.row.children.length) {
                  return <div>
                    <Table stripe columns={this.table.columns} data={params.row.children}></Table>
                  </div>
                } else {
                  return <span>无</span>
                }
              } else {
                return <span>无</span>
              }
            }
          },
          {
            key: 'caozuo',
            title: '操作',
            minWidth: 60,
            maxWidth: 100,
            align: 'center',
            render: (h, params) => {
              return <div class="table-btn-box">
                <i-button class="table-btn" type="primary" size="small"
                  nativeOnClick={this.tableBtnClick.bind(this, params.row, 'editor')}>编辑
                </i-button>
              </div>
            }
          }
        ],
        data: []
      },
      searchData: {
        name: '',
        status: ''
      },
      startSearchData: {
        name: '',
        status: ''
      },
      formItem: {
        name: '',
        unit: '',
        type_id: '',
        safe_stock: ''
      },
      rule: {
        name: [{required: true, message: '必输项不能为空', trigger: 'blur'}
        ]
      }
    }
  },
  components: {
    Table
  },
  created () {
  },
  mounted () {
    this.getTableData()
  },
  methods: {
    getTableData () {
      /* fun:数据接口；page:页数；limit:返回数据条数 */
      let options = {
        fun: 'goods/getGoodsType'
      }
      tableRequest(options,{}).then(res => {
        this.table.data = res.data
      }).catch(err => {
        console.log(err)
      })
    },
    add () {
      this.$refs.form.resetFields()
      this.modal1Title = '添加类目'
      this.modal1 = true
    },
    cancel () {
      this.modal1 = false
    },
    save () {
      let _this = this
      _this.$refs.form.validate(valid => {
        if (valid) {
          // _this.$axios('Company/editorCompany', this.formItem, true).then((res) => {
          //   if (res.code === 1) {
          //     _this.$Message.success({
          //       content: res.msg,
          //       duration: 2
          //     })
          //     this.modal1 = false
          //     _this.pageRefresh()
          //   }
          // })
        } else {
          return false
        }
      })
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
        case 'change':
          let title = item.status === 0 ? '启用' : '禁用'
          let content = `<p>确认${item.status === 0 ? '启动' : '禁用'}<span class="prominentText">${item.name}</span>？</p>`
          this.$Modal.confirm({
            title,
            content,
            onOk: () => {
              alert('没有')
              // this.$axios('Company/renewalCompany', {id: item.id}, true).then((res) => {
              //   this.pageRefresh()
              // })
            }
          })
          break
        case 'editor':
          console.log(item)
          this.$refs.form.resetFields()
          this.modal1Title = '编辑类目'
          this.formItem.id = item.id
          this.formItem.name = item.name
          this.formItem.unit = item.unit
          this.formItem.safe_stock = item.safe_stock
          this.modal1 = true
          break
      }
    }
  }
}
</script>
<style lang="scss" scoped>

</style>
