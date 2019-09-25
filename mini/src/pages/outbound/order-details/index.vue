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
        </div>
      </div>
      <div class="detail-list" v-if="orderDetail.detail_info&&orderDetail.detail_info.length>0">
        <div class="divider-title">
          <divider content="出库商品" :css="{'font-size': '17px'}"></divider>
        </div>
        <div class="detail-list-item" v-for="(item,index) in orderDetail.detail_info" :key="item.id">
          <div class="goods-title">
            {{item.name}}
          </div>
          <div class="goods-content">
            <div class="content-item nowrap">申请出库量：{{item.apply_amount || 0}}</div>
            <div class="content-item nowrap" v-if="orderDetail.status=='distribute'||orderDetail.status=='done'">实际出库量：{{item.delivery_amount || 0}}</div>
          </div>
        </div>
      </div>
    </div>
    <div class="details-operation-btns" v-if="orderDetail">
      <span class="large_btn_primary" @click="orderEditor('apply')" v-if="orderDetail.status==='apply'&&(role==='boss'||role==='storage')">审核</span>
      <span class="large_btn_primary" @click="orderEditor('pass')" v-if="orderDetail.status==='pass'&&(role==='boss'||role==='storage')">配货</span>
      <span class="large_btn_primary" @click="orderEditor('distribute')" v-if="orderDetail.status==='distribute'&&(role==='boss'||role==='store')">签收</span>
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
        remark: '',
        maskIsShow: false,
      }
    },
    onShow() {
      this.init()
    },
    mounted(){
    },
    methods:{
      init(){
        this.remark = ''
        this.maskIsShow = false
        this.orderDetail = null
        this.id = this.$root.$mp.query.id
        this.role = this.$store.state.role
        this.getDetail()
      },
      getDetail() {
        const _this = this
        _this.$ajax('checkout/getDetailInfo',{order_id:_this.id},function (res) {
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
              url:`/pages/outbound/order-entry/main?order_id=${_this.id}`
            })
            break
          case 'distribute':
            wx.navigateTo({
              url:`/pages/common/order-receive/main?order_id=${_this.id}&type=outbound`
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
        _this.$ajax('checkout/verify',data,function (res) {
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

<style lang="wxss">
  page{
    background-color: #F3F3F3;
    height: 100%;
  }
</style>
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
