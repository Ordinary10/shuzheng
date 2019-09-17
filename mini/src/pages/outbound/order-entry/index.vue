<template>
  <div class="pages">
    <i-toast id="toast" />
    <div class="detail-box" v-if="orderDetail">
      <div class="goods-lists" v-if="orderDetail.detail_info.length>0">
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
        <div class="goods-item">
          <div class="content">
            <textarea v-model="remark" id="textarea" placeholder="请输入配货备注" max="100" ></textarea>
          </div>
        </div>
      </div>
    </div>
    <div class="details-operation-btns">
      <button type="button" @click="submitEntry" class="large_btn_primary">提交</button>
    </div>
  </div>
</template>
<script>
  export default {
    components: {},

    data() {
      return {
        order_id: '',
        orderDetail: null,
        searchText: '',
        remark: ''
      }
    },
    created() {
    },
    onShow() {
      if(this.$root.$mp.query.order_id&&this.order_id != this.$root.$mp.query.order_id){
        this.orderDetail = null
        this.order_id = this.$root.$mp.query.order_id
        this.getDetail()
      }
    },
    mounted(){
    },
    methods:{
      getDetail() {
        const _this = this
        _this.$ajax('checkout/getDetailInfo',{order_id:_this.order_id},function (res) {
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
            if(key=='detail_info') {
              for(let item of data.detail_info){
                item.bar_code = ''
                item.num = item.apply_amount
              }
            }
          })
        }
        return data
      },
      barCode(item) {
        const _this = this
        wx.scanCode({
          success (res) {
            item.bar_code = res.result
          },
          fail(){
            _this.$common.error_tip('扫码失败，请重试')
          }
        })
      },
      submitEntry(){
        const _this = this
        if(_this.remark === ''){
          _this.$common.error_tip('请填写配货备注')
          return
        }
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
          remark: _this.remark,
          order_id: _this.order_id,
          data: detail
        }
        _this.$ajax('checkout/distribute',data,function (res) {
          if(res.code === 1){
            _this.$common.success_tip('配货成功',function () {
              wx.reLaunch({url:`/pages/outbound/main`})
            })
          }
        })
      }
    }
  }
</script>

<style scoped lang="scss">
  .pages{
    height: 100%;
    background-color: #FF4B5B;
    box-sizing: border-box;
    padding: 15px 0 40px 0;
    display: flex;
    font-size: 16px;
    color:#495060;
    flex-direction: column;
    .qrcode{
      .iconQR-code1{
        line-height: 40px;
        font-size: 16px;
        padding: 0 12px;
        color: #2486ff;
        position: absolute;
        top: 0;
        right: 0;
        z-index: 998;
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
      .goods-lists{
        padding-bottom: 12px;
        .goods-item{
          margin: 12px 0;
          .content{
            display: flex;
            flex-wrap: wrap;
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
            .content-item{
              width: 50%;
              padding: 8px;
              box-sizing: border-box;
              display: flex;
              align-items: center;
              position: relative;
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
  }
</style>
