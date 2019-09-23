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
        <!--常用操作按钮-->
        <div class="commonly-used-btn-box">
          <Tooltip content="入库" placement="bottom-start">
            <Button class="commonly-used-btn" type="warning" size="large" icon="ios-add-circle-outline" @click="add" style="font-size: 18px"></Button>
          </Tooltip>
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
      :width='1200'
      class-name="vertical-center-modal"
    >
      <div class="card-body">
        <div style="margin: 5px 0;overflow: hidden">
          <Button style="float: right" type="primary" size="large" @click="addGood">添加商品</Button>
        </div>
        <Divider />
        <Form :model="item" :rules="rule" :label-width="60"   ref="form" v-for="(item,index) in addGoods">
          <Col span="4">
            <FormItem label="批次号">
              <Input v-model="item.code" type="text" placeholder="请输入批次号" disabled></Input>
            </FormItem>
          </Col>
          <Col span="6">
            <FormItem label="商品">
              <Cascader :data="goodsData" placeholder="请选择商品" @mouseenter.native="selectGoodsBefore(item.serial)"  @on-change="selectGoods"></Cascader>
            </FormItem>
          </Col>
          <Col span="4">
            <FormItem label="单位" prop="unit" >
              <Select v-model="item.unit" class="search-input" size="large" placeholder="请选择单位">
                <Option :value="String(i)" v-for="i of item.unitData"  :key="i">
                  {{i}}
                </Option>
              </Select>
            </FormItem>
          </Col>
          <Col span="4">
            <FormItem label="库位" prop="location" >
              <Input v-model="item.location" type="text" placeholder="请填写入库位置"></Input>
            </FormItem>
          </Col>
          <Col span="4">
            <FormItem label="数量" prop="num" >
              <Input v-model="item.num" type="text" placeholder="请填写入库数量"></Input>
            </FormItem>
          </Col>
          <Col span="2" style="display: flex;justify-content: center">
            <div class="iconfont icon_delete iconshanchuqq" @click="remove(item)"></div>
          </Col>
        </Form>
        <Divider />
        <Form :model="formItem" :label-width="60">
          <FormItem label="备注">
            <Input v-model="formItem.remark" type="textarea" :autosize="{minRows: 2,maxRows: 5}" placeholder="请输入备注"></Input>
          </FormItem>
        </Form>
      </div>
      <div slot="footer">
        <Button type="text" size="large" @click="cancel">取消</Button>
        <Button type="primary" size="large" @click="handleSubmit">入库</Button>
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
      modal: false,
      modalTitle: '',
      statusData: '',
      goodsData: [],
      // 添加商品的信息条
      addGoods: [],
      // 联级选择商品时,临时用来存储当前信息条的编号
      currentSerial: '',
      rule: {
        unit: [
          { required: true, message: '请选择商品单位', trigger: 'blur' }
        ],
        location: [
          { required: true, message: '请填写入库位置', trigger: 'blur' }
        ],
        num: [
          { required: true, message: '请填写入库数量', trigger: 'blur' }
        ]
      },
      formItem: {
        remark: ''
      },
      config: {
        fun: 'purchaseOrder/getLists',
        columns: [
          {
            key: 'store_name',
            title: '门店',
            align: 'center'
          }
          // {
          //   key: 'caozuo',
          //   title: '操作',
          //   align: 'center',
          //   render: (h, params) => {
          //     return <div class="table-btn-box">
          //       <i-button className="table-btn" type="success" size="small"
          //         nativeOnClick={this.tableBtnClick.bind(this, params.row, 'approval')}>入库
          //       </i-button>
          //     </div>
          //   }
          // }
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
    this.init()
  },
  methods: {
    async init () {
      let res = await this.$axios('goods/getTypeWithGoods', {})
      function test (data) {
        data.forEach(e => {
          e.label = e.type_name || e.name
          e.value = e.id
          e.children = e.goods || []
          if (e.children.length) {
            test(e.children)
          }
        })
      }
      test(res.data)
      this.goodsData = JSON.parse((JSON.stringify(res.data)))
      this.addGood()
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
    add () {
      this.modal = true
      this.modalTitle = '入库'
      // this.addGoods = []
      // this.addGood()
      // this.formItem.remark=''
    },
    // 添加出库商品条
    addGood () {
      // 自动填入批次号
      function test (num) {
        return num > 9 ? num : '0' + num
      }
      let date = new Date()
      let month = test(date.getMonth() + 1)
      let code =
            '' +
            date.getFullYear() +
            month + test(date.getDate()) +
            test(date.getHours()) +
            test(date.getMinutes()) +
            test(date.getSeconds()) +
            test(this.addGoods.length)

      let obj = {
        // 商品条序号
        serial: this.addGoods.length,
        code,
        id: '',
        unit: '',
        unitData: [],
        location: '',
        num: ''
      }
      this.addGoods.push(obj)
    },
    // 删除出库商品条
    remove (item) {
      this.addGoods.splice(this.addGoods.indexOf(item), 1)
    },
    // 选择完商品后回调
    selectGoods (e, selectedData) {
      let selectedGoods = selectedData.pop()
      let goods = this.addGoods[+this.currentSerial]
      // 赋值
      // console.log(selectedGoods)
      goods.id = selectedGoods.id
      goods.unitData = [selectedGoods.unit]
      if (selectedGoods.lower_unit === selectedGoods.unit) {
        goods.unit = selectedGoods.unit
      } else {
        goods.unitData.push(selectedGoods.lower_unit)
      }
    },
    // 准备选择商品
    selectGoodsBefore (id) {
      this.currentSerial = id
    },
    // 点击入库进行表单验证
    handleSubmit () {
      if (!this.addGoods.length) {
        this.$Message.error('请选择出库商品')
        return false
      }
      let arr = []
      this.$refs.form.forEach(form => {
        form.validate((valid) => {
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
    cancel () {
      // 关闭入库框
      this.modal = false
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
      this.searchData = JSON.parse(JSON.stringify(this.startSearchData))
      this.$refs.pagingTable.pageRefresh(this.searchData)
    },
    /* table操作栏 */
    tableBtnClick (item, type) {
      if (type === 'approval') {
        // this.modalTitle = '入库'
        // this.ApplyData = item
        // this.purchaseSee(item.id)
        // this.modal = true
      }
    },
    // 入库
    async storage () {
      const _this = this
      console.log(_this.addGoods)
      // let res = await _this.$axios('purchaseOrder/putInStorage', {order_id: this.ApplyData.id, data: data})
      // if (res.code === 1) {
      //   this.pageRefresh()
      //   this.$Modal.remove()
      //   this.$set(this.$data, 'modal', false)
      //   this.instance('success', res)
      // } else {
      //   this.instance('error', res)
      // }
    }
  }
}
</script>
<style lang="scss" scoped>
</style>
