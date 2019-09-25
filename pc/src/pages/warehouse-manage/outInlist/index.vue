<template>
  <div>
    <search>
      <div class="search-box">
        <!--搜索输入框-->
        <!--  <Input class="search-input" v-model="searchData.name" size="large" placeholder="请输入申请人姓名" />-->
        <Select v-model="searchData.type" class="search-input" size="large" placeholder="请选择角色">
          <Option :value="1">入库列表</Option>
          <Option :value="-1">出库列表</Option>
        </Select>
        <!--搜索按钮-->
        <div class="search-submit">
          <Tooltip content="更多搜索条件" placement="bottom-start">
            <Button class="search-btn " size="large" icon="ios-options-outline" type="primary"
                    @click.native="isShow=!isShow"></Button>
          </Tooltip>
          <Button class="search-btn " size="large" icon="md-search" type="primary" @click.native="search"></Button>
          <Button class="refresh-btn search-btn" size="large" icon="md-refresh" type="info"
                  @click.native="refresh"></Button>
        </div>
        <!--常用操作按钮-->
        <div class="commonly-used-btn-box">
          <Tooltip content="出库" placement="bottom-start">
            <Button class="commonly-used-btn" type="error" size="large" icon="ios-add-circle-outline" @click="out"
                    style="font-size: 18px"></Button>
          </Tooltip>
          <Tooltip content="入库" placement="bottom-start">
            <Button class="commonly-used-btn" type="success" size="large" icon="ios-add-circle-outline" @click="add"
                    style="font-size: 18px"></Button>
          </Tooltip>
        </div>
        <!--更多操作-->
        <div class="redundant-btn" v-if="redundantList && redundantList.length>0">
          <Dropdown>
            <Button type="primary" size="large" @mouseout.native="iconType='md-arrow-dropdown'" @mouseover.native="iconType='md-arrow-dropup'">
              更多操作
              <Icon :type="iconType" />
            </Button>
            <DropdownMenu slot="list">
              <DropdownItem v-if="item.isShow === true" v-for="(item,index) in redundantList" :key="item.type" @click.native="redundant(index)">{{item.label}}</DropdownItem>
            </DropdownMenu>
          </Dropdown>
        </div>
      </div>
    </search>
    <div class="content-block">
      <paging-table ref="pagingTable" :config="config" :searchData="searchData"></paging-table>
    </div>
    <!--入库-->
    <Modal
      v-model="inModal"
      :title="modalTitle"
      :width='1200'
      class-name="vertical-center-modal"
    >
      <div class="card-body">
        <div style="margin: 5px 0;overflow: hidden">
          <Button style="float: right" type="primary" size="large" @click="addGood">添加入库商品</Button>
        </div>
        <Divider/>
        <Form :model="item" :rules="rule" :label-width="60" ref="inForm" v-for="(item,index) in addGoods"
              :key="item.batch_number">
          <Col span="4">
            <FormItem label="批次号">
              <Input v-model="item.batch_number" type="text" placeholder="请输入批次号" disabled></Input>
            </FormItem>
          </Col>
          <Col span="5">
            <FormItem label="商品" prop="cascaderList">
              <Cascader ref="inFormCascader" :data="goodsData" placeholder="请选择商品" @mouseenter.native="selectGoodsBefore(item.serial)"
                        @on-change="selectGoods"></Cascader>
            </FormItem>
          </Col>
          <Col span="5">
            <FormItem label="单位" prop="unit">
              <Select v-model="item.unit" class="search-input" size="large" placeholder="请选择单位">
                <Option :value="i.id" v-for="i of item.unitData" :key="i.name">
                  {{i.name}}
                </Option>
              </Select>
            </FormItem>
            <FormItem label="规格" prop="specs" v-if="item.unit===1 && item.unitData.length===2">
              <Input v-model="item.specs" type="text"
                     :placeholder="`请填写规格(1${item.unitData[0].name}=?${item.unitData[1].name})`"></Input>
            </FormItem>
          </Col>
          <Col span="5">
            <FormItem label="库位" prop="locator">
              <Input v-model="item.locator" type="text" placeholder="请填写入库位置"></Input>
            </FormItem>
          </Col>
          <Col span="4">
            <FormItem label="数量" prop="num">
              <Input v-model="item.num" type="text" placeholder="请填写入库数量"></Input>
            </FormItem>
          </Col>
          <Col span="1" style="display: flex;justify-content: center">
            <div class="iconfont icon_delete iconshanchuqq" @click="remove(item)"></div>
          </Col>
        </Form>
        <Divider/>
        <Form :model="formItem" :label-width="60">
          <FormItem label="备注">
            <Input v-model="formItem.remark" type="textarea" :autosize="{minRows: 2,maxRows: 5}"
                   placeholder="请输入备注"></Input>
          </FormItem>
          <FormItem label="凭证">
            <img-upload ref="imgUpload" :config="ImgConfig"></img-upload>
          </FormItem>
        </Form>
      </div>
      <div slot="footer">
        <Button type="text" size="large" @click="cancel">取消</Button>
        <Button type="primary" size="large" @click="handleSubmit">{{modalTitle}}</Button>
      </div>
    </Modal>
    <!--出库-->
    <Modal
      v-model="outModal"
      :title="modalTitle"
      :width='1200'
      class-name="vertical-center-modal"
    >
      <div class="card-body">
        <div style="margin: 5px 0;overflow: hidden">
          <Button style="float: right" type="primary" size="large" @click="OutGood">添加出库商品</Button>
        </div>
        <Divider/>
        <Form :model="item" :rules="rule" :label-width="65" ref="outForm" v-for="(item,index) in addGoodsOut"
              :key="item.serial">
          <Col span="6">
            <FormItem label="批次号"  prop="batch_number">
              <Input v-model="item.batch_number" :maxlength="20" type="text" placeholder="请输入批次号" ></Input>
            </FormItem>
          </Col>
          <Col span="6">
            <FormItem label="商品" prop="cascaderList">
              <Cascader ref="outFormCascader" :data="goodsData" placeholder="请选择商品" @mouseenter.native="selectGoodsBefore(item.serial)"
                        @on-change="selectGoods"></Cascader>
            </FormItem>
          </Col>
          <Col span="6">
            <FormItem label="单位" prop="unit">
              <Select v-model="item.unit" class="search-input" size="large" placeholder="请选择单位">
                <Option :value="i.id" v-for="i of item.unitData" :key="i.name">
                  {{i.name}}
                </Option>
              </Select>
            </FormItem>
          </Col>
          <Col span="5">
            <FormItem label="数量" prop="outNum">
              <Input v-model="item.outNum" type="text" placeholder="请填写出库数量"></Input>
            </FormItem>
            <FormItem label="库存" v-show="item.stock !== ''">
              <Input v-model="item.stock" type="text" disabled></Input>
            </FormItem>
          </Col>
          <Col span="1" style="display: flex;justify-content: center">
            <div class="iconfont icon_delete iconshanchuqq" @click="outRemove(item)"></div>
          </Col>
        </Form>
        <Divider/>
        <Form :model="formItem" :label-width="60">
          <FormItem label="备注">
            <Input v-model="formItem.remark" type="textarea" :autosize="{minRows: 2,maxRows: 5}"
                   placeholder="请输入备注"></Input>
          </FormItem>
          <FormItem label="凭证">
            <img-upload ref="imgUpload" :config="ImgConfig"></img-upload>
          </FormItem>
        </Form>
      </div>
      <div slot="footer">
        <Button type="text" size="large" @click="cancel">取消</Button>
        <Button type="primary" size="large" @click="handleSubmit">{{modalTitle}}</Button>
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
      inModal: false,
      outModal: false,
      iconType: 'md-arrow-dropdown',
      modalTitle: '',
      statusData: '',
      goodsData: [],
      // 添加商品的信息条  入库
      addGoods: [],
      // 添加商品的信息条  出库
      addGoodsOut: [],
      // 联级选择商品时,临时用来存储当前信息条的编号
      currentSerial: '',
      // 接口获得的所有商品列表 用于表格根据商品id获得名称
      AllGoodsData: [],
      rule: {
        unit: [
          {required: true, type: 'number', message: '请选择单位', trigger: 'blur'}
        ],
        batch_number: [
          {required: true, message: '请填写批次号', trigger: 'blur'},
          {validator: (rule, value, callback) => {
            if (!Number(value)) {
              return callback(new Error('请填写批次号(数字16位)'))
            } else if (value.length !== 16) {
              return callback(new Error('请填写批次号(数字16位)'))
            } else {
              callback()
            }
          },
          trigger: 'blur'}
        ],
        locator: [
          {required: true, message: '请填写入库位置', trigger: 'blur'}
        ],
        specs: [
          {validator: this.$validateFun.Znumber, required: true, trigger: 'blur'}
        ],
        cascaderList: [{
          trigger: 'change',
          validator: (rule, value, callback) => {
            // console.log(value)
            if (!value.length) {
              return callback(new Error('请选择商品'))
            } else {
              callback()
            }
          },
          required: true,
          type: 'array'
        }
        ],
        num: [
          {validator: this.$validateFun.Fnumber, required: true, trigger: 'blur'}
        ],
        outNum: [
          {validator: this.$validateFun.Fnumber, required: true, trigger: 'blur'},
          {validator: (rule, value, callback) => {
            let stock = +this.addGoodsOut[+this.currentSerial].stock
            if (stock < +value) {
              callback(new Error('超出库存'))
            } else {
              callback()
            }
          },
          trigger: 'blur'
          }
        ]
      },
      formItem: {
        remark: ''
      },
      config: {
        fun: 'Checkout/goodsInventoryList',
        columns: [
          {
            key: 'uname',
            title: '操作人',
            align: 'center'
          },
          {
            title: '详情',
            align: 'center',
            render: (h, params) => {
              let detail = params.row.detail || []
              let showData = ''
              detail.forEach(e => {
                let goods = this.$common.recursiveQuery(this.AllGoodsData, 'id', e.goods_id) || {}
                showData += `批次号:${e.batch_number}，商品:${goods.name}，单位:${goods.lower_unit}，库位:${e.locator}，数量:${e.num}<br>`
              })
              return h('Tooltip', {
                props: {
                  placement: 'right',
                  maxWidth: '450'
                }
              }, [
                h('Button', {
                  domProps: {
                    innerHTML: detail.length + '项'
                  }
                }), // 表格中的数据
                h('div', {
                  slot: 'content',
                  style: {
                    whiteSpace: 'normal',
                    wordBreak: 'break-all'
                  },
                  domProps: {
                    innerHTML: showData
                  }
                })
              ])
            }
          },
          {
            key: 'remark',
            title: '备注',
            align: 'center'
          },
          {
            title: '状态',
            align: 'center',
            render: (h, params) => {
              if (params.row.type_name === '入库') {
                return <span class="green-color">入库</span>
              } else {
                return <span class="redtext">出库</span>
              }
            }
          },
          {
            key: 'ctime',
            title: '操作时间',
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
        type: 1
      },
      startSearchData: {
        name: '',
        type: 1
      },
      ImgConfig: {
        oldImg: []
      },
      redundantList: [
        {
          type: 'exportList',
          label: '导出列表',
          isShow: true,
          exportFun: 'car/exportCars'
        }
      ]
    }
  },
  components: {},
  created () {
    this.init()
  },
  mounted () {
  },
  methods: {
    async init () {
      let _this = this
      let res = await _this.$axios('goods/getTypeWithGoods', {})
      function test (data) {
        data.forEach(e => {
          e.label = e.type_name || e.name
          e.value = e.id
          e.children = e.goods || []
          // 如果有名称和单位 说明该数据是商品而非类目 保存第二级商品信息1
          if (e.name && e.unit) {
            _this.AllGoodsData.push(e)
          }
          if (e.children.length) {
            test(e.children)
          }
        })
      }
      test(res.data)
      _this.goodsData = JSON.parse((JSON.stringify(res.data)))
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
    // 入库
    add () {
      this.inModal = true
      this.modalTitle = '入库'
      this.addGoods = []
      this.addGood()
      this.formItem.remark = ''
      this.ImgConfig.oldImg = []
    },
    // 添加入库商品条
    addGood () {
      // 自动填入批次号
      function test (num) {
        return num > 9 ? num : '0' + num
      }

      let date = new Date()
      let month = test(date.getMonth() + 1)
      let batch_number =
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
        batch_number,
        id: '',
        unit: '',
        unitData: [],
        locator: '',
        // 选择商品的结果数组 用于验证的
        cascaderList: '',
        // 选择两级单位用的规格
        specs: '',
        num: ''
      }
      this.addGoods.push(obj)
    },
    // 删除出库商品条
    remove (item) {
      this.addGoods.splice(this.addGoods.indexOf(item), 1)
    },
    outRemove (item) {
      this.addGoodsOut.splice(this.addGoodsOut.indexOf(item), 1)
    },
    // 选择完商品后回调
    selectGoods (e, selectedData) {
      if (this.inModal) {
        // 入库操作
        let goods = this.addGoods[+this.currentSerial]
        // 商品验证赋值
        goods.cascaderList = [selectedData]
        let selectedGoods = selectedData.pop()
        // console.log(selectedGoods)
        goods.id = selectedGoods.id
        if (selectedGoods.lower_unit === selectedGoods.unit) {
          goods.unitData = [{name: selectedGoods.unit, id: 2}]
          goods.unit = 2
        } else {
          goods.unitData = [{name: selectedGoods.unit, id: 1}, {name: selectedGoods.lower_unit, id: 2}]
        }
      } else {
        // 出库操作
        let goods = this.addGoodsOut[+this.currentSerial]
        // console.log(goods)
        // 商品验证赋值
        goods.cascaderList = [selectedData]
        let selectedGoods = selectedData.pop()
        // console.log(selectedGoods)
        goods.id = selectedGoods.id
        goods.unitData = [{name: selectedGoods.lower_unit, id: 2}]
        goods.unit = 2
        goods.stock = selectedGoods.stock
      }
    },
    // 准备选择商品
    selectGoodsBefore (id) {
      this.currentSerial = id
    },
    // 点击入库进行表单验证
    handleSubmit () {
      if (this.inModal && !this.addGoods.length) {
        this.$Message.error('请选择入库商品')
        return false
      }
      if (!this.inModal && !this.addGoodsOut.length) {
        this.$Message.error('请选择出库商品')
        return false
      }
      let arr = []
      // 两种from 一种入库 一种出库
      let formType
      if (this.inModal) {
        // 入库操作
        formType = 'inForm'
      } else {
        // 出库操作
        formType = 'outForm'
      }
      this.$refs[formType].forEach(form => {
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
        this.storage(this.inModal)
      } else {
        this.$Message.error('请填写相关内容')
      }
    },
    cancel () {
      // 关闭入库框
      this.inModal = false
      this.outModal = false
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
      }
    },
    // 调用接口  type为true时是入库
    async storage (type) {
      const _this = this
      let detail = []
      let fun
      if (type) {
        fun = 'Checkout/commodityWarehousing'
        // 入库
        _this.addGoods.forEach(e => {
          detail.push({
            goods_id: e.id,
            locator: e.locator,
            unit_num: e.num,
            num: e.num,
            batch_number: e.batch_number,
            unit_type: e.unit,
            specs: e.specs,
            flag: 1
          })
        })
      } else {
        fun = 'Checkout/goodsDecrease'
        //  出库
        _this.addGoodsOut.forEach(e => {
          detail.push({
            goods_id: e.id,
            batch_number: e.batch_number,
            num: e.outNum,
            unit_type: e.unit,
            flag: -1
          })
        })
      }
      let obj = {
        detail,
        remark: _this.formItem.remark,
        img: _this.$refs.imgUpload.getImgUrl(),
        type: type ? 1 : -1
      }
      // console.log(obj)
      // console.log(_this.$refs.imgUpload.getImgUrl())
      let res = await _this.$axios(fun, obj)
      if (res.code === 1) {
        _this.cancel()
        this.search()
        // 如果是出库操作 侧记到库存需要重新初始化数据
        !type && _this.init()
        this.instance('success', res)
      } else {
        this.instance('error', res)
      }
    },
    // 出库
    out () {
      this.outModal = true
      this.modalTitle = '出库'
      this.addGoodsOut = []
      this.OutGood()
      this.formItem.remark = ''
      this.ImgConfig.oldImg = []
      this.$nextTick(() => {
        this.$refs.outForm[0].resetFields()
        this.$refs.outFormCascader[0].currentValue = []
        this.$refs.outFormCascader[0].selected = []
        this.$refs.outFormCascader[0].tmpselected = []
      })
    },
    // 添加出库商品条1
    OutGood () {
      let obj = {
        batch_number: '',
        // 商品条序号
        serial: this.addGoodsOut.length,
        id: '',
        unit: '',
        stock: '',
        unitData: [],
        // 选择商品的结果数组 用于验证的
        cascaderList: [],
        // 选择出库的数量
        outNum: ''
      }
      this.addGoodsOut.push(obj)
    },
    /* 更多操作 */
    redundant (index) {
      let d = this.redundantList[index]
      if (d.isExcelModal === true) {
      } else if (d.type === 'exportList' && d.exportFun) {
        const _this = this
        let str = ''
        Object.keys(_this.searchData).forEach(key => {
          str += `&params[${key}]=${_this.searchData[key]}`
        })
        window.open(`${_this.$common.API_PATH}/${d.exportFun}?token=${sessionStorage.getItem('token')}${str}`)
      } else {
        alert(d.label)
      }
    }
  }
}
</script>
<style lang="scss" scoped>
</style>
