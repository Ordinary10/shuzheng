<template>
  <div>
    <search>
      <div class="search-box">
        <!--搜索输入框-->
        <Input class="search-input" v-model="searchData.name" size="large"  @keydown.13.native="search" placeholder="请输入门店名称" />
        <Select v-model="searchData.type" class="search-input" size="large" placeholder="请选择经营方式">
          <Option value="1">直营</Option>
          <Option value="2">加盟</Option>
        </Select>
        <Select v-model="searchData.status" class="search-input" size="large" placeholder="请选择状态">
          <Option value="1">启用</Option>
          <Option value="2">禁用</Option>
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
          <Tooltip content="添加门店" placement="bottom-start">
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
          <FormItem label="门店名称" prop="name">
            <Input v-model="formItem.name" type="text" placeholder="请输入门店名"></Input>
          </FormItem>
          <FormItem label="门店地址" prop="location">
            <Input v-model="formItem.location" type="text" placeholder="请输入门店地址"></Input>
          </FormItem>
          <FormItem label="电话号码" prop="mobile">
            <Input v-model="formItem.mobile" type="text" placeholder="请输入电话号码"></Input>
          </FormItem>
          <FormItem label="经营方式" prop="gender">
            <RadioGroup v-model="formItem.type">
              <Radio label="1">直营</Radio>
              <Radio label="2">加盟</Radio>
            </RadioGroup>
          </FormItem>
          <FormItem label="备注" >
            <Input v-model="formItem.remark" type="textarea" :autosize="{minRows: 2,maxRows: 5}" placeholder="请输入备注"></Input>
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
      add: '',
      popupData: {},
      config: {
        fun: 'Company/getCompanyLists',
        columns: [
          {
            key: 'name',
            title: '门店名称',
            align: 'center'
          },
          {
            key: 'location',
            title: '位置',
            align: 'center'
          },
          {
            key: 'mobile',
            title: '电话号码',
            align: 'center'
          },
          {
            key: 'type_name',
            title: '经营方式',
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
              if (params.row.status === 1) {
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
        name: '',
        type: '',
        status: ''
      },
      startSearchData: {
      },
      formItem: {
        id: '',
        name: '',
        location: '',
        mobile: '',
        remark: '',
        type: ''
      },
      rule: {
        name: [{required: true, message: '必输项不能为空', trigger: 'blur'}
        ],
        location: [{required: true, message: '必输项不能为空', trigger: 'blur'}
        ],
        mobile: [
          {required: true, message: '必输项不能为空', trigger: 'blur'},
          {validator: this.$validateFun.phone,
            trigger: 'blur'}
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
      this.formItem = {type: '1'}
      this.modal1Title = '添加用户'
      this.modal1 = true
    },
    cancel () {
      this.modal1 = false
    },
    save () {
      let _this = this
      _this.$refs.form.validate(valid => {
        if (valid) {
          _this.$axios('Company/editorCompany', this.formItem).then((res) => {
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
          let _this = this
          let title = item.status === 0 ? '启用' : '禁用'
          let content = `<p>确认${item.status === 0 ? '启动' : '禁用'}<span class="prominentText">${item.name}</span>？</p>`
          this.$Modal.confirm({
            title,
            content,
            loading: true,
            onOk: () => {
              this.$axios('Company/renewalCompany', {id: item.id}, true).then((res) => {
                _this.$Modal.remove()
                if (res.code === 1) {
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
          this.modal1Title = '编辑用户'
          this.formItem.id = item.id
          this.formItem.name = item.name
          this.formItem.location = item.location
          this.formItem.type = String(item.type)
          this.formItem.mobile = item.mobile
          this.formItem.remark = item.remark
          this.modal1 = true
          break
      }
    }
  }
}
</script>
<style lang="scss" scoped>

</style>
