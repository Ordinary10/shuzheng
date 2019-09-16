<template>
  <div class="pages">
    <i-toast id="toast" />
    <div class="detail-box" v-if="orderDetail">
      <div class="dataList">
        <div class="dataItem">
          <span class="dataItemLeft">申请人：</span>
          <span class="dataItemRight">{{orderDetail.uname||'-'}}</span>
        </div>
        <div class="dataItem">
          <span class="dataItemLeft">出库商品数：</span>
          <span class="dataItemRight">{{orderDetail.num||'0'}}</span>
        </div>
        <div class="dataItem">
          <span class="dataItemLeft">申请时间：</span>
          <span class="dataItemRight">{{orderDetail.ctime||'-'}}</span>
        </div>
        <div class="dataItem">
          <span class="dataItemLeft">申请门店：</span>
          <span class="dataItemRight">{{orderDetail.store_name||'-'}}</span>
        </div>
        <div class="dataItem">
          <span class="dataItemLeft">状态：</span>
          <span class="dataItemRight">{{orderDetail.status_name}}</span>
          <span class="dataItemRight .shenhe-btn" @click="orderEditor('apply')" v-if="orderDetail.status==='apply'&&role==='admin'">审核</span>
          <span class="dataItemRight .luru-btn" @click="orderEditor('pass')" v-if="orderDetail.status==='pass'&&role==='admin'">配货</span>
          <span class="dataItemRight .luru-btn" @click="orderEditor('distribute')" v-if="orderDetail.status==='distribute'&&(role==='chef'||role==='admin')">签收</span>
        </div>
      </div>
      <div class="goods-list" v-if="orderDetail.detail_info.length>0">
        <div class="divider-title">
          <divider content="出库商品" :css="{'font-size': '17px'}"></divider>
        </div>
        <div class="goods-item" v-for="(item,index) in orderDetail.detail_info" :key="item.id">
          <i-card :title="item.name" extra=" " thumb=" ">
            <div slot="content" class="content">
              <span>申请出库量：{{item.apply_amount || 0}}</span>
              <span>实际出库量：{{item.delivery_amount || 0}}</span>
            </div>
          </i-card>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
  import divider from '@/components/divider'
  export default {
    components: {divider},

    data() {
      return {
        id: '',
        orderDetail: null,
        role: ''
      }
    },
    created() {
    },
    onShow() {
      this.orderDetail = null
      this.id = this.$root.$mp.query.id
      this.role = this.$store.state.role
      this.getDetail()
    },
    mounted(){
    },
    methods:{
      getDetail() {
        const _this = this
        _this.$ajax('checkout/getDetailInfo',{order_id:_this.id},function (res) {
          _this.orderDetail = _this.dataFilter(res.data)
          // console.log(_this.orderDetail)
        })
      },
      dataFilter (data) {
        if(data){
          Object.keys(data).forEach(key => {
            if(key == 'ctime' || key =='up_time') {
              data[key] = data[key].split(' ')[0]
            }
          })
        }
        return data
      },
      orderEditor(status){
        let path = ''
        switch (status) {
          case 'apply':
            path = '/pages/outbound/order-audit/main'
            break
          case 'pass':
            path = '/pages/outbound/order-entry/main'
            break
          case 'distribute':
            path = '/pages/outbound/order-sign-off/main'
            break
        }
        const _this = this
        wx.navigateTo({
          url:`${path}?order_id=${_this.id}`
        })
      }
    }
  }
</script>

<style lang="wxss">
  page{
    background-color: #F3F3F3;
    height: 100%;
  }
</style>
<style scoped lang="scss">
  .pages{
    height: 100%;
    box-sizing: border-box;
    padding-top: 30rpx;
    display: flex;
    flex-direction: column;
    .detail-box{
      flex: 1;
      width: 100%;
      background-color: white;
      box-sizing: border-box;
      box-shadow:0 0 38rpx 11rpx rgba(0, 0, 0, 0.11);
      border-radius:40rpx 40rpx 0 0;
      padding: 0 40rpx;
      overflow: scroll;
      .divider-title{
        padding: 40rpx 0 16rpx 0;
      }
      .dataList{
        width: 100%;
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        .dataItem{
          width: 100%;
          min-height: 43px;
          box-sizing: border-box;
          border-bottom: 1px solid #D9D9D9;
          color: #000;
          font-size: 16px;
          display: flex;
          align-items: center;
          position: relative;
          .dataItemLeft{
            color: #8A98AC;
          }
          .dataItemRight{
            margin-left: 8px;
          }
        }
      }
      .goods-list{
        padding-bottom: 12px;
        .goods-item{
          margin: 12px 0;
          .content{
            display: flex;
            flex-wrap: wrap;
            span{
              width: 50%;
              padding: 8px;
              box-sizing: border-box;
            }
          }
          .status-caozuo{
            font-size: 16px;
            padding-bottom: 12px;
          }
        }
      }
    }
  }
</style>
