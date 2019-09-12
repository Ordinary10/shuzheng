<template>
  <div class="pages" :class="tab_show_pages">
    <i-toast id="toast" />
    <div class="pages_header">
      <div class="pages_top_modified"></div>
      <div class="search_box">
        <div class="multiple_search_input">
          <div class="left nowrap">{{statusList[statusIndex].name=='全部'?'状态':statusList[statusIndex].name}}</div>
          <picker @change="bindStatusPickerChange" :value="statusIndex" :range="statusList" range-key="name">
            <div>
                <icon class="iconfont iconjiantou right"></icon>
            </div>
          </picker>
        </div>
        <div class="multiple_search_input" v-if="companyList.length>0">
          <div class="left nowrap">{{companyList[companyIndex].name=='全部'?'门店':companyList[companyIndex].name}}</div>
          <picker @change="bindCompanyPickerChange" :value="companyIndex" :range="companyList" range-key="name">
            <div>
              <icon class="iconfont iconjiantou right"></icon>
            </div>
          </picker>
        </div>
        <div class="multiple_search_input" v-if="role==='salesman'" @click="orderApply">
          <div class="left nowrap" style="text-align: center">
            新增
          </div>
          <div>
            <icon class="iconfont iconadd right" style="text-align: left; min-width: 30px;"></icon>
          </div>
        </div>
      </div>
    </div>
    <div class="pages_container">
      <div class="results-box">
        <div class="results-header">
          共{{purchaseOrderList.length}}笔订单
        </div>
        <div class="results-list">
          <div class="not-results" v-if="purchaseOrderList.length===0">
            <img class="search-bgc" src="http://zucheguanjia.oss-cn-qingdao.aliyuncs.com/car/15682812084909.png" alt="">
            <div class="not-results-text">没有相关信息哦</div>
          </div>
          <div class="show-results-list" v-if="purchaseOrderList.length>0">
            <div class="table-header list-item">
              <div class="item item1">申请人</div>
              <div class="item item2">门店</div>
              <div class="item item3">时间</div>
              <div class="item item4">状态</div>
            </div>
            <div class="flex_1" style="height: 100px;">
              <scroll-view scroll-y style="height: 100%;">
                <div class="list-item" v-for="(item,index) in purchaseOrderList" :key="index" @click="get_details(item)">
                  <div class="item item1">{{item.uname}}</div>
                  <div class="item item2">{{item.store_name}}</div>
                  <div class="item item3">{{item.ctime}}</div>
                  <div class="item item4">{{item.status_name}}</div>
                </div>
              </scroll-view>
            </div>
          </div>
        </div>
      </div>
    </div>
    <LtabBar nowPath="/pages/procurement/main" key="procurement"></LtabBar>
  </div>
</template>
<script>
  import LtabBar from '@/components/LtabBar/index'
  export default {
    components: {LtabBar},

    data() {
      return {
        purchaseOrderList: [],
        role: '',
        statusIndex: 0,
        statusList: [
          {
            name: '全部',
            status: ''
          },
          {
            name: '待审核',
            status: 'apply'
          },
          {
            name: '待采购',
            status: 'pass'
          },
          {
            name: '待入库',
            status: 'buy'
          },
          {
            name: '已完成',
            status: 'done'
          },
          {
            name: '拒绝',
            status: 'deny'
          }
        ],
        companyIndex:0,
        companyList:[]
      }
    },
    created() {
    },
    onShow() {
      this.init()
      this.role=this.$store.state.role
    },
    mounted(){
      wx.hideTabBar()
    },
    computed:{
      tab_show_pages(){
        return this.$store.state.role==='admin'||this.$store.state.role==='other'
          ?'tab_show_pages'
          :''
      }
    },
    methods:{
      init(){
        const _this = this
        _this.$common.getPageInfo(['company']).then(res => {
          _this.companyList = res.data.company
          _this.companyList.unshift({
            id:'',
            name: '全部'
          })
          _this.getList()
        })
      },
      getList() {
        const _this = this
        _this.$ajax('purchaseOrder/getLists', {
          status: _this.statusList[_this.statusIndex].status,
          company: _this.companyList[_this.companyIndex].id
        },function (res) {
          _this.purchaseOrderList = _this.dataFilter(res.data)
        })
      },
      dataFilter (data) {
        let arr = []
        if(data&&data.length>0){
          data.forEach(val => {
            val.ctime = val.ctime.split(' ')[0]
            val.up_time = val.up_time.split(' ')[0]
            arr.push(val)
          })
        }
        return arr
      },
      get_details(item) {
        wx.navigateTo({url:`/pages/procurement/order-details/main?id=${item.id}`})
      },
      orderApply() {
        wx.navigateTo({url:'/pages/procurement/order-apply/main'})
      },
      bindStatusPickerChange (e) {
        this.statusIndex = e.mp.detail.value
        this.getList()
      },
      bindCompanyPickerChange(e) {
        this.companyIndex = e.mp.detail.value
        this.getList()
      }
    }
  }
</script>

<style scoped lang="scss">
    .search_box{
      width: 100%;
      position: absolute;
      top: 14px;
      left: 0;
      z-index: 999;
      display: flex;
      justify-content: space-around;
    }
</style>
