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
      :width='980'
      class-name="vertical-center-modal"
    >
      <div><Button type="primary" size="large" @click="addGood">增加</Button></div>
      <Form :model="item" :label-width="65" class="card-body"  :ref="'form'+index" v-for="(item,index) in addGoods">
        <Col span="5">
          <FormItem label="批次号">
            {{addGoodsId}}
          <Input v-model="item.code" type="text" placeholder="请输入批次号" disabled></Input>
        </FormItem>
        </Col>
        <Col span="5">
        <FormItem label="商品">
          <Cascader :data="goodsData" @mouseenter.native="selectGoodsBefore(item.serial)"  @on-change="selectGoods"></Cascader>
        </FormItem>
        </Col>
        <Col span="4">
        <FormItem label="单位" >
            <Select v-model="item.unit" class="search-input" size="large" placeholder="请选择角色">
              <Option :value="String(i.unit)" v-for="i of unitData" :key="i.unit">
                {{i.unit}}
              </Option>
            </Select>
        </FormItem>
        </Col>
        <Col span="4">
        <FormItem label="库位" >
          <Input v-model="item.location" type="text" placeholder="请输入真实姓名"></Input>
        </FormItem>
        </Col>
        <Col span="4">
          <FormItem label="数量" >
            <Input v-model="item.num" type="text" placeholder=""></Input>
          </FormItem>
        </Col>
        <Col span="2">
          <div class="iconfont icon_delete iconshanchuqq" @click="remove(item)"></div>
        </Col>
<!--        <FormItem label="备注" prop="role">-->
<!--          <Select v-model="formItem.role" class="search-input" size="large" placeholder="请选择角色">-->
<!--            <Option :value="String(item.id)" v-for="item of roleData" :key="item.id">-->
<!--              {{item.name}}-->
<!--            </Option>-->
<!--          </Select>-->
<!--        </FormItem>-->
<!--        <FormItem label="所属公司" prop="dp_id">-->
<!--          <Select v-model="formItem.dp_id" class="search-input" size="large" placeholder="请选择所属公司">-->
<!--            <Option :value="String(item.id)" v-for="item of companyData" :key="item.id">-->
<!--              {{item.name}}-->
<!--            </Option>-->
<!--          </Select>-->
<!--        </FormItem>-->
      </Form>
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
      seeData: '',
      getDeta: '',
      selectedArr: [],
      modalTitle: '',
      statusData: '',
      goodsData: [],
      // 添加商品的信息条
      addGoods: [],
      // 联级选择商品时,临时用来存储当前信息条的编号
      addGoodsId: '',
      formItem: {
        unit: '',
        unitData: [],
        num: '',
        location: '',
        code: ''
      },
      rule: {
        name: [
          { required: true, message: '请输入商品名称', trigger: 'blur' }
        ]
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

    },
    // 选择完商品后回调
    selectGoods (e, selectedData) {
      let selectedGoods = selectedData.pop()
      let goods = this.addGoods[+this.addGoodsId]
      // 赋值
      goods.id = selectedGoods.id
    },
    // 准备选择商品
    selectGoodsBefore (id) {
      this.addGoodsId = id
    },
    // 点击入库进行表单验证
    handleSubmit () {
      // if (!this.selectedArr.length) {
      //   this.$Message.error('请选择出库商品')
      //   return false
      // }
      let arr = []
      this.selectedArr.forEach((data, key) => {
        let form = 'seeData' + data.id
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
    createForm (data) {
      if (this.selectedArr.includes(data)) {
        this.selectedArr = this.selectedArr.filter(function (ele) { return ele !== data })
      } else {
        this.selectedArr.push(data)
      }
      // this.seeData = []
      // for (let key in this.selectedArr) {
      //   for (let i in this.commoData) {
      //     if (this.commoData[i].name === this.selectedArr[key]) {
      //       this.seeData.push(this.commoData[i])
      //     }
      //   }
      // }
      // // 如果没有过滤则显示全部
      // if (!this.seeData[0]) {
      //   this.seeData = this.commoData
      // }
    },
    cancel () {
      // 关闭入库框
      alert(1)
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
        // this.selectedArr = []
      }
    },
    // // 请求采购单详情
    // async purchaseSee (order_id) {
    //   const _this = this
    //   let res = await _this.$axios('purchaseOrder/getDetailInfo', {order_id: order_id})
    //   this.seeData = res.data.detail
    //   function test (num) {
    //     return num > 9 ? num : '0' + num
    //   }
    // },
    // 入库
    async storage () {
      const _this = this
      let data = []
      _this.selectedArr.map((value, index, arry) => {
        data.push({
          place: value.place,
          bar_code: value.bar_code,
          detail_id: value.id})
      })
      console.log(data)
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
