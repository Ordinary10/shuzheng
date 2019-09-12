<template>
  <div class="pages orderPages">
    <i-toast id="toast" />
    <div class="pages_header">
      <div class="pages_top_modified"></div>
      <div class="search_box">
        <div class="a_single_search_input">
          <input type="text" class="search-input" v-model="goodsName" placeholder="搜索商品">
          <icon class="iconfont iconsearch2" @click="goodsNameSearch"></icon>
        </div>
      </div>
    </div>
    <div class="pages_container">
      <scroll-view scroll-y class="left">
        <div class="category-list" v-if="categoryList">
          <div class="category-list-item" @click="categoryClick('')" :class="{'category-active':categoryActive == 'all'}">
            <span>全部</span>
          </div>
          <div
            class="category-list-item"
            @click="categoryClick(item1)"
            v-for="(item1,index1) in categoryList"
            :key="item1.id"
            :class="{'category-active':categoryActive == item1.id}"
          >
            {{item1.type_name}}
          </div>
        </div>
      </scroll-view>
      <scroll-view scroll-y class="right">
        <div class="goods-list" v-if="goodsList.length>0">
          <div class="goods-list-item" v-for="(item,index) in goodsList" :key="item.id">
            <div class="goods-name">{{item.name}}</div>
            <div class="goods-num">
              <span class="add_btn" @click="addGoods(item)">+</span>
            </div>
          </div>
        </div>
        <div class="no-goods" v-else>该类目暂无商品</div>
      </scroll-view>
    </div>
    <div class="order-footer">
      <div class="order-list"  v-if="orderList.length>0" v-show="isSeeOrderList">
        <div class="order-list-title">
          <span @click="emptyOrderList">清空</span>
          <icon class="iconfont iconjiantou" @click="isSeeOrderList = false"></icon>
        </div>
        <div class="order-list-item">
          <div>商品</div>
          <div class="text_center">单价</div>
          <div class="text_center">数量</div>
        </div>
        <div class="order-list-item" v-show="item.isShow" v-for="(item,index) in orderList" :key="item.goods_id">
          <div>{{item.name}}</div>
          <div class="text_center">
            <input style="line-height: 40px;height: 40px;" type="digit" v-model="item.unit_price" placeholder="0" @blur="unit_priceChange">
          </div>
          <div class="amount-change text_center">
            <span @click="amountSub(item,index)" class="sub_btn">-</span>
            <span style="min-width: 40px">{{item.amount}}</span>
            <span @click="amountAdd(item)" class="add_btn">+</span>
          </div>
        </div>
      </div>
      <icon class="iconfont icongouwuche" @click="orderList.length>0?isSeeOrderList = !isSeeOrderList:isSeeOrderList=false"></icon>
      <span class="order-count">{{orderCount}}</span>
      <div class="total-amount">
        <i-icon style="margin-right: 12px;" type="financial_fill" size="24" color="red"/>{{total_amount}}
      </div>
      <div class="small_btn_primary" @click="submitOrder" :class="{'no-submit':orderCount===0}">确定</div>
    </div>
  </div>
</template>
<script>
  export default {
    components: {},

    data() {
      return {
        type_id: '',
        goodsName: '',
        categoryActive: 'all',
        orderList: [],
        goodsList: [],
        categoryList: null,
        orderCount: 0,
        total_amount: 0,
        isSeeOrderList: false
      }
    },

    created() {
    },
    mounted(){
      this.type_id = ''
      this.goodsName = ''
      this.categoryActive = 'all'
      this.orderList = []
      this.orderCount = 0
      this.total_amount = 0
      this.isSeeOrderList = false
      this.init()
      this.getGoodsList()
    },
    methods:{
      init(){
        this.categoryList = []
        const  _this = this
        _this.$ajax('goods/getGoodsType','',function (res) {
          _this.categoryList = res.data
        })
      },
      getGoodsList() {
        const _this =this
        _this.$ajax('goods/getGoodsLists',{
          type_id:_this.type_id,
          name: _this.goodsName
        },function (res) {
          _this.goodsList = res.data
        })
      },
      goodsNameSearch(){
        this.type_id = ''
        this.getGoodsList()
      },
      categoryClick(item) {
       if(item){
         this.categoryActive = item.id
         this.type_id = item.id
       } else {
         this.categoryActive = 'all'
         this.type_id = ''
       }
        this.getGoodsList()
      },
      addGoods(item){
        let data = {
          goods_id: item.id,
          amount: 1,
          unit_price: 0,
          name: item.name,
          isShow: true
        }
        this.orderList.push(data)
        this.orderCount ++
      },
      amountSub(item,index){
        if(item.amount === 1) {
          item.isShow = false
          this.orderList.splice(index,1)
          this.orderCount--
          if(this.orderCount === 0){
            this.isSeeOrderList = false
          }
        } else {
          item.amount--
        }
        this.totalAmountChange()
      },
      amountAdd(item){
        item.amount++
        this.totalAmountChange()
      },
      unit_priceChange() {
        this.totalAmountChange()
      },
      totalAmountChange(){
        let total_amount = 0
        for(let item of this.orderList){
          total_amount+=Number(item.amount)*Number(item.unit_price)
        }
        this.total_amount = total_amount
      },
      emptyOrderList() {
        this.orderList = []
        this.isSeeOrderList = false
        this.total_amount = 0
        this.orderCount = 0
      },
      submitOrder(){
        const _this = this
        let submitOrder = {
          order_id: 0,
          data: []
        }
        for(let v of this.orderList){
          if(v.isShow){
            submitOrder.data.push(v)
          }
        }
        if(submitOrder.data.length === 0){
          _this.$common.error_tip('未添加任何商品')
        } else {
          _this.$ajax('purchaseOrder/apply',submitOrder,function (res) {
            if(res.code===1){
              _this.$common.success_tip('订单提交成功',function () {
                wx.reLaunch({url:'/pages/procurement/main'})
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
  /*顶部搜索区*/
  .search_box{
    width: 100%;
    position: absolute;
    top: 14px;
    left: 0;
    z-index: 999;
    display: flex;
    justify-content: center;
  }
  .search-input{
    width: 100%;
    box-sizing: border-box;
    height: 32px;
    line-height: 32px;
    text-align: center;
    padding: 0 15px;
  }
  .iconsearch2{
    position: absolute;
    line-height: 32px;
    width: 40px;
    text-align: center;
    top: 0;
    right: 0;
    z-index: 1000;
    color: $mainColor;
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
      padding-left: 8px;
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
