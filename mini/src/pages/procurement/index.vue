<template>
  <div class="pages TabBar_page">
    <i-toast id="toast" />
    <div class="mini-list">
      <div class="mini-list-item">
        <div class="left">
          <span>订单状态</span>
        </div>
        <div class="center">
          <span>{{selectedStatusName}}</span>
        </div>
        <div class="right">
          <i-icon type="enter" color="#999" size="20" @click="statusChoose = !statusChoose" v-if="statusChoose"/>
          <i-icon type="unfold" color="#999" size="20" @click="statusChoose = !statusChoose" data-icon="is" v-if="!statusChoose"/>
        </div>
        <div class="status-choose-box" v-if="statusChoose">
          <span @click="statusChooseClick(index)" :class="{'status-selected':selectedStatus===item.status}" v-for="(item,index) in orderStatusList" :key="item.status">{{item.name}}</span>
        </div>
      </div>
    </div>
    <div class="results-box">
      <div class="results-header">
        共{{purchaseOrderList.length}}单采购
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
        orderStatusList: [
          {
            name: '全部',
            status: ''
          },
          {
            name: '申请中',
            status: 'apply'
          },
          {
            name: '采购中',
            status: 'pass'
          },
          {
            name: '待入库',
            status: 'buy'
          },
          {
            name: '已入库',
            status: 'done'
          },
          {
            name: '拒绝',
            status: 'deny'
          }
        ],
        selectedStatusName: '全部',
        selectedStatus: ''
      }
    },

    created() {
    },
    onShow() {
    },
    mounted(){
      wx.hideTabBar()
      this.getList()
    },
    methods:{
      getList() {
        const _this = this
        _this.$ajax('purchaseOrder/getLists','',function (res) {
          _this.purchaseOrderList = _this.dataFilter(res.data)
          console.log(_this.purchaseOrderList,)
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
      statusChooseClick(index) {
        let val = this.orderStatusList[index]
        this.selectedStatusName = val.name
        this.selectedStatus = val.status
        this.statusChoose = !this.statusChoose
      },
      get_details(item) {
        console.log(item)
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
  .status-selected{
    color: red !important;
  }
  .pages{
    .status-choose-box{
      position: absolute;
      background-color: white;
      z-index: 1000;
      top: 37px;
      right: 4px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      border: 1px solid rgba(230,230,230,0.8);
      border-radius: 4px;
      padding-bottom: 4px;
      span{
        width: 50px;
        text-align: center;
        line-height: 22px;
        font-size: 14px;
        margin: 8px 6px 0;
        background-color: #F3F3F3;
        color: #3FAAE2;
      }
    }
    .center{
      display: flex;
      justify-content: flex-end;
      span{
        color: #EC181F;
      }
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
            }
            .item1{
              width: 25%;
              text-indent: 20px;
            }
            .item2{
              width: 25%;
              text-indent: 10px;
            }
            .item3{
              width: 30%;
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
