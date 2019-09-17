<template>
  <div>
    <search>
      <div class="search-box">
<!--        &lt;!&ndash;搜索输入框&ndash;&gt;-->
<!--        <Select v-model="searchData.status" class="search-input" size="large" placeholder="请选择状态">-->
<!--          <Option value="1">启用</Option>-->
<!--          <Option value="0">禁用</Option>-->
<!--        </Select>-->
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
          <Tooltip content="添加角色" placement="bottom-start">
            <Button class="commonly-used-btn" type="warning" size="large" icon="ios-add-circle-outline" @click="addRole" style="font-size: 18px"></Button>
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
        <Form :model="formItem" :label-width="80" :rules="rule" ref="form" >
          <FormItem label="角色权限" >
            <Tree class="max_role" :data="role_data" :check-strictly="true" @on-check-change="checkChange" ref="tree" show-checkbox multiple></Tree>
          </FormItem>
          <FormItem label="角色名称" prop="title">
            <Input v-model="formItem.title" type="text" placeholder="请输入角色名称"></Input>
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
        fun: 'Role/getRoleList',
        columns: [
          {
            key: 'id',
            title: '编号',
            align: 'center'
          },
          {
            key: 'title',
            title: '名称',
            align: 'center'
          },
          {
            title: '状态',
            align: 'center',
            render: (h, params) => {
              if (params.row.status == 1) {
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
              if (params.row.status == 1) {
                return <div class="table-btn-box">
                  <i-button class="table-btn" type="primary" size="small"
                    nativeOnClick={this.tableBtnClick.bind(this, params.row, 'editor')}>编辑
                  </i-button>
                  <i-button class="table-btn" type="error" size="small"
                    nativeOnClick={this.tableBtnClick.bind(this, params.row, 'change')}>禁用
                  </i-button>
                </div>
              } else {
                return <div class="table-btn-box">
                  <i-button class="table-btn" type="primary" size="small"
                    nativeOnClick={this.tableBtnClick.bind(this, params.row, 'editor')}>编辑
                  </i-button>
                  <i-button class="table-btn" type="success" size="small"
                    nativeOnClick={this.tableBtnClick.bind(this, params.row, 'change')}>启动
                  </i-button>
                </div>
              }
            }
          }
        ]
      },
      searchData: {
        status: ''
      },
      startSearchData: {
        status: ''
      },
      formItem: {
        roleIds: [],
        title: ''
      },
      rule: {
        title: [{required: true, message: '必输项不能为空', trigger: 'blur'}
        ]
      },
      role_data: [
      ]
    }
  },
  components: {
  },
  created () {
  },
  mounted () {
    this.get_role_data(0)
  },
  methods: {
    checkChange (list, item) {
      console.log(list,item)
      let _this = this
      function recursion (data) {
        // 全选 去删除操作
        if (data.children.length) {
          data.children.forEach(e => {
            e.checked = data.checked
            if (e.children.length) {
              recursion(e.children)
            }
          })
        }
      }
      recursion(item)
      _this.clean_roleId()
    },
    addRole () {
      this.$refs.form.resetFields()
      this.get_role_data(0)
      this.formItem.title = ''
      if (this.formItem.hasOwnProperty('id')) delete this.formItem.id
      this.modal1Title = '添加角色'
      this.modal1 = true
    },
    cancel () {
      this.modal1 = false
    },
    async save () {
      let _this = this
      // _this.clean_roleId(_this.role_data)
      _this.formItem.rules = _this.form_rules
      console.log(this.formItem)
      let res = await _this.$axios('Role/editorRole', this.formItem)
      if (res.code == 1) {
        this.modal1 = false
        _this.pageRefresh()
      }
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
          let _this = this
          let title = item.status == -1 ? '启用' : '禁用'
          let content = `<p>确认${item.status == -1 ? '启动' : '禁用'}<span class="prominentText">${item.title}</span>？</p>`
          this.$Modal.confirm({
            title,
            content,
            loading: true,
            onOk: () => {
              _this.$axios('Role/renewalRoleStatus', {id: item.id}, true).then((res) => {
                _this.$Modal.remove()
                if (res.code == 1) {
                  _this.$Message.success({
                    content: res.msg,
                    duration: 2
                  })
                  _this.pageRefresh()
                }
              })
            }
          })
          break
        case 'editor':
          this.$refs.form.resetFields()
          this.modal1Title = '编辑角色'
          this.formItem.id = item.id
          this.formItem.title = item.title
          this.get_role_data(item.id)
          this.modal1 = true
          break
      }
    },
    // 为接口数据加上children 和data数据一样
    clean_role (data) {
      data.forEach(e => {
        e.children = e.data
        if (e.children.length) {
          this.clean_role(e.children)
        }
      })
    },
    // 提取选中的id值
    async clean_roleId () {
      let _this = this
      _this.formItem.roleIds = []
      let res = await this.$refs.tree.getCheckedAndIndeterminateNodes()
      // console.log(res)
      function recursion (data) {
        data.forEach(e => {
          if (e.checked) {
            if (!_this.formItem.roleIds.includes(e.id)) _this.formItem.roleIds.push(e.id)
          }
          if (e.children.length) {
            e.children.forEach(ele => {
              recursion(e.children)
            })
          }
        })
      }
      recursion(res)
    },
    async get_role_data (roleId) {
      let _this = this
      let res = await _this.$axios('Role/getRoleRules', {role_id: roleId})
      if (res.code == 1) {
        _this.role_data = res.data
        _this.clean_role(_this.role_data)
        _this.clean_roleId(_this.role_data)
      }
    }

  },
  computed: {
    form_rules () {
      return this.formItem.roleIds.join(',')
    }
  }
}
</script>
<style lang="scss" scoped>
  .max_role{
    max-height: 350px;
    overflow: auto;
  }
/deep/ .ivu-tree-title{
  font-size: 14px;
}
/deep/ .ivu-tree>.ivu-tree-children:first-of-type > li:first-of-type{
  margin: 0;
}
</style>
