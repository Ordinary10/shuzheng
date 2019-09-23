<template>
  <div>
    <search>
      <div class="search-box">
        <!--搜索输入框-->
        <Input class="search-input" v-model="searchData.name" size="large" placeholder="请输入申请人姓名" />
        <DatePicker class="search-input" type="date" placeholder="申请时间" size="large" value='yyyy-MM-dd' @on-change="searchData.apply_time=$event" v-model="searchData.apply_time"></DatePicker>
        <!--搜索按钮-->
        <div class="search-submit">
          <Tooltip content="更多搜索条件" placement="bottom-start">
            <Button class="search-btn " size="large" icon="ios-options-outline" type="primary" @click.native="isShow=!isShow"></Button>
          </Tooltip>
          <Button class="search-btn " size="large" icon="md-search" type="primary" @click.native="search"></Button>
          <Button class="refresh-btn search-btn" size="large" icon="md-refresh" type="info" @click.native="refresh"></Button>
        </div>
      </div>
    </search>
    <div class="content-block">
      <paging-table ref="pagingTable" :config="config" :searchData="searchData"></paging-table>
    </div>
    <!--入库-->
    <Modal
      v-model="modal"
      :title="modalTitle"
      :width='980'
      class-name="vertical-center-modal"
    >
      <Row v-if="this.getDeta">
        <div class="ma-spacing">
          <steps type="purchase" :file="this.getDeta.data"></steps>
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
              <Form :ref="'seeData' + index" :model="list" :rules="ruleInline" :label-width="70">
                <Col span="6">
                  <div class="ma-nomb-spacing">
                    <FormItem label="批次号" prop="bar_code">
                      <Input v-model="list.bar_code" disabled></Input>
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
                <Col span="6">
                  <div class="ma-nomb-spacing">
                    <FormItem label="单位"prop="unit">
                      <Input v-model="list.unit"></Input>
                    </FormItem>
                  </div>
                </Col>
                <Col span="6">
                  <div class="ma-nomb-spacing">
                    <FormItem label="数量"prop="inNum">
                      <Input v-model="list.inNum"></Input>
                    </FormItem>
                  </div>
                </Col>
              </Form>
              <Divider />
            </Col>
          </Row>
        <div slot="footer">
              <Button type="text" size="large" @click="cancel">取消</Button>
              <Button type="primary" size="large" @click="handleSubmit">确定</Button>
        </div>
      </Row>
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
      addisShow: false,
      isShow: false,
      modal: false,
      seeData: '',
      getDeta: '',
      examineremark: '',
      storageremark: '',
      commoData: [],
      arr: [],
      modalTitle: '',
      statusData: '',
      ruleInline: {
        unit: [
          { required: true, message: '请输入单位', trigger: 'blur' }
        ],
        bar_code: [
          { required: true, message: '请输入商品批次号', trigger: 'blur' }
        ],
        place: [
          { required: true, message: '请输入仓库', trigger: 'blur' }
        ],
        inNum: [
          { required: true, message: '请输入入库数量', trigger: 'blur' }
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
              return <div class="table-btn-box">
                <i-button className="table-btn" type="success" size="small"
                  nativeOnClick={this.tableBtnClick.bind(this, params.row, 'approval')}>入库
                </i-button>
              </div>
            }
          }
        ]
      },
      searchData: {
        name: '',
        apply_time: '',
        status: 'buy'
      },
      startSearchData: {
        name: '',
        apply_time: '',
        status: 'buy'
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
    // commodity (name) {
    //   if (this.arr.includes(name)) {
    //     this.arr = this.arr.filter(function (ele) { return ele != name })
    //   } else {
    //     this.arr.push(name)
    //   }
    //   this.seeData = []
    //   for (let key in this.arr) {
    //     for (let i in this.commoData) {
    //       if (this.commoData[i].name === this.arr[key]) {
    //         this.seeData.push(this.commoData[i])
    //       }
    //     }
    //   }
    //   // 如果没有过滤则显示全部
    //   if (!this.seeData[0]) {
    //     this.seeData = this.commoData
    //   }
    // },
    cancel () {
      // 关闭入库框
      this.modal = false
    },
    save () {
      // let _this = this
      // _this.$refs.form.validate(valid => {
      //   if (valid) {
      //     // _this.$axios('Company/editorCompany', this.formItem, true).then((res) => {
      //     //   if (res.code === 1) {
      //     //     _this.$Message.success({
      //     //       content: res.msg,
      //     //       duration: 2
      //     //     })
      //     //     this.modal1 = false
      //     //     _this.pageRefresh()
      //     //   }
      //     // })
      //   } else {
      //     return false
      //   }
      // })
    },
    /* 搜索按钮 */
    search () {
      this.$refs.pagingTable.search(this.searchData)
    },
    /* 刷新按钮 */
    refresh () {
      /* 注意：不能将searchData引用为startSearchData，否则后续刷新将失效——引用（指针）与内存空间的问题 */
      this.searchData = JSON.parse(JSON.stringify(this.startSearchData))
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
      if (type === 'approval') {
        this.modalTitle = '入库'
        this.ApplyData = item
        this.purchaseSee(item.id)
        this.modal = true
      }
    },
    // 请求采购单详情
    async purchaseSee (order_id) {
      const _this = this
      let res = await _this.$axios('purchaseOrder/getDetailInfo', {order_id: order_id})
      this.seeData = res.data.detail
      function test (num) {
        return num > 9 ? num : '0' + num
      }
      this.seeData.forEach((e, index) => {
        // 自动填入批次号
        let date = new Date()
        let month = test(date.getMonth() + 1)
        e.bar_code =
          '' +
          date.getFullYear() +
          month + test(date.getDate()) +
          test(date.getHours()) +
          test(date.getMinutes()) +
          test(date.getSeconds()) +
          test(index)
      })
      this.getDeta = res
    },
    // 入库
    async storage () {
      const _this = this
      let data = []
      this.seeData.map((value, index, arry) => {
        data.push({
          place: value.place,
          bar_code: value.bar_code,
          detail_id: value.id})
      })
      let res = await _this.$axios('purchaseOrder/putInStorage', {order_id: this.ApplyData.id, data: data})
      if (res.code === 1) {
        this.pageRefresh()
        this.$Modal.remove()
        this.$set(this.$data, 'modal', false)
        this.instance('success', res)
      } else {
        this.instance('error', res)
      }
    }
  }
}
</script>
<style lang="scss" scoped>

</style>
