<template>
  <div>
    <search>
      <div class="search-box">
        <!--搜索输入框-->
        <Input class="search-input" v-model="searchData.username" size="large" placeholder="请输入姓名" />
        <Input class="search-input" v-model="searchData.account" size="large" placeholder="请输入账号" />
        <Select v-model="searchData.role" class="search-input" size="large" placeholder="请选择角色">
          <Option :value="String(item.id)" v-for="item of roleData" :key="item.id">
            {{item.name}}
          </Option>
        </Select>
        <Select v-model="searchData.status" class="search-input" size="large" placeholder="请选择状态">
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
      :title="modal1Title"
      :width='600'
      class-name="vertical-center-modal"
      class="overstepModel"
    >
      <Form :model="formItem" :label-width="100" :rules="rule" ref="form" >

      <FormItem label="账号" prop="account">
        <Input v-model="formItem.account" type="text" placeholder="请输入账号"></Input>
      </FormItem>
      <FormItem label="密码" prop="pwd" v-if="showPwd">
        <Input v-model="formItem.pwd" type="password" placeholder="请输入密码"></Input>
      </FormItem>
      <FormItem label="姓名" prop="uname">
        <Input v-model="formItem.uname" type="text" placeholder="请输入真实姓名"></Input>
      </FormItem>
        <FormItem label="职位" prop="position">
          <Input v-model="formItem.position" type="text" placeholder="请输入职位"></Input>
        </FormItem>
      <FormItem label="角色分配" prop="role">
        <Select v-model="formItem.role" class="search-input" size="large" placeholder="请选择角色">
          <Option :value="String(item.id)" v-for="item of roleData" :key="item.id">
            {{item.name}}
          </Option>
        </Select>
      </FormItem>
        <FormItem label="所属公司" prop="dp_id">
          <Select v-model="formItem.dp_id" class="search-input" size="large" placeholder="请选择角色">
            <Option :value="String(item.id)" v-for="item of companyData" :key="item.id">
              {{item.name}}
            </Option>
          </Select>
        </FormItem>
        <FormItem label="状态" prop="status">
          <Select v-model="formItem.status" class="search-input" size="large" placeholder="请选择角色">
            <Option value="1">正常</Option>
            <Option value="0">禁用</Option>
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
      modal1Title: '',
      showPwd: true,
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
            key: 'uname',
            title: '姓名',
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
                <i-button class="table-btn" type="primary" size="small"
                  nativeOnClick={this.tableBtnClick.bind(this, params.row, 'editor')}>编辑
                </i-button>
              </div>
            }
          }
        ]
      },
      searchData: {
        username: '',
        account: '',
        status: '',
        role: ''
      },
      startSearchData: {
        username: '',
        account: '',
        status: '',
        role: ''
      },
      formItem: {
        account: '',
        pwd: '',
        role: '',
        dp_id: '',
        position: '',
        uname: '',
        status: '1'
      },
      rule: {
        account: [{required: true, message: '必输项不能为空', trigger: 'blur'},
          {type: 'string', min: 5, message: '最少5个字符', trigger: 'blur'},
          {type: 'string', max: 10, message: '最多10个字符', trigger: 'blur'}
        ],
        pwd: [{required: true, message: '必输项不能为空', trigger: 'blur'},
          {type: 'string', min: 6, message: '不能少于6个字符', trigger: 'blur'}
        ],
        uname: [{required: true, message: '必输项不能为空', trigger: 'blur'}
        ],
        position: [{required: true, message: '必输项不能为空', trigger: 'blur'}
        ],
        dp_id: [{required: true, message: '必输项不能为空', trigger: 'change'}
        ],
        role: [{required: true, message: '必输项不能为空', trigger: 'change'}
        ],
        status: [{required: true, message: '必输项不能为空', trigger: 'change'}
        ]
      },
      // 角色选项数据
      roleData: [],
      // 门店选项数据
      companyData: []
    }
  },
  components: {
  },
  created () {
  },
  mounted () {
    // 初始化
    // this.init()
    let _this = this
    this.$common.PageInitInfo(['role', 'company']).then((res) => {
      _this.roleData = res.data.role
      _this.companyData = res.data.company
    })
  },
  methods: {
    async init () {
      let data = await this.$common.test(['role', 'company'])
      this.roleData = data.role
      this.companyData = data.company
    },
    /* 新增用户 */
    addUser () {
      this.showPwd = true
      this.modal1Title = '新增用户'
      for (let key in this.formItem) {
        this.formItem[key] = ''
      }
      this.formItem.uid = 0
      this.formItem.status = '1'
      this.modal1 = true
    },
    cancel () {
      this.modal1 = false
    },
    save () {
      let _this = this
      _this.$refs.form.validate(valid => {
        if (valid) {
          console.log(_this.formItem)
          _this.$axios('user/edit', this.formItem).then((res) => {
            if (res.code === 1) {
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
          // let title = item.status === 0 ? '启用' : '禁用'
          // let content = item.status === 0 ? `<p>确认启用${item.account}？</p>` : `<p>确认禁用${item.account}？</p>`
          // this.$Modal.confirm({
          //   title,
          //   content,
          //   onOk: () => {
          //     // this.$axios('Company/renewalCompany', {id: item.id}, true).then((res) => {
          //     //   this.pageRefresh()
          //     // })
          //   }
          // })
          break
        case 'editor':
          this.showPwd = false
          this.modal1Title = '编辑用户'
          this.formItem.uid = item.uid
          this.formItem.position = item.position
          this.formItem.account = item.account
          this.formItem.uname = String(item.uname)
          this.formItem.status = String(item.status)
          this.formItem.dp_id = String(item.dp_id || '')
          this.formItem.role = String(item.group_id || '')
          this.modal1 = true
          break
      }
    }
  }
}
</script>
<style lang="scss" scoped>

</style>
