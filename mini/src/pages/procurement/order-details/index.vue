<template>
  <div class="pages">
    <i-toast id="toast" />
    <div class="detail-box" v-if="orderDetail">
      <div class="divider-title">
        <divider content="基本信息" :css="{'font-size': '17px'}"></divider>
      </div>
      <div class="dataList">
        <div class="dataItem">
          <span class="dataItemLeft">订单编号：</span>
          <span class="dataItemright">{{orderDetail.batch_no||'-'}}</span>
        </div>
        <div class="dataItem">
          <span class="dataItemLeft">申请人：</span>
          <span class="dataItemright">{{orderDetail.uid||'-'}}</span>
        </div>
        <div class="dataItem">
          <span class="dataItemLeft">商品总数：</span>
          <span class="dataItemright">{{orderDetail.total_count||'0'}}</span>
        </div>
        <div class="dataItem">
          <span class="dataItemLeft">商品总价：</span>
          <span class="dataItemright">{{orderDetail.total_amount||'0'}}</span>
        </div>
        <div class="dataItem">
          <span class="dataItemLeft">申请时间：</span>
          <span class="dataItemright">{{orderDetail.ctime||'-'}}</span>
        </div>
        <div class="dataItem">
          <span class="dataItemLeft">状态：</span>
          <span class="dataItemright">{{orderDetail.status}}</span>
        </div>
      </div>
      <div class="divider-title">
        <divider content="商品详情" :css="{'font-size': '17px'}"></divider>
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
    padding-top: 30rpx;
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
    .detail-box{
      background-color: white;
      box-shadow:0 0 38rpx 11rpx rgba(0, 0, 0, 0.11);
      border-radius:40rpx 40rpx 0 0;
      padding: 0 40rpx;
      .divider-title{
        padding: 40rpx 0 16rpx 0;
      }
    }
  }
</style>
