<template>
  <div>
    <search>
      <div class="search-box">
        <!--搜索输入框-->
        <Input class="search-input" v-model="searchData.name" size="large"  @keydown.13.native="search" placeholder="请输入商品名称" />
        <Select v-model="searchData.type_id" filterable class="search-input" size="large" placeholder="请选择类目">
          <Option :value="item.id" v-for="item of typeSelectData" :key="item.id">{{item.name}}</Option>
        </Select>
        <Select v-model="searchData.status" class="search-input" size="large" placeholder="请选择状态">
          <Option value="1">正常</Option>
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
          <Tooltip content="添加商品" placement="bottom-start">
            <Button class="commonly-used-btn" type="warning" size="large" icon="ios-add-circle-outline" @click="add" style="font-size: 18px"></Button>
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
          <FormItem label="商品名称" prop="name">
            <Input v-model="formItem.name" type="text" placeholder="请输入商品名"></Input>
          </FormItem>
          <FormItem label="类目" prop="typeList">
            <typeCascader ref="typeCascader" @init="init" @typeid = 'letType' :echoId="formItem.echoId" :changeOnSelect="false"></typeCascader>
          </FormItem>
          <FormItem label="安全库存" prop="safe_stock">
            <Input v-model="formItem.safe_stock" type="text" placeholder="请输入安全库存值(非零正整数)"></Input>
          </FormItem>
          <FormItem label="单位" prop="unit">
            <Input v-model="formItem.unit" type="text" placeholder="请输入商品单位"></Input>
          </FormItem>
          <FormItem label="二级单位" prop="lower_unit">
            <Input v-model="formItem.lower_unit" type="text" placeholder="请输入商品二级单位"></Input>
          </FormItem>
          <FormItem label="商品图片" >
            <img-upload ref="imgUpload" :config="ImgConfig"></img-upload>
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
      popupData: {},
      config: {
        fun: 'goods/getGoodsLists',
        columns: [
          {
            title: '图片',
            width: 80,
            align: 'center',
            render: (h, params) => {
              let url = params.row.img
              if (url) {
                let config = {
                  onlyShow: true,
                  oldImg: [
                    {url}
                  ],
                  width: '25px',
                  height: '25px',
                  borderRadius:'2px'
                }
                return <div style="cursor: pointer;">
                  <imgUpload config={config}></imgUpload>
                </div>
              } else {
                return h('span', {
                  style: {
                    transform: 'translateY(2px)'
                  },
                  class: 'defaultImg',
                  on: {
                    click: () => { }
                  }
                })
              }
            }
          },
          {
            key: 'name',
            title: '商品名称',
            align: 'center'
          },
          {
            key: 'type_name',
            title: '类目',
            align: 'center'
          },
          {
            key: 'unit',
            title: '单位',
            align: 'center'
          },
          {
            key: 'lower_unit',
            title: '二级单位',
            align: 'center'
          },
          {
            key: 'safe_stock',
            title: '安全库存',
            align: 'center'
          },
          {
            key: 'stock',
            title: '当前库存',
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
        name: '',
        status: '1',
        type_id: ''
      },
      startSearchData: {
        name: '',
        type_id: '',
        status: '1'
      },
      formItem: {
        name: '',
        unit: '',
        lower_unit: '',
        safe_stock: '',
        // 用于提交
        type_id: '',
        top_type_id: '',
        // 用于做验证
        typeList: [],
        // 回显的id
        echoId: ''
      },
      rule: {
        name: [{required: true, message: '必输项不能为空', trigger: 'blur'}
        ],
        unit: [{required: true, message: '必输项不能为空', trigger: 'blur'}
        ],
        lower_unit: [],
        typeList: [ {trigger: 'change',
          validator: (rule, value, callback) => {
            // console.log(value)
            if (!value.length) {
              return callback(new Error('必输项不能为空'))
            } else {
              callback()
            }
          },
          required: true,
          type: 'array'}
        ],
        safe_stock: [
          {validator: this.$validateFun.Znumber, required: true, trigger: 'blur'}
        ]
      },
      // 搜索类目选择框数据
      typeSelectData: [],
      ImgConfig: {
        max: 1,
        oldImg: [
          // {url: 'http://zucheguanjia.oss-cn-qingdao.aliyuncs.com/car/15689663869632.png'}
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
    init () {
      let typeData = this.$refs.typeCascader.type_data
      let _this = this
      function recursion (list) {
        list.forEach(e => {
          _this.typeSelectData.push({name: e.type_name, id: e.id})
          if (e.children && e.children.length) recursion(e.children)
        })
      }
      recursion(typeData)
    },
    add () {
      this.$refs.form.resetFields()
      this.modal1Title = '添加商品'
      for (let key in this.formItem) {
        this.formItem[key] = ''
      }
      this.formItem.typeList = []
      this.ImgConfig.oldImg = []
      this.modal1 = true
    },
    cancel () {
      this.modal1 = false
    },
    // 选择类目后 回显验证
    letType () {
      this.formItem.type_id = this.$refs.typeCascader.type_id
      this.formItem.top_type_id = this.$refs.typeCascader.top_type_id
      let obj = []
      this.$refs.typeCascader.typeList.forEach(key => {
        obj.push(key)
      })
      this.formItem.typeList = obj
    },
    save () {
      let _this = this
      _this.$refs.form.validate(valid => {
        if (valid) {
          _this.formItem.img = _this.$refs.imgUpload.getImgUrl()
          _this.$axios('goods/editGoods', _this.formItem).then((res) => {
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
          let title = item.status == 1 ? '禁用' : '启用'
          let content = `<p>确认${item.status == 1 ? '禁用' : '启动'}<span class="prominentText">${item.name}</span>？</p>`
          this.$Modal.confirm({
            title,
            content,
            loading: true,
            onOk: () => {
              _this.$axios('Goods/renewalGoodsStatus', {id: item.id}, true).then((res) => {
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
          this.modal1Title = '编辑商品'
          this.formItem.id = item.id
          this.formItem.name = item.name
          this.formItem.unit = item.unit
          this.formItem.lower_unit = item.lower_unit
          if (item.img) {
            this.ImgConfig.oldImg = item.img
          } else {
            this.ImgConfig.oldImg = ''
          }
          this.formItem.safe_stock = item.safe_stock
          if (this.formItem.echoId === item.type_id) {
            this.$refs.typeCascader.watchEchoId()
            this.letType()
          } else {
            this.formItem.echoId = item.type_id
          }
          this.modal1 = true
          break
      }
    }
  }
}
</script>
<style lang="scss" scoped>

</style>
