<template>
  <div class="pages orderPages">
    <i-toast id="toast" />
    <div class="pages_header">
      <div class="pages_top_modified"></div>
    </div>
    <div class="pages_container">
      <scroll-view scroll-y class="left">
        <div class="category-list" v-if="data">
          <div
            class="category-list-item"
            @click="categoryClick(index)"
            v-for="(item,index) in data"
            :key="item.el_id"
            :class="{'category-active':categoryActive == item.el_id}"
            v-if="item.goods.length>0"
          >
            {{item.type_name}}
          </div>
        </div>
      </scroll-view>
      <scroll-view
        scroll-y
        class="right"
        :scroll-into-view="scroll_into_id"
        :scroll-with-animation="false"
        :scroll-anchoring="true"
        @scroll="scrollView"
        @scrolltolower="scrollTolower"
        @scrolltoupper="scrollToupper">
        <div class="goods-type-lists" v-if="data.length>0">
          <div class="goods-type-item" v-for="(item,index) in data" :key="item.el_id" v-if="item.goods.length>0">
            <div class="goods-type" :id="item.el_id">
              {{item.type_name}}
            </div>
            <div class="goods-lists">
              <div class="goods-item" v-for="(val,i) in item.goods" :key="val.id">
                <img :src="val.img||'http://zucheguanjia.oss-cn-qingdao.aliyuncs.com/insurance/15692289695472.png'" alt="" class="goods-img">
                <div class="goods-infor">
                  <div class="goods-name">{{val.name}}</div>
                  <div class="goods-unit_price">
                    <div>库存：{{val.stock}}{{val.lower_unit}}</div>
                  </div>
                </div>
                <div class="goods-amount">
                  <span @click="amountSub(val,i)" class="sub_btn" v-if="val.amount>0">-</span>
                  <input class="flex_1" style="margin: 0 4px;" @change="totalAmountChange" v-model="val.amount" v-if="val.amount>0" type="number" >
                  <span @click="amountAdd(val)" class="add_btn">+</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </scroll-view>
    </div>
    <div class="order-footer">
      <div class="order-list"  v-if="goodsCount>0" v-show="isSeeOrderList">
        <div class="order-list-title">
          <span @click="init">清空</span>
          <icon class="iconfont iconjiantou" @click="isSeeOrderList = false"></icon>
        </div>
        <div class="order-list-item">
          <div>商品</div>
          <div class="text_center">数量</div>
        </div>
        <div v-for="(item,index) in data" :key="item.el_id" v-if="item.goods.length>0">
          <div class="order-list-item" v-for="(val,i) in item.goods" :key="i" v-if="val.amount>0">
            <div>{{val.name}}</div>
            <div class="amount-change text_center" style="padding: 0 12px;box-sizing: border-box;">
              <span @click="amountSub(val)" class="sub_btn">-</span>
              <input class="flex_1" style="margin: 0 4px;" v-model="val.amount" type="number">
              <span @click="amountAdd(val)" class="add_btn">+</span>
            </div>
          </div>
        </div>
      </div>
      <icon class="iconfont icongouwuche" @click="goodsCount>0?isSeeOrderList = !isSeeOrderList:isSeeOrderList=false"></icon>
      <span class="order-count">{{goodsCount}}</span>
      <div class="total-amount"></div>
      <div class="small_btn_primary" @click="submitOrder" :class="{'no-submit':goodsCount===0}">确定</div>
    </div>
  </div>
</template>
<script>
  export default {
    components: {},

    data() {
      return {
        order_id: 0,
        data: null,
        categoryActive: '',
        el_name_list: [],
        goodsCount: 0,
        isSeeOrderList: false,
        scroll_into_id: ''
      }
    },
    onLoad(){
      this.init()
    },
    created() {
    },
    mounted(){
    },
    methods:{
      init(){
        const  _this = this
        _this.order_id = this.$mp.query.order_id || 0
        _this.data = []
        _this.scroll_into_id = ''
        _this.categoryActive = ''
        _this.el_name_list = []
        _this.goodsCount = 0
        _this.isSeeOrderList = false
        _this.$ajax('goods/getTypeWithGoods','',function (res) {
          _this.data = _this.dataFilter(res.data)
          if(_this.order_id != 0){
            _this.$ajax('checkout/getDetailInfo',{order_id:_this.order_id},function (res) {
              for(let item of res.data.detail_info){
                for(let val of _this.data){
                  if(val.goods.length>0){
                    for(let v of val.goods){
                      if(v.id === item.goods_id){
                        v.amount = item.apply_amount
                      }
                    }
                  }
                }
              }
              _this.goodsCountChange()
            })
          }
        })
      },
      /*增加el_id用于scroll-view的页面内锚点跳转标志*/
      dataFilter(data){
        for(let item of data){
          item.el_id = `_id${item.id}`
          if(item.goods.length>0){
            for(let val of item.goods){
              val.amount = 0
            }
          }
        }
        for(let item of data){
          if(item.goods.length>0){
            this.categoryActive = item.el_id
            this.scroll_into_id = item.el_id
            break
          }
        }
        for(let item of data){
          if(item.goods.length>0){
            this.el_name_list.push(item.el_id)
          }
        }
        return data
      },
      categoryClick(index) {
        this.categoryActive = this.data[index].el_id
        this.scroll_into_id = this.data[index].el_id
      },
      amountSub(item){
        item.amount--
        this.goodsCountChange()
      },
      amountAdd(item){
        item.amount++
        this.goodsCountChange()
      },
      goodsCountChange(){
        let goodsCount = 0
        for(let item of this.data){
          for(let val of item.goods){
            if(val.amount > 0){
              goodsCount++
            }
          }
        }
        this.goodsCount = goodsCount
      },
      scrollView(e){
        const _this = this
        let query = wx.createSelectorQuery()
        for(let el_name of _this.el_name_list){
          query.select(`#${el_name}`).boundingClientRect(function(rect){
            let top  = rect.top
            if(top<70&&top>50){
              _this.categoryActive = el_name
            }
          }).exec()
        }
      },
      scrollTolower(){
        this.categoryActive = this.el_name_list[this.el_name_list.length-1]
      },
      scrollToupper(){
        this.categoryActive = this.el_name_list[0]
      },
      submitOrder(){
        const _this = this
        let submitOrder = {
          order_id: 0,
          data: []
        }
        for(let v of _this.data){
          for(let item of v.goods){
            if(item.amount>0){
              submitOrder.data.push({
                goods_id: item.id,
                amount:item.amount,
              })
            }
          }
        }
        if(submitOrder.data.length > 0){
          _this.$ajax('Checkout/apply',submitOrder,function (res) {
            if(res.code===1){
              _this.$common.success_tip('订单提交成功',function () {
                wx.reLaunch({url:'/pages/outbound/main'})
              })
            }
          })
        }
      }
    }
  }
</script>
<style scoped lang="scss">
  @import "../../../assets/variables";
  /*:class样式*/
  .add-order-allow{
    background-color: #f1338e !important;
  }
  .no-submit{
    background-color: #999 !important;
  }
  .category-active{
    background-color: white;
  }
  /*内容区*/
  .pages_container{
    height: 100px;
    flex-direction: row !important;
    .left,.right{
      box-sizing: border-box;
      height: 100%;
      box-sizing: border-box;
      padding-bottom: 48px;
    }
    .left{
      width: 86px;
      background-color: #F8F8F8;
    }
    .right{
      width: 289px;
      box-sizing: border-box;
      background-color: white;
      .no-goods{
        text-align: center;
        margin: 8px 0;
        color: #999;
      }
    }
  }
  .order-footer{
    .icongouwuche{
      width: 65px;
      height: 65px;
      border-radius: 50%;
      background-color: #FFAE2F;
      text-align: center;
      color: white;
      font-size: 32px;
      line-height: 62px;
      position: absolute;
      bottom: 3px;
      left: 21px;
      z-index: 1001;
    }
    .total-amount{
      margin-left: 110px;
    }
    .order-count{
      width: 20px;
      height: 20px;
      border-radius: 50%;
      background-color: $mainColor;
      color: white;
      text-align: center;
      line-height: 20px;
      font-size: 14px;
      font-weight: 600;
      position: absolute;
      left: 75px;
      top: -10px;
      z-index: 1002;
    }
    .order-list{
      border-top: 1px solid rgba(0,0,0,0.2);
      background-color: white;
      position: absolute;
      bottom: 48px;
      left: 0;
      box-sizing: border-box;
      width: 375px;
      border-radius: 20px 20px 0 0;
      padding: 0 20px;
      overflow: hidden;
      .order-list-title{
        width: 100%;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid rgba(0,0,0,0.2);
        span,icon{
          line-height: 35px;
        }
      }
      .order-list-item{
        width: 100%;
        height: 40px;
        line-height: 40px;
        display: flex;
        justify-content: space-between;
        .amount-change{
          justify-content: center;
          align-items: center;
          display: flex;
        }
        div{
          width: 33%;
        }
      }
    }
  }
</style>
