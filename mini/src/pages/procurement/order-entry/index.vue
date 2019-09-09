<template>
  <div class="pages">
    <i-toast id="toast" />
    <div class="detail-box" v-if="orderDetail">
      <div class="goods-list" v-if="orderDetail.detail.length>0">
        <div class="divider-title">
          <divider content="商品录入" :css="{'font-size': '17px'}"></divider>
        </div>
        <div class="goods-item" v-for="(item,index) in orderDetail.detail" :key="item.id">
          <i-card :title="item.name" extra=" " thumb=" ">
            <div slot="content" class="content">
              <div class="content-item">预购数量：{{item.apply_amount}}{{item.unit}}</div>
              <div class="content-item">预估花费：{{item.estimated_money}}元</div>
              <div class="content-item">
                <span>实购数量：</span>
                <input type="number" v-model="item.buy_amount">
              </div>
              <div class="content-item">
                <span>实购花费：</span>
                <input type="digit" v-model="item.buy_money">
              </div>
              <picker style="width: 100%" :data-index="index" @change="bindPickerChange" :value="supplierIndex" :range="supplierList" range-key="name">
                <div class="picker content-item" style="width: 100%">
                  <span>供应商：</span>
                  <span class="center">{{item.supplier||'请选择供应商'}}</span>
                  <span><i-icon type="enter" size="20"/></span>
                </div>
              </picker>
            </div>
          </i-card>
        </div>
      </div>
    </div>
    <div class="entry-submit">
      <button type="button" @click="submitEntry" class="login_btn">录入采购</button>
    </div>
  </div>
</template>
<script>
  import divider from '@/components/divider'
  export default {
    components: {divider},

    data() {
      return {
        order_id: '',
        orderDetail: null,
        supplierIndex: 0,
        supplierList: null
      }
    },
    created() {
    },
    onShow() {
      this.orderDetail = null
      this.order_id = this.$root.$mp.query.order_id
      this.init()
      this.getDetail()
    },
    mounted(){
    },
    methods:{
      init(){
        const _this = this
        _this.$common.getPageInfo(['supplier']).then(res => {
          _this.supplierList = res.data.supplier
        })
      },
      getDetail() {
        const _this = this
        _this.$ajax('purchaseOrder/getDetailInfo',{order_id:_this.order_id},function (res) {
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
            if(key=='detail') {
              for(let item of data.detail){
                item.buy_amount = item.apply_amount
                item.buy_money = item.estimated_money
              }
            }
          })
        }
        return data
      },
      bindPickerChange (e) {
        this.supplierIndex = e.mp.detail.value
        let index = e.mp.currentTarget.dataset.index
        this.orderDetail.detail[index].supplier = this.supplierList[this.supplierIndex].name
        this.orderDetail.detail[index].supplier_id = this.supplierList[this.supplierIndex].id
        this.orderDetail.detail = JSON.parse(JSON.stringify(this.orderDetail.detail))
      },
      submitEntry(){
        const _this = this
        let detail = []
        for(let v of _this.orderDetail.detail){
          detail.push({
            detail_id: v.id,
            buy_amount: v.buy_amount,
            buy_money: v.buy_money,
            supplier_id: v.supplier_id
          })
        }
        let data = {
          order_id: _this.order_id,
          data: detail
        }
        _this.$ajax('purchaseOrder/buy',data,function (res) {
          _this.$common.success_tip('录入成功',function () {
            wx.navigateBack()
          })
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
    padding: 15px 0 40px 0;
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
            .content-item{
              width: 50%;
              padding: 8px;
              box-sizing: border-box;
              display: flex;
              align-items: center;
              .center{
                flex: 1;
                color: #04A9F5;
              }
              input{
                flex: 1;
                border-bottom: 1px solid #eee;
                line-height: 40px;
              }
            }
          }
          .status-caozuo{
            font-size: 16px;
            padding-bottom: 12px;
          }
        }
      }
    }
    .entry-submit{
      position: fixed;
      bottom: 0;
      left: 0;
      background-color: white;
    }
    .login_btn{
      width:375px;
      font-size: 18px;
      color: white;
      line-height: 40px;
      background:#EC181F;
      box-shadow:0px 7px 15px 3px rgba(236, 31, 24, 0.35);
      border-radius:20px;
      &:hover{
        background: #EC5B5A;
      }
    }
  }
</style>
