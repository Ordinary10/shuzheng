<template>
  <div class="pages">
    <i-toast id="toast" />
    <div class="detail-box" v-if="orderDetail">
      <div class="dataList">
        <div class="dataItem">
          <span class="dataItemLeft">订单编号：</span>
          <span class="dataItemright">{{orderDetail.batch_no||'-'}}</span>
        </div>
        <div class="dataItem">
          <span class="dataItemLeft">申请人：</span>
          <span class="dataItemright">{{orderDetail.uname||'-'}}</span>
        </div>
        <div class="dataItem">
          <span class="dataItemLeft">预估金额：</span>
          <span class="dataItemright">{{orderDetail.estimated_amount||'0'}}元</span>
        </div>
        <div class="dataItem" v-if="orderDetail.total_amount">
          <span class="dataItemLeft">实购金额：</span>
          <span class="dataItemright">{{orderDetail.total_amount}}元</span>
        </div>
        <div class="dataItem">
          <span class="dataItemLeft">申请时间：</span>
          <span class="dataItemright">{{orderDetail.ctime||'-'}}</span>
        </div>
        <div class="dataItem">
          <span class="dataItemLeft">申请门店：</span>
          <span class="dataItemright">{{orderDetail.store_name||'-'}}</span>
        </div>
        <div class="dataItem">
          <span class="dataItemLeft">状态：</span>
          <span class="dataItemright">{{orderDetail.status_name}}</span>
        </div>
      </div>
      <div class="goods-list" v-if="orderDetail.detail.length>0">
        <div class="divider-title">
          <divider content="商品详情" :css="{'font-size': '17px'}"></divider>
        </div>
        <div class="goods-item" v-for="(item,index) in orderDetail.detail" :key="item.id">
          <i-card :title="item.name" extra=" " thumb=" ">
            <div slot="content" class="content">
              <span>预购数量：{{item.apply_amount}}{{item.unit}}</span>
              <span>预估花费：{{item.estimated_money}}元</span>
              <span>实购数量：{{item.buy_amount}}{{item.unit}}</span>
              <span>实购花费：{{item.buy_money}}元</span>
            </div>
            <div slot="footer">
              <div class="status-caozuo">
                <div v-if="orderDetail.status==='deny'"><span>已拒绝</span></div>
                <div v-else-if="orderDetail.status==='done'"><span>已入库</span></div>
                <div v-else-if="orderDetail.status==='buy'"><span class="put-in-storage">入库</span></div>
                <div v-else-if="orderDetail.status==='pass'"><span class="entry">采购中</span></div>
                <div v-else-if="orderDetail.status==='apply'"><span>审核中</span></div>
              </div>
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
        orderDetail: null
      }
    },
    created() {
    },
    onShow() {
      this.orderDetail = null
      this.id = this.$root.$mp.query.id
      this.getDetail()
    },
    mounted(){
      this.id = this.$root.$mp.query.id
      this.getDetail()
    },
    methods:{
      getDetail() {
        const _this = this
        _this.$ajax('purchaseOrder/getDetailInfo',{order_id:_this.id},function (res) {
          _this.orderDetail = _this.dataFilter(res.data)
          console.log(_this.orderDetail)
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
          min-height: 86rpx;
          box-sizing: border-box;
          border-bottom: 2rpx solid #D9D9D9;
          color: #000;
          font-size: 34rpx;
          line-height: 80rpx;
          position: relative;
          .dataItemLeft{
            color: #8A98AC;
            padding-right: 10rpx;
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
            justify-content: space-between;
            span{
              margin: 8px;
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
