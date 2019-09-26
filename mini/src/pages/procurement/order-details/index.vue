<template>
  <div class="pages">
    <i-toast id="toast" />
    <div class="detail-box" v-if="orderDetail">
      <div class="dataList">
        <div class="dataItem">
          <span class="dataItemLeft">申请人：</span>
          <span class="dataItemRight flex_1">{{orderDetail.uname||'-'}}</span>
          <icon class="iconfont iconbianji" style="color: #1da3ff;" @click.stop="editorOrder" v-if="orderDetail.status === 'apply'&&role!=='purchase'"></icon>
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
      <div class="order-goods-list" v-if="orderDetail.detail&&orderDetail.detail.length>0">
        <div class="divider-title">
          <divider content="商品详情" :css="{'font-size': '17px'}"></divider>
        </div>
        <div class="goods-item" v-for="(item,index) in orderDetail.detail" :key="item.id">
          <goodsDetailsCard :goodsData="item" :orderStatus="orderDetail.status"></goodsDetailsCard>
        </div>
      </div>
    </div>
    <div class="details-operation-btns" v-if="orderDetail">
      <span class="large_btn_primary" @click="orderEditor('apply')" v-if="orderDetail.status==='apply'&&role === 'boss'&&orderDetail.verify_status==1">审核</span>
      <span class="large_btn_primary" @click="orderEditor('apply')" v-if="orderDetail.status==='apply'&&(role === 'purchase'||role === 'boss')&&orderDetail.verify_status==5">审核</span>
      <span class="large_btn_primary" @click="orderEditor('pass')" v-if="orderDetail.status==='pass'&&(role === 'boss'||role==='purchase')">录入</span>
      <span class="large_btn_primary" @click="orderEditor('buy')" v-if="orderDetail.status==='buy'&&(role==='boss'||role==='store')">签收</span>
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
    onLoad(){
      // this.init()
    },
    onShow() {
      this.init()
    },
    methods:{
      init(){
        this.maskIsShow = false
        this.remark = ''
        this.id = this.$root.$mp.query.id
        this.role = this.$store.state.role
        this.orderDetail = null
        this.getDetail()
      },
      getDetail() {
        const _this = this
        _this.$ajax('purchaseOrder/getDetailInfo',{order_id:_this.id},function (res) {
          _this.orderDetail = _this.dataFilter(res.data)
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
          case 'buy':
            wx.navigateTo({
              url:`/pages/common/order-receive/main?order_id=${_this.id}&type=procurement`
            })
            break
        }
      },
      editorOrder(){
        wx.navigateTo({
          url:`/pages/procurement/order-apply/main?order_id=${this.id}`
        })
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
        _this.$ajax('purchaseOrder/verify',data,function (res) {
          if(res.code === 1 ){
            _this.$common.success_tip('审核成功',function () {
              _this.init()
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
  }
</style>
