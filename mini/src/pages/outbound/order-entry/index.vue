<template>
  <div class="pages">
    <i-toast id="toast" />
<!--    <div class="search-box">-->
<!--      <input type="text" v-model="searchText" placeholder="请扫描条形码或输入商品条形码">-->
<!--      <icon class="iconfont iconsearch2"></icon>-->
<!--      <icon class="iconfont iconQR-code1"></icon>-->
<!--    </div>-->
    <div class="detail-box" v-if="orderDetail">
      <div class="goods-list" v-if="orderDetail.detail_info.length>0">
        <div class="divider-title">
          <divider content="申请商品详情" :css="{'font-size': '17px'}"></divider>
        </div>
        <div class="goods-item" v-for="(item,index) in orderDetail.detail_info" :key="item.id">
          <i-card :title="item.name" extra=" " thumb=" ">
            <div slot="content" class="content">
              <div class="content-item" style="width:100%">申请数：{{item.apply_amount}}</div>
              <div class="content-item" style="width:100%">
                <span>配货数：</span>
                <input type="number" v-model="item.num" placeholder="请输入配货数">
              </div>
              <div class="qrcode content-item" style="width: 100%">
                <span>条形码：</span>
                <input type="text" v-model="item.bar_code" placeholder="请输入或扫描条形码">
                <icon class="iconfont iconQR-code1" @click="barCode(item)"></icon>
              </div>
            </div>
          </i-card>
        </div>
      </div>
    </div>
    <div class="entry-submit">
      <button type="button" @click="submitEntry" class="login_btn">配货</button>
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
        searchText: ''
      }
    },
    created() {
    },
    onShow() {
      this.orderDetail = null
      this.order_id = this.$root.$mp.query.order_id
      this.getDetail()
    },
    mounted(){
    },
    methods:{
      getDetail() {
        const _this = this
        _this.$ajax('checkout/getDetailInfo',{order_id:_this.order_id},function (res) {
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
              for(let item of data.detail_info){
                item.buy_amount = item.apply_amount
                item.buy_money = item.estimated_money
                item.bar_code = ''
                item.num = ''
              }
            }
          })
        }
        return data
      },
      barCode(item) {
        wx.scanCode({
          success (res) {
            console.log(res)
          }
        })
      },
      submitEntry(){
        const _this = this
        let detail = []
        for(let v of _this.orderDetail.detail_info){
          if(v.bar_code!==''&&v.num!==''){
            detail.push({
              detail_id: v.id,
              bar_code: v.bar_code,
              num: v.num
            })
          } else {
            _this.$common.error_tip('商品条形码和数量不能为空')
            return
          }
        }
        let data = {
          order_id: _this.order_id,
          data: detail
        }
        _this.$ajax('checkout/distribute',data,function (res) {
          if(res.code === 1){
            _this.$common.success_tip('配货成功',function () {
              wx.navigateBack()
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
    height: 100%;
    box-sizing: border-box;
    padding: 15px 0 40px 0;
    display: flex;
    flex-direction: column;
    .qrcode{
      .iconQR-code1{
        line-height: 40px;
        font-size: 16px;
        padding: 0 12px;
        color: #2486ff;
      }
    }
    .search-box{
      width: 100%;
      display: flex;
      position: relative;
      justify-content: center;
      margin-bottom: 16px;
      .iconfont{
        position: absolute;
        z-index: 998;
        top: 0;
        line-height: 40px;
        font-size: 20px;
      }
      .iconsearch2{
        right: 30px;
        color: #f51247;
      }
      .iconQR-code1{
        left: 30px;
        color: #1da3ff;
      }
      input{
        font-size: 14px;
        width: 90%;
        box-sizing: border-box;
        padding: 0 40px;
        height: 40px;
        line-height: 40px;
        border-radius: 20px;
        background-color: white;
      }
    }
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
