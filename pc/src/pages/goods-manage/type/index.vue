<template>
  <div>
    <search>
      <div class="search-box">
<!--        &lt;!&ndash;搜索输入框&ndash;&gt;-->
<!--        <Input class="search-input" v-model="searchData.name" size="large" placeholder="请输入类目名称" />-->
<!--        &lt;!&ndash;搜索按钮&ndash;&gt;-->
<!--        <div class="search-submit">-->
<!--          <Tooltip content="更多搜索条件" placement="bottom-start">-->
<!--            <Button class="search-btn " size="large" icon="ios-options-outline" type="primary" @click.native="isShow=!isShow"></Button>-->
<!--          </Tooltip>-->
<!--          <Button class="search-btn " size="large" icon="md-search" type="primary" @click.native="search"></Button>-->
<!--          <Button class="refresh-btn search-btn" size="large" icon="md-refresh" type="info" @click.native="refresh"></Button>-->
<!--        </div>-->
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
      class="overstepModel"
    >
        <Form :model="formItem" :label-width="100" :rules="rule" ref="form" >
          <FormItem label="父级类目">
            <typeCascader ref="typeCascader" @typeid = 'letType' :echoId="formItem.pid"></typeCascader>
          </FormItem>
          <FormItem label="类目名称" prop="type_name">
            <Input v-model="formItem.type_name" type="text" placeholder="请输入类目名"></Input>
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
        type_name: '',
        id: '',
        pid: ''
      },
      rule: {
        type_name: [{required: true, message: '必输项不能为空', trigger: 'blur'}
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
      tableRequest(options, {}).then(res => {
        this.table.data = res.data
      }).catch(err => {
        console.log(err)
      })
    },
    add () {
      this.$refs.form.resetFields()
      this.modal1Title = '添加类目'
      for (let key in this.formItem) {
        this.formItem[key] = ''
      }
      this.modal1 = true
    },
    cancel () {
      this.modal1 = false
    },
    letType () {
      this.formItem.pid = this.$refs.typeCascader.type_id
    },
    save () {
      let _this = this
      // console.log(this.formItem)
      if (!this.formItem.pid) this.formItem.pid = 0
      if (this.formItem.pid === this.formItem.id) {
        this.$Modal.error({
          title: '类目错误',
          content: '父级类目和自身重复,请点击添加按钮进行操作!'
        })
        return false
      }
      _this.$refs.form.validate(valid => {
        if (valid) {
          _this.$axios('goods/editGoodsType', this.formItem, true).then((res) => {
            if (res.code === 1) {
              _this.$Message.success({
                content: res.msg,
                duration: 2
              })
              this.modal1 = false
              // _this.pageRefresh()
              this.getTableData()
              this.$refs.typeCascader.init()
            }
          })
        } else {
          return false
        }
      })
    },
    /* 搜索按钮 */
    search () {
      this.$refs.pagingTable.search(this.searchData)
    },
    /* 保留page刷新table */
    // pageRefresh () {
    //   let obj = {}
    //   Object.keys(this.startSearchData).forEach(key => {
    //     obj[key] = this.startSearchData[key]
    //   })
    //   this.searchData = obj
    //   this.$refs.pagingTable.pageRefresh(this.searchData)
    // },
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
          // console.log(item)
          this.$refs.form.resetFields()
          this.modal1Title = '编辑类目'
          this.formItem.id = item.id
          this.formItem.pid = item.pid
          this.formItem.type_name = item.type_name
          this.modal1 = true
          break
      }
    }
  }
}
</script>
<style lang="scss" scoped>

</style>
