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
        <div class="multiple_search_input" v-if="role=='boss'||role=='storage'||role=='store'" @click="orderApply">
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
          <div class="turn-page">
            <icon class="iconfont iconsdf" :class="{iconColor:page<2}" @click="pageChange('sub')"></icon>
          </div>
          <div class="center flex_1">
            <input type="number" class="page-input" v-model="page" @change="inputChange">
            <span>/{{pages}}页</span>
            <span style="margin-left: 16px">共{{total_count}}条</span>
          </div>
          <div class="turn-page">
            <icon class="iconfont iconxiangyoujiantou" :class="{iconColor:page>=pages}" @click="pageChange('add')"></icon>
          </div>
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
                  <div class="item item4">
                    {{item.status_name}}
                    <icon class="iconfont iconshenhe" v-if="(role=='purchase'||role=='boss')&&item.status=='apply'&&item.verify_status==5"></icon>
                    <icon class="iconfont iconshenhe" v-if="role=='boss'&&item.status=='apply'&&item.verify_status==1"></icon>
                  </div>
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
        statusIndex: 1,
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
            name: '已采购',
            status: 'buy'
          },
          {
            name: '已完成',
            status: 'done'
          },
          {
            name: '已拒绝',
            status: 'deny'
          }
        ],
        companyIndex:0,
        companyList:[],
        total_count: 0,
        page: 1,
        pages: 1
      }
    },
    onShow() {
      this.init()
    },
    mounted(){
      wx.hideTabBar()
    },
    computed:{
      tab_show_pages(){
        return this.$store.state.role!=='purchase'
          ?'tab_show_pages'
          :''
      }
    },
    methods:{
      init(){
        this.total_count = 0
        this.role=this.$store.state.role
        this.companyList = JSON.parse(JSON.stringify(this.$store.state.initData.company))
        this.companyList.unshift({
          id:'',
          name: '全部'
        })
        this.getList()
      },
      getList() {
        const _this = this
        _this.$ajax('purchaseOrder/getLists', {
          page: _this.page,
          limit: 20,
          status: _this.statusList[_this.statusIndex].status,
          company: _this.companyList[_this.companyIndex].id
        },function (res) {
          _this.purchaseOrderList = _this.dataFilter(res.data)
          _this.total_count = res.count
          _this.pages = Math.ceil(res.count/20)
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
      pageChange(type){
        if(type === 'sub' && this.page>1){
          this.page--
        } else if(type === 'add' && this.page < this.pages) {
          this.page++
        } else {
          return
        }
        this.getList()
      },
      inputChange(e){
        let v = e.mp.detail.value
        if(v<this.pages&&v>0){
          this.getList()
        }
      },
      bindStatusPickerChange (e) {
        this.statusIndex = e.mp.detail.value
        this.page = 1
        this.getList()
      },
      bindCompanyPickerChange(e) {
        this.companyIndex = e.mp.detail.value
        this.page = 1
        this.getList()
      }
    }
  }
</script>

<style scoped lang="scss">
  .iconColor{
    color: #999 !important;
  }
    .search_box{
      width: 100%;
      position: absolute;
      top: 14px;
      left: 0;
      z-index: 999;
      display: flex;
      justify-content: space-around;
    }
  .iconshenhe{
    color: #1da3ff;
  }
</style>
