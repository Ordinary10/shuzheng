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
        </div>
      </div>
      <div class="order-goods-list" v-if="orderDetail.detail.length>0">
        <div class="divider-title">
          <divider content="商品详情" :css="{'font-size': '17px'}"></divider>
        </div>
        <div class="goods-item" v-for="(item,index) in orderDetail.detail" :key="item.id">
          <goodsDetailsCard :goodsData="item" :orderStatus="orderDetail.status"></goodsDetailsCard>
        </div>
      </div>
    </div>
    <div class="details-operation-btns" v-if="orderDetail">
      <span class="large_btn_primary" @click="orderEditor('apply')" v-if="orderDetail.status==='apply'&&role === 'admin'">审核</span>
      <span class="large_btn_primary" @click="orderEditor('pass')" v-if="orderDetail.status==='pass'&&(role === 'salesman'||role==='admin')">录入</span>
    </div>
    <div class="audit-mask" v-if="maskIsShow" catchtouchmove="ture">
      <div class="mask-container">
        <div class="mask-title">
          审核
          <icon class="iconfont iconshanchuqq" @click="maskIsShow = false"></icon>
        </div>
        <div class="mask-content">
          <textarea name="remark" id="textarea" placeholder="请输入审核备注" v-model="remark"></textarea>
        </div>
        <div class="mask-footer-btn">
            <div class="audit-refused" @click="audit('deny')">拒绝</div>
          <div class="audit-through" @click="audit('pass')">通过</div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
  import divider from '@/components/divider'
  import goodsDetailsCard from '@/components/goods-details-card'
  export default {
    components: {divider,goodsDetailsCard},

    data() {
      return {
        id: '',
        orderDetail: null,
        role: '',
        maskIsShow: false,
        remark: ''
      }
    },
    created() {
    },
    onShow() {
      if(!this.$root.$mp.query.id||this.id !== this.$root.$mp.query.id){
        this.init()
      }
    },
    mounted(){
    },
    methods:{
      init(){
        this.orderDetail = null
        this.id = this.$root.$mp.query.id
        this.role = this.$store.state.role
        this.maskIsShow = false
        this.remark = ''
        this.getDetail()
      },
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
        const _this = this
        switch (status) {
          case 'apply':
            _this.maskIsShow = true
            break
          case 'pass':
            wx.navigateTo({
              url:`/pages/procurement/order-entry/main?order_id=${_this.id}`
            })
            break
        }
      },
      audit(type){
        const _this = this
        if(this.remark===''){
          this.$common.error_tip('请填写备注')
          return
        }
        let data = {
          order_id: +_this.id,
          verify_status: type,
          remark: _this.remark
        }
        _this.$ajax('Checkout/verify',data,function (res) {
          if(res.code === 1 ){
            _this.$common.success_tip('审核成功',function () {
              _this.init()
              wx.reLaunch({
                url: '/pages/procurement/main'
              })
            })
          }
        })
      }
    }
  }
</script>
<style scoped lang="scss">
  .pages{
    background-color: #FF4B5B;
    height: 100%;
    box-sizing: border-box;
    padding: 15px 0 50px 0;
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
      .goods-item{
        margin: 12px 0;
      }
    }
    .details-operation-btns{
      position: fixed;
      width: 100%;
      height: 50px;
      bottom: 0;
      left: 0;
      z-index: 1002;
      background-color: white;
    }
    .audit-mask{
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0,0,0,0.3);
      display: flex;
      justify-content: center;
      align-items: center;
      z-index: 1003;
      .mask-container{
        width: 300px;
        height: 250px;
        background:rgba(255,255,255,1);
        box-shadow:0px 3px 7px 0px rgba(0, 0, 0, 0.35);
        border-radius:5px;
        overflow: hidden;
        .mask-title{
          height: 35px;
          line-height: 35px;
          text-align: center;
          position: relative;
          font-weight: 500;
          font-size: 16px;
          border-bottom: 1px solid #ddd;
          .iconshanchuqq{
            position: absolute;
            right: 10px;
            top: 0;
            font-size: 24px;
          }
        }
        .mask-content{
          padding: 16px;
          width: 100%;
          box-sizing: border-box;
          #textarea{
            width: 100%;
            height: 150px;
            box-sizing: border-box;
            border: 1px solid #ddd;
            border-radius: 5px;
            line-height: 20px;
            resize: none;
            padding: 4px;
          }
        }
        .mask-footer-btn{
          height: 32px;
          box-sizing: border-box;
          border-top: 1px solid #ddd;
          display: flex;
          justify-content: space-between;
          background-color: white;
          .audit-refused,.audit-through{
            color: white;
            font-size: 16px;
            font-weight: 500;
            text-align: center;
            line-height: 32px;
            width: 49.8%;
          }
          .audit-refused{
            background-color: #03B3D8;
          }
          .audit-through{
            background-color: #2BCD72;
          }
        }
      }
    }
  }
</style>
