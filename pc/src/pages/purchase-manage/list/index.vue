<template>
  <div>
    <search>
      <div class="search-box">
        <!--搜索输入框-->
        <Input class="search-input" v-model="searchData.name" size="large" placeholder="请输入申请人姓名" />
        <DatePicker class="search-input" type="date" placeholder="申请时间" size="large" value='yyyy-MM-dd' @on-change="searchData.apply_time=$event" v-model="searchData.apply_time"></DatePicker>
        <Select v-model="searchData.status" class="search-input" size="large" placeholder="选择状态">
          <Option :value="String(item.id)" v-for="item of statusData" :key="item.id">
            {{item.name}}
          </Option>
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
          <Tooltip content="新增采购" placement="bottom-start">
            <Button class="commonly-used-btn" type="warning" size="large" icon="ios-add-circle-outline" @click="add" style="font-size: 18px"></Button>
          </Tooltip>
        </div>
      </div>
    </search>
    <div class="content-block">
      <paging-table ref="pagingTable" :config="config" :searchData="searchData"></paging-table>
    </div>
    <!--新增-->
    <Modal
      v-model="addisShow"
      :title="modal1Title"
      :width='980'
      class-name="vertical-center-modal"
      :footer-hide="true"
    >
      <add></add>
    </Modal>
    <!--查看详情-->
    <Modal
      v-model="modal1"
      :title="modal1Title"
      :width='800'
      class-name="vertical-center-modal"
      :footer-hide="true"
    >
      <Row v-if="getDeta">
        <div class="ma-spacing">
          <steps type="purchase" :progress="progress"></steps>
        </div>
        <Divider />

        <Col span="12">
          <div class="ma-spacing">
            申请人：<span class="key_text">{{ApplyData.uname}}</span>
          </div>
        </Col>
        <Col span="12">
          <div class="ma-spacing">
            门店：<span class="key_text">{{ApplyData.store_name}}</span>
          </div>
        </Col>
        <Col span="12">
          <div class="ma-spacing">
            申请时间：<span class="key_text">{{ApplyData.ctime}}</span>
          </div>
        </Col>
        <Col span="12">
          <div class="ma-spacing">
            状态：<span class="key_text">{{ApplyData.status_name}}</span>
          </div>
        </Col>
        <Col span="12">
          <div class="ma-spacing">
            总金额：<span class="key_text">{{ApplyData.total_amount}}</span>
          </div>
        </Col>
        <Divider />
        <Col span="24" v-for="(list,index) in seeData" :key="list.id">
          <Col span="12">
            <div class="ma-spacing">
              商品名称：<span class="key_text">{{list.name}}</span>
            </div>
          </Col>
          <Col span="12">
            <div class="ma-spacing">
              买入金额：<span class="key_text">{{list.buy_amount}}</span>
            </div>
          </Col>
          <Col span="12">
            <div class="ma-spacing">
              申请金额：<span class="key_text">{{list.apply_amount}}</span>
            </div>
          </Col>
          <Col span="12">
            <div class="ma-spacing">
              购买金额：<span class="key_text">{{list.buy_money}}</span>
            </div>
          </Col>
          <Col span="12">
            <div class="ma-spacing">
              预计金额：<span class="key_text">{{list.estimated_money}}</span>
            </div>
          </Col>
          <Col span="12">
            <div class="ma-spacing">
              单位：<span class="key_text">{{list.unit}}</span>
            </div>
          </Col>
          <Col span="12">
            <div class="ma-spacing">
              单价：<span class="key_text">{{list.unit_price}}</span>
            </div>
          </Col>
          <Col span="12">
            <div class="ma-spacing">
              商品条码：<span class="key_text">{{list.bar_code}}</span>
            </div>
          </Col>
          <Col span="12">
            <div class="ma-spacing">
              仓库：<span class="key_text">{{list.place}}</span>
            </div>
          </Col>

        </Col>
      </Row>
    </Modal>
    <!--入库-->
    <Modal
      v-model="modal2"
      :title="modal1Title"
      :width='980'
      class-name="vertical-center-modal"
      :footer-hide="true"
    >
      <Row v-if="getDeta">
        <div class="ma-spacing">
          <steps type="purchase" :progress="progress"></steps>
        </div>
        <Divider />

        <Row>
            <Col span="6">
              <div class="ma-spacing">
                申请人：<span class="key_text">{{ApplyData.uname}}</span>
              </div>
            </Col>
            <Col span="6">
              <div class="ma-spacing">
                门店：<span class="key_text">{{ApplyData.store_name}}</span>
              </div>
            </Col>
            <Col span="6">
              <div class="ma-spacing">
                申请时间：<span class="key_text">{{ApplyData.ctime}}</span>
              </div>
            </Col>
            <Col span="6">
              <div class="ma-spacing">
                状态：<span class="key_text">{{ApplyData.status_name}}</span>
              </div>
            </Col>
          </Row>
          <Divider />
          <Row class="storage-body roll">
            <Col span="24" v-for="(list,index) in seeData" :key="list.id">
              <Row>
                <Col span="6">
                  <div class="ma-spacing">
                    商品名称：<span class="key_text">{{list.name}}</span>
                  </div>
                </Col>
                <Col span="6">
                  <div class="ma-spacing">
                    买入数量：<span class="key_text">{{list.buy_amount}}</span>
                  </div>
                </Col>
                <Col span="6">
                  <div class="ma-spacing">
                    申请金额：<span class="key_text">{{list.apply_amount}}</span>
                  </div>
                </Col>
                <Col span="6">
                  <div class="ma-spacing">
                    购买金额：<span class="key_text">{{list.buy_money}}</span>
                  </div>
                </Col>
                <Col span="6">
                  <div class="ma-spacing">
                    单位：<span class="key_text">{{list.unit}}</span>
                  </div>
                </Col>
                <Col span="6">
                  <div class="ma-spacing">
                    单价：<span class="key_text">{{list.unit_price}}</span>
                  </div>
                </Col>
              </Row>
              <Form :ref="'seeData' + index" :model="list" :rules="ruleInline" :label-width="50">
                <Col span="6">
                  <div class="ma-nomb-spacing">
                    <FormItem label="条码"prop="bar_code">
                      <Input v-model="list.bar_code"></Input>
                    </FormItem>
                  </div>
                </Col>
                <Col span="6">
                  <div class="ma-nomb-spacing">
                    <FormItem label="仓库"prop="place">
                      <Input v-model="list.place"></Input>
                    </FormItem>
                  </div>
                </Col>
              </Form>
              <Divider />
            </Col>
          </Row>
          <Row>
<!--            <Col span="24">-->
<!--              <div class="ma-spacing">-->
<!--                <Input v-model="storageremark" type="textarea" placeholder="入库备注" />-->
<!--              </div>-->
<!--            </Col>-->
            <Col span="24">
              <div class="ma-spacing">
                <Button type="success" @click="handleSubmit()">入库</Button>
              </div>
            </Col>
          </Row>
      </Row>
    </Modal>
    <!--审核-->
    <Modal
      v-model="modal3"
      :title="modal1Title"
      :width='980'
      class-name="vertical-center-modal"
      :footer-hide="true"
    >
      <Row v-if="getDeta">
        <steps type="purchase" :progress="progress"></steps>
        <Divider />

        <Row>
            <Col span="6">
              <div class="ma-spacing">
                申请人：<span class="key_text">{{ApplyData.uname}}</span>
              </div>
            </Col>
            <Col span="6">
              <div class="ma-spacing">
                门店：<span class="key_text">{{ApplyData.store_name}}</span>
              </div>
            </Col>
            <Col span="6">
              <div class="ma-spacing">
                申请时间：<span class="key_text">{{ApplyData.ctime}}</span>
              </div>
            </Col>
            <Col span="6">
              <div class="ma-spacing">
                状态：<span class="key_text">{{ApplyData.status_name}}</span>
              </div>
            </Col>
          </Row>
          <Divider/>
          <Row class="examine-body roll">
            <Col span="24" v-for="(list,index) in seeData" :key="list.id">
              <Row>
                <Col span="6">
                  <div class="ma-spacing">
                    商品名称：<span class="key_text">{{list.name}}</span>
                  </div>
                </Col>
                <Col span="6">
                  <div class="ma-spacing">
                    买入金额：<span class="key_text">{{list.buy_amount}}</span>
                  </div>
                </Col>
                <Col span="6">
                  <div class="ma-spacing">
                    申请金额：<span class="key_text">{{list.apply_amount}}</span>
                  </div>
                </Col>
                <Col span="6">
                  <div class="ma-spacing">
                    购买金额：<span class="key_text">{{list.buy_money}}</span>
                  </div>
                </Col>
                <Col span="6">
                  <div class="ma-spacing">
                    预计金额：<span class="key_text">{{list.estimated_money}}</span>
                  </div>
                </Col>
                <Col span="6">
                  <div class="ma-spacing">
                    单位：<span class="key_text">{{list.buy_amount}}</span>
                  </div>
                </Col>
                <Col span="6">
                  <div class="ma-spacing">
                    单价：<span class="key_text">{{list.buy_money}}</span>
                  </div>
                </Col>
                <Col span="6">
                  <div class="ma-spacing">
                    商品条码：<span class="key_text">{{list.bar_code}}</span>
                  </div>
                </Col>
                <Col span="6">
                  <div class="ma-spacing">
                    仓库：<span class="key_text">{{list.place}}</span>
                  </div>
                </Col>
              </Row>
              <Divider />

            </Col>
          </Row>
          <Row>
            <Col span="6">
              <div class="ma-spacing box-ib" v-for="(list,index) in commoData"
                   :key="list.id">
                <Button
                  @click="commodity(list.name)"

                  class="box-ib"
                  :class="{primary:arr.includes(list.name)}"
                >{{list.name}}</Button>
              </div>
            </Col>
            <Col span="24">
              <div class="ma-spacing">
                总金额：<span class="key_text">{{ApplyData.total_amount}}</span>
              </div>
            </Col>
          </Row>
          <Form ref="formValidate" v-if="getDeta.data.status=='apply'">
            <Col span="24">
              <div class="ma-nomb-spacing">
                <FormItem label="审核意见">
                  <Input v-model="examineremark" type="textarea" placeholder="审核意见" />
                </FormItem>
              </div>
            </Col>
            <Col span="24">
              <div class="ma-spacing">
                <Button type="success" @click="examine('pass',examineremark)">同意</Button>
                <Button type="error" @click="examine('deny',examineremark)">拒绝</Button>
              </div>
            </Col>
          </Form>
      </Row>
    </Modal>

  </div>
</template>
<script type="text/jsx">
import add from './add'

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
      *            --> isShow 为true时按钮才显示，其余状态皆不可用.用于商品权限相关操作的隐藏显示
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
      addisShow: false,
      isShow: false,
      modal1: false,
      modal2: false,
      modal3: false,
      seeData: '',
      ApplyData: '',
      getDeta: '',
      // steps用的流程数组
      progress: [],
      examineremark: '',
      storageremark: '',
      commoData: [],
      arr: [],
      neworder_id: '',
      modal1Title: '',
      popupData: {},
      statusData: '',
      ruleInline: {
        name: [
          { required: true, message: '请输入内容', trigger: 'blur' }
        ],
        buy_amount: [
          { required: true, message: '请输入购买数量', trigger: 'blur' }
        ],
        apply_amount: [
          { required: true, message: '请输入申请金额', trigger: 'blur' }
        ],
        buy_money: [
          { required: true, message: '请输入购买金额', trigger: 'blur' }
        ],
        estimated_money: [
          { required: true, message: '请输入预计金额', trigger: 'blur' }
        ],
        unit: [
          { required: true, message: '请输入单位', trigger: 'blur' }
        ],
        unit_price: [
          { required: true, message: '请输入单价', trigger: 'blur' }
        ],
        bar_code: [
          { required: true, message: '请输入商品条码', trigger: 'blur' }
        ],
        place: [
          { required: true, message: '请输入仓库', trigger: 'blur' }
        ]
      },
      config: {
        fun: 'purchaseOrder/getLists',
        columns: [
          {
            key: 'uname',
            title: '申请人',
            align: 'center'
          },
          {
            key: 'store_name',
            title: '门店',
            align: 'center'
          },
          {
            key: 'ctime',
            title: '申请时间',
            align: 'center'
          },
          {
            key: 'status_name',
            title: '状态',
            align: 'center'
          },
          {
            key: 'total_amount',
            title: '总金额',
            align: 'center'
          },
          {
            key: 'caozuo',
            title: '操作',
            align: 'center',
            render: (h, params) => {
              let status = params.row.status
              let text = params.row.status_name
              console.log(status)
              if (status == 'apply') { // 待审核
                return <div class="table-btn-box">
                  <i-button class="table-btn" type="error" size="small"
                    nativeOnClick={this.tableBtnClick.bind(this, params.row, status)}>{{text}}
                  </i-button>
                </div>
              } else {
                return <div class="table-btn-box">
                  <i-button class="table-btn" type="primary" size="small"
                    nativeOnClick={this.tableBtnClick.bind(this, params.row, 'editor')}>查看
                  </i-button>
                </div>
              }
            }
          }
        ]
      },
      searchData: {
        name: '',
        apply_time: '',
        status: ''
      },
      startSearchData: {
        name: '',
        apply_time: '',
        status: ''

      },
      formItem: {
        id: '', // id
        name: '', // 申请人
        time: '', // 时间
        store: '', // 门店
        type: '', // 加盟
        state: '', // 状态
        remark: '' // 备注
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
    add
  },
  created () {
  },
  mounted () {
    // 初始化
    let _this = this
    this.$common.PageInitInfo(['purchase_order_status']).then((res) => {
      _this.statusData = res.data.purchase_order_status
    })
  },
  methods: {
    modasear (res, is) {
      if (res.code === 1) {
        this.pageRefresh()
        this.$Modal.remove()
        this.$set(this.$data, is, false)
        this.instance('success', res)
      } else {
        this.instance('error', res)
      }
    },
    instance (type, res) {
      let content = res.msg
      switch (type) {
        case 'info':
          this.$Modal.info({
            title: '提示',
            content: content
          })
          setTimeout(() => {
            this.$Modal.remove()
          }, 2500)
          break
        case 'success':
          this.$Modal.success({
            title: '成功',
            content: content
          })
          setTimeout(() => {
            this.$Modal.remove()
          }, 2500)
          break
        case 'warning':
          this.$Modal.warning({
            title: '警告',
            content: content
          })
          setTimeout(() => {
            this.$Modal.remove()
          }, 2500)
          break
        case 'error':
          this.$Modal.error({
            title: '错误',
            content: content
          })
          setTimeout(() => {
            this.$Modal.remove()
          }, 2500)
          break
      }
    },
    // 点击入库进行表单验证
    handleSubmit () {
      let arr = []
      this.seeData.forEach((data, key) => {
        let form = 'seeData' + key

        this.$refs[form][0].validate((valid) => {
          if (valid) {
            arr.push(true)
          } else {
            arr.push(false)
          }
        })
      })
      let flag = arr.every((item) => {
        return item === true
      })
      if (flag) {
        this.storage()
      } else {
        this.$Message.error('请填写相关内容')
      }
    },
    commodity (name) {
      if (this.arr.includes(name)) {
        this.arr = this.arr.filter(function (ele) { return ele != name })
      } else {
        this.arr.push(name)
      }
      this.seeData = []
      for (let key in this.arr) {
        for (let i in this.commoData) {
          if (this.commoData[i].name == this.arr[key]) {
            this.seeData.push(this.commoData[i])
          }
        }
      }
      // 如果没有过滤则显示全部
      if (!this.seeData[0]) {
        this.seeData = this.commoData
      }
    },
    add () {
      this.modal1Title = '采购申请'
      this.addisShow = true
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
      if (type === 'buy') {
        this.modal1Title = '入库'
        this.ApplyData = item
        this.purchaseSee(item.id)
        this.modal2 = true
      } else if (type === 'editor') {
        this.modal1Title = '查看编辑'
        this.ApplyData = item
        this.purchaseSee(item.id)
        this.modal1 = true
      } else if (type === 'apply') {
        this.modal1Title = '审核'
        this.ApplyData = item
        this.purchaseSee(item.id)
        this.modal3 = true
      }
    },
    // 审核同意
    async examine (type, remark) {
      const _this = this
      let res = await _this.$axios('purchaseOrder/verify', {verify_status: type, remark: remark, order_id: this.ApplyData.id})
      this.modasear(res, 'modal3')
    },
    // 请求采购单详情
    async purchaseSee (order_id) {
      const _this = this
      let res = await _this.$axios('purchaseOrder/getDetailInfo', {order_id: order_id})
      if (res.code === 1) {
        this.seeData = res.data.detail
        this.getDeta = res
        this.progress = JSON.parse(JSON.stringify(res.data.progress))
        this.commoData = JSON.parse(JSON.stringify(this.seeData))
      }
    },
    // 入库
    async storage () {
      const _this = this
      let data = []
      let admins = {
        place: '',
        bar_code: '',
        detail_id: ''
      }
      this.seeData.map((value, index, arry) => {
        data.push({
          place: value.place,
          bar_code: value.bar_code,
          detail_id: value.id})
      })
      let res = await _this.$axios('purchaseOrder/putInStorage', {order_id: this.ApplyData.id, data: data})
      this.modasear(res, 'modal2')
    }
  }
}
</script>
<style lang="scss" scoped>

</style>
