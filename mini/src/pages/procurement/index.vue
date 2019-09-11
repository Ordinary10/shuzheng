<template>
  <div class="pages TabBar_page" :class="tab_show">
    <i-toast id="toast" />
    <div class="mini-list">
      <div class="mini-list-item" @click="orderApply" v-if="role==='salesman'">
        <span>申请采购</span>
        <span class="center"></span>
        <span><i-icon type="enter" size="20"/></span>
      </div>
      <picker @change="bindPickerChange" :value="statusIndex" :range="statusList" range-key="name">
        <div class="picker mini-list-item">
          <span>订单状态：</span>
          <span class="center">{{statusList[statusIndex].name}}</span>
          <span><i-icon type="enter" size="20"/></span>
        </div>
      </picker>
    </div>
    <div class="results-box">
      <div class="results-header">
        共{{purchaseOrderList.length}}笔订单
      </div>
      <div class="results-list">
        <div class="not-results" v-show="purchaseOrderList.length===0">
          <img class="search-bgc" src="http://test.c.zdxrchina.com/images/wechat/search_bgc.png" alt="">
          <div class="not-results-text">没有相关信息哦</div>
        </div>
        <div class="show-results-list" v-show="purchaseOrderList.length>0">
          <div class="table-header list-item">
            <div class="item item1">申请人</div>
            <div class="item item2">门店</div>
            <div class="item item3">时间</div>
            <div class="item item4">状态</div>
          </div>
          <div class="list-item" v-for="(item,index) in purchaseOrderList" :key="index" @click="get_details(item)">
            <div class="item item1">{{item.uname}}</div>
            <div class="item item2">{{item.store_name}}</div>
            <div class="item item3">{{item.ctime}}</div>
            <div class="item item4">{{item.status_name}}</div>
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
        statusChoose: false,
        statusIndex: 0,
        role: '',
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
        ]
      }
    },

    created() {
    },
    onShow() {
      this.getList()
      this.role=this.$store.state.role
    },
    mounted(){
      wx.hideTabBar()
    },
    computed:{
      tab_show(){
        return this.$store.state.role==='admin'||this.$store.state.role==='other'
          ?'tab_show'
          :''
      }
    },
    methods:{
      getList() {
        const _this = this
        _this.$ajax('purchaseOrder/getLists',{status:_this.statusList[_this.statusIndex].status},function (res) {
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
      bindPickerChange (e) {
        this.statusIndex = e.mp.detail.value
        this.getList()
      }
    }
  }
</script>

<style lang="wxss">
  page{
    background-color: #F3F3F3;
  }
</style>
<style scoped lang="scss">
  .pages{
    .center{
      color: #04A9F5;
    }
    .results-box{
      background-color: white;
      border-radius: 20px 20px 0 0;
      color: #000;
      font-size: 15px;
      display: flex;
      flex-direction: column;
      flex: 1;
      .results-header{
        font-size: 14px;
        text-align: center;
        border-bottom: 1px solid #D9D9D9;
        line-height: 35px;
      }
      .results-list{
        display: flex;
        flex-direction: column;
        flex: 1;
        .not-results{
          margin: auto;
          .search-bgc{
            width: 198px;
            height: 100px;
            margin-bottom: 30px;
          }
          .not-results-text{
            text-align: center;
          }
        }
        .show-results-list{
          font-size: 15px;
          .table-header{
            background-color: #F0F1F4;
            color: #8A98AC;
          }
          .list-item{
            display: flex;
            width: 100%;
            height: 39px;
            border-bottom: 1px solid #D9D9D9;
            .item{
              line-height: 39px;
              white-space: nowrap;
              word-wrap: break-word;
              word-break: break-all;
              text-overflow:ellipsis;
              overflow: hidden;
            }
            .item1{
              width: 20%;
              text-indent: 20px;
            }
            .item2{
              width: 35%;
              text-indent: 10px;
            }
            .item3{
              width: 25%;
            }
            .item4{
              width: 20%;
            }
            &:hover{
              background-color: #F0F1F4;
              color: #8A98AC;
            }
          }
        }
      }
    }
  }
</style>
