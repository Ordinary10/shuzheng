<template>
  <div>
    <search>
      <div class="search-box">
        <!--搜索输入框-->
        <Input class="search-input" v-model="searchData.name" size="large"  @keydown.13.native="search" placeholder="请输入供应商名称" />
        <Select v-model="searchData.status" class="search-input" size="large" placeholder="请选择状态">
          <Option value="1">启用</Option>
          <Option value="-1">禁用</Option>
        </Select>
        <!--搜索按钮-->
        <div class="search-submit">
<!--          <Tooltip content="更多搜索条件" placement="bottom-start">-->
<!--            <Button class="search-btn " size="large" icon="ios-options-outline" type="primary" @click.native="isShow=!isShow"></Button>-->
<!--          </Tooltip>-->
          <Button class="search-btn " size="large" icon="md-search" type="primary" @click.native="search"></Button>
          <Button class="refresh-btn search-btn" size="large" icon="md-refresh" type="info" @click.native="refresh"></Button>
        </div>
        <!--常用操作按钮-->
        <div class="commonly-used-btn-box">
          <Tooltip content="添加供应商" placement="bottom-start">
            <Button class="commonly-used-btn" type="warning" size="large" icon="ios-add-circle-outline" @click="addCompany" style="font-size: 18px"></Button>
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
      :title="modal1Title"
      :width='600'
      class-name="vertical-center-modal"
    >
        <Form :model="formItem" :label-width="100" :rules="rule" ref="form" >
          <FormItem label="供应商名称" prop="name">
            <Input v-model="formItem.name" type="text" placeholder="请输入供应商名"></Input>
          </FormItem>
          <FormItem label="备注" >
            <Input v-model="formItem.remark" type="textarea" :autosize="{minRows: 2,maxRows: 5}" placeholder="请输入备注"></Input>
          </FormItem>
          <FormItem label="状态" prop="status">
            <Select v-model="formItem.status" class="search-input" size="large" placeholder="请选择角色">
              <Option value="1">正常</Option>
              <Option value="-1">禁用</Option>
            </Select>
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
      add: '',
      popupData: {},
      config: {
        fun: 'Supplier/getLists',
        columns: [
          {
            key: 'id',
            title: '编号',
            align: 'center'
          },
          {
            key: 'name',
            title: '供应商名称',
            align: 'center'
          },
          {
            key: 'remark',
            title: '备注',
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
                <i-button class="table-btn" type="primary" size="small"
                  nativeOnClick={this.tableBtnClick.bind(this, params.row, 'editor')}>编辑
                </i-button>
              </div>
            }
          }
        ]
      },
      searchData: {
        name: '',
        status: ''
      },
      startSearchData: {
      },
      formItem: {
        id: '',
        name: '',
        remark: '',
        status: ''
      },
      rule: {
        name: [
          {required: true, message: '必输项不能为空', trigger: 'blur'},
          {type: 'string', max: 20, message: '不超过二十个字符', trigger: 'blur'}
        ],
        status: [{required: true, message: '必输项不能为空', trigger: 'blur'}
        ]
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
    addCompany () {
      this.$refs.form.resetFields()
      for (let key in this.formItem) {
        this.formItem[key] = ''
      }
      delete this.formItem.id
      this.modal1Title = '添加供应商'
      this.modal1 = true
    },
    cancel () {
      this.modal1 = false
    },
    save () {
      let _this = this
      _this.$refs.form.validate(valid => {
        if (valid) {
          _this.$axios('Supplier/edit', this.formItem, true).then((res) => {
            if (res.code === 1) {
              _this.$Message.success({
                content: res.msg,
                duration: 2
              })
              this.modal1 = false
              _this.pageRefresh()
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
    /* 保留page和搜索状态刷新table */
    pageRefresh () {
      this.$refs.pagingTable.pageRefresh(this.searchData)
    },
    /* table操作栏 */
    tableBtnClick (item, type) {
      switch (type) {
        case 'change':
          // let title = item.status === 0 ? '启用' : '禁用'
          // let content = `<p>确认${item.status === 0 ? '启动' : '禁用'}<span class="prominentText">${item.name}</span>？</p>`
          // this.$Modal.confirm({
          //   title,
          //   content,
          //   onOk: () => {
          //     this.$axios('Company/renewalCompany', {id: item.id}, true).then((res) => {
          //       this.pageRefresh()
          //     })
          //   }
          // })
          break
        case 'editor':
          this.$refs.form.resetFields()
          this.modal1Title = '编辑供应商'
          this.formItem.id = item.id
          this.formItem.name = item.name
          this.formItem.remark = item.remark
          this.formItem.status = String(item.status)
          this.modal1 = true
          break
      }
    }
  }
}
</script>
<style lang="scss" scoped>

</style>
