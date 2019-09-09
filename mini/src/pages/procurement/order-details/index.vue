<template>
  <div class="pages">
    <i-toast id="toast" />
    <div class="detail-box" v-if="orderDetail">
      <div class="dataList">
        <div class="dataItem">
          <span class="dataItemLeft">订单编号：</span>
          <span class="dataItemRight">{{orderDetail.batch_no||'-'}}</span>
        </div>
        <div class="dataItem">
          <span class="dataItemLeft">申请人：</span>
          <span class="dataItemRight">{{orderDetail.uname||'-'}}</span>
        </div>
        <div class="dataItem">
          <span class="dataItemLeft">预估金额：</span>
          <span class="dataItemRight">{{orderDetail.estimated_amount||'0'}}元</span>
        </div>
        <div class="dataItem" v-if="orderDetail.total_amount">
          <span class="dataItemLeft">实购金额：</span>
          <span class="dataItemRight">{{orderDetail.total_amount}}元</span>
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
          <span class="dataItemRight .shenhe-btn" @click="orderEditor('apply')" v-if="orderDetail.status==='apply'">审核</span>
          <span class="dataItemRight .luru-btn" @click="orderEditor('pass')" v-if="orderDetail.status==='pass'">录入</span>
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
              <span>预估费用：{{item.estimated_money}}元</span>
              <span v-if="orderDetail.status != 'apply'&&orderDetail.status != 'pass'">实购数量：{{item.buy_amount}}{{item.unit}}</span>
              <span v-if="orderDetail.status != 'apply'&&orderDetail.status != 'pass'">实购花费：{{item.buy_money}}元</span>
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
    },
    methods:{
      getDetail() {
        const _this = this
        _this.$ajax('purchaseOrder/getDetailInfo',{order_id:_this.id},function (res) {
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
            path = '/pages/procurement/order-audit/main'
            break
          case 'pass':
            path = '/pages/procurement/order-entry/main'
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
