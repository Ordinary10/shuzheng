<template>
  <div>
    <search>
      <div class="search-box">
        <!--搜索输入框-->
        <Input class="search-input" v-model="searchData.name" size="large" placeholder="请输入申请人姓名" />
        <DatePicker class="search-input top_sea"  size="large" type="date" placeholder="申请时间"  value='yyyy-MM-dd' @on-change="searchData.apply_time=$event" v-model="searchData.apply_time"></DatePicker>

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
          <Tooltip content="添加出库申请" placement="bottom-start">
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
      :footer-hide="true"
    >
      <see v-if="newseeData" :ApplyData="newseeData" :key="newseeData.id"></see>
    </Modal>
    <!--审核-->
    <Modal
      v-model="modal2"
      :title="modal1Title"
      :width='980'
      class-name="vertical-center-modal"
      :footer-hide="true"
    >
      <approval v-if="newseeData" :ApplyData="newseeData" :key="newseeData.id"></approval>
    </Modal>
    <!--配货-->
    <Modal
      v-model="modal3"
      :title="modal1Title"
      :width='980'
      class-name="vertical-center-modal"
      :footer-hide="true"
    >
      <distribution v-if="newseeData" :ApplyData="newseeData" :key="newseeData.id"></distribution>
    </Modal>
    <!--新增-->
    <Modal
      v-model="addisShow"
      :title="Title"
      :width='980'
      class-name="vertical-center-modal"
      :footer-hide="true"
    >
      <add v-if="addisShow" :key="addisShow"></add>
    </Modal>

  </div>
</template>

<script type="text/jsx">
  import see from './see'
  import add from './add'
  import approval from './approval'
  import distribution from './distribution'
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
        isShow: false,
        modal1: false,
        modal2:false,
        modal3:false,
        seeData:'',
        ApplyData:'',
        getDeta:'',
        modal1Title:'',
        newseeData:'',
        Title:'',
        addisShow:false,
        neworder_id:'',
        goodsData:'',
        config: {
          fun: 'checkout/getLists',
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
                  <i-button class="table-btn" type="primary" size="small"
                            nativeOnClick={this.tableBtnClick.bind(this, params.row, 'editor')}>查看
                  </i-button>
                  <i-button class="table-btn" type="error" size="small"
                            nativeOnClick={this.tableBtnClick.bind(this, params.row, 'approval')}>审核
                  </i-button>
                  <i-button className="table-btn" type="error" size="small"
                            nativeOnClick={this.tableBtnClick.bind(this, params.row, 'distribution')}>配货
                  </i-button>
                </div>
              }
            }
          ]
        },
        searchData: {
          name: '',
          apply_time: ''
        },
        startSearchData: {
          name: '',
          apply_time: ''
        },
        formItem: {
          id: '',   //id
          name: '', //申请人
          time:'',  //时间
          store:'', //门店
          type:'',  //加盟
          state:'', //状态
          remark:'' //备注
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
      add,
      see,
      approval,
      distribution,
    },
    created () {
    },
    mounted () {
    },
    methods: {

      add () {
        this.Title = '出库申请'
        this.addisShow =true
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
      modasear(res,is){
        if (res.code === 1) {
          this.pageRefresh()
          this.$Modal.remove()
          this.$set(this.$data,is,false);
          this.instance('success',res)
        }else {
          this.$Message.success({
            content: res.msg,
            duration: 2
          })
        }
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
        if (type =='approval'){
          this.modal1Title = '审核'
          this.ApplyData = item
          this.purchaseSee(item.id)
          this.modal2 = true
        } else if (type =='editor'){
          this.modal1Title = '查看编辑'
          this.ApplyData = item
          this.purchaseSee(item.id)
          this.modal1 = true
        }else if (type =='distribution'){
          this.modal1Title = '配货'
          this.ApplyData = item
          this.purchaseSee(item.id)
          this.modal3 = true
        }
      },

      //请求出库单详情
      async purchaseSee(order_id){
        const _this = this
        let res = await _this.$axios('checkout/getDetailInfo', {order_id: order_id})
        this.seeData = res.data.detail
        this.newseeData = res.data
        // this.commoData = JSON.parse(JSON.stringify(this.seeData))
      },
      instance (type,res) {
        let content = res.msg;
        switch (type) {
          case 'info':
            this.$Modal.info({
              title: '提示',
              content: content
            });
            break;
          case 'success':
            this.$Modal.success({
              title: '成功',
              content: content
            });
            setTimeout(() => {
              this.$Modal.remove();
            }, 2500);
            break;
          case 'warning':
            this.$Modal.warning({
              title: '警告',
              content: content
            });
            break;
          case 'error':
            this.$Modal.error({
              title: '错误',
              content: content
            });
            break;
        }
      }

    }
  }
</script>
<style lang="scss" scoped>

</style>
