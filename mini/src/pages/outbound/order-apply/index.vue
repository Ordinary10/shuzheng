<template>
  <div class="pages orderPages">
    <i-toast id="toast" />
    <scroll-view scroll-y class="category-list" v-if="categoryList">
      <div class="category-list-item" data-index="-1" @click="categoryClick" :class="{'category-active':categoryActive == 'all'}">
        <span style="padding-left: 8px">全部</span>
      </div>
      <div
        class="category-list-item"
        @click="categoryClick"
        :data-index="index1"
        v-for="(item1,index1) in categoryList"
        :key="item1.id"
      >
        <div class="category-1" :class="{'category-active':categoryActive == item1.id}">
          <span class="flex_1">{{item1.type_name}}</span>
          <span v-if="item1.children.length>0" data-icon="1"><i-icon data-icon="1" type="enter" size="20"/></span>
        </div>
        <div class="category-1-list animated slideInDown" @click.stop="" v-if="item1.children.length>0" v-show="category1Show==item1.id">
          <div
            class="category-1-list-item"
            v-for="(item2,index2) in item1.children"
            :key="index2"
            @click="category1Click(index1,index2)"
            :class="{'category1-active':category1Active == index2}"
          >
            <div style="text-align: center">
              {{item2.type_name}}
            </div>
          </div>
        </div>
      </div>
    </scroll-view>
    <scroll-view scroll-y class="goods-list" v-if="goodsList.length>0">
      <div class="goods-item" v-for="(item,index) in goodsList" :key="item.id">
        <i-card :title="item.name" extra=" " thumb=" ">
          <div slot="content" class="content">
            <div>数量：<input type="number" v-model="item.amount">{{item.unit}}</div>
          </div>
          <div slot="footer" class="card-footer">
            <span></span>
            <span @click="addOrder(item)" class="add-order-btn" :class="{'add-order-allow':item.unit_price}">+</span>
          </div>
        </i-card>
      </div>
    </scroll-view>
    <div class="goods-list" v-if="goodsList.length==0">
      <div class="no-goods">该类目暂无商品</div>
    </div>
    <div class="order-footer">
      <div class="order-list animated" :class="{fadeInUp:isSeeOrderList,fadeOutDown:!isSeeOrderList}" v-if="orderList.length>0" v-show="isSeeOrderList">
        <div class="order-list-title">
          <span @click="isSeeOrderList = false">取消</span>
          <span @click="emptyOrderList">清空</span>
        </div>
        <div class="order-list-item" v-if="item.isShow" v-for="(item,index) in orderList" :key="item.goods_id">
          <div>{{item.name}}</div>
          <div class="amount-change">
            <span @click="amountSub(item)">-</span>
            <span>{{item.amount}}</span>
            <span @click="amountAdd(item)">+</span>
          </div>
        </div>
      </div>
      <div class="footer-btns">
        <div class="see-order-list" @click="seeOrderList">
          <span class="order-count" v-if="orderCount>0">{{orderCount}}</span>
          <icon class="iconfont iconcaigoudan caigoudan"></icon>
        </div>
        <div class="submit" @click="submitOrder" :class="{'submit-order-allow':orderCount>0}">
          提交
        </div>
      </div>
    </div>
  </div>
</template>
<script>
  export default {
    components: {},

    data() {
      return {
        last_type_id: null,
        type_id: '',
        category1Show: -1,
        categoryActive: 'all',
        category1Active:0,
        orderList: [],
        goodsList: [],
        categoryList: null,
        orderCount: 0,
        isSeeOrderList: false
      }
    },

    created() {
    },
    mounted(){
      this.type_id = ''
      this.last_type_id = null
      this.category1Show = -1
      this.categoryActive = 'all'
      this.category1Active = 0
      this.orderList = []
      this.orderCount = 0
      this.isSeeOrderList = false
      this.init()
      this.getGoodsList(true)
    },
    methods:{
      init(){
        this.categoryList = []
        const  _this = this
        _this.$ajax('goods/getGoodsType','',function (res) {
          _this.categoryList = res.data
        })
      },
      getGoodsList(isFirst) {
        const _this =this
        if(isFirst || (_this.last_type_id!=_this.type_id)) {
          _this.last_type_id = _this.type_id
          _this.$ajax('goods/getAllGoodsByType',{type_id:_this.type_id},function (res) {
            _this.goodsList = res.data
            // console.log(_this.goodsList)
          })
        }
      },
      categoryClick(e) {
        let index = e.mp.currentTarget.dataset.index,
            icon = e.mp.target.dataset.icon
        if(index==-1) {
          this.category1Show = -1
          this.categoryActive = 'all'
          this.type_id = ''
        } else if(icon==1) {
          this.categoryActive = this.categoryList[index].id
          this.category1Show = this.categoryList[index].id
          this.type_id = this.categoryList[index].children[0].id
        } else if(icon!=1) {
          this.categoryActive = this.categoryList[index].id
          this.category1Show = -1
          this.type_id = this.categoryList[index].id
        }
        this.category1Active = 0
        this.getGoodsList()
      },
      category1Click(index1,index2) {
        this.category1Active = index2
        this.type_id = this.categoryList[index1].children[index2].id
        this.getGoodsList()
      },
      addOrder(item) {
        if(item.amount){
          this.orderList.push({
            name: item.name,
            goods_id:item.id,
            amount:item.amount,
            isShow: true
          })
          this.orderCount ++
        } else {
          this.$common.error_tip('请输入商品出库数量')
        }
      },
      amountSub(item) {
        item.amount--
        if(item.amount==0){
          item.isShow = false
          this.orderCount--
          this.orderList = JSON.parse(JSON.stringify(this.orderList))
        }
      },
      amountAdd(item) {
        item.amount++
      },
      seeOrderList() {
        if(this.orderList.length===0 || this.isSeeOrderList == true){
          this.isSeeOrderList = false
          return
        }
        let _copy_orderList = JSON.parse(JSON.stringify(this.orderList))
        for(let v of _copy_orderList){
          if(v.isShow) {
            this.isSeeOrderList = true
            return
          }
        }
      },
      emptyOrderList() {
        this.orderList = []
        this.isSeeOrderList = false
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
          _this.$ajax('Checkout/apply',submitOrder,function (res) {
            if(res.code===1){
              _this.$common.success_tip('出库单提交成功',function () {
                wx.reLaunch({url:'/pages/outbound/main'})
              })
            }
          })
        }
      }
    }
  }
</script>

<style lang="wxss">
  page{
    background-color: white;
    height: 100%;
  }
</style>
<style scoped lang="scss">
  /*:class样式*/
  .add-order-allow{
   background-color: #f1338e !important;
  }
  .submit-order-allow{
    background-color: #f1338e !important;
  }
  .pages{
    height: 100%;
    width: 100%;
    position: relative;
    .category-list{
      border-top: 1px solid #eee;
      position: fixed;
      top: 0;
      left: 0;
      width: 80px;
      box-sizing: border-box;
      padding-bottom: 60px;
      background-color: #F3F3F3;
      height: 100%;
      .category-list-item{
        min-height: 36px;
        line-height: 36px;
        display: flex;
        flex-direction: column;
        font-size: 12px;
        color: #999;
        .category-1{
          display: flex;
          .flex_1{
            flex: 1;
            padding-left: 8px;
          }
        }
        .category-1-list{
          width: 100%;
          background-color: #ddd;
        }
      }
      .category-active{
        background-color: #ddd !important;
      }
      .category1-active{
        color: #3a8ff5 !important;
      }
    }
    .goods-list{
      border-top: 1px solid #eee;
      margin-left: 80px;
      box-sizing: border-box;
      background-color: white;
      padding: 0 0 60px 10px;
      min-height: 100%;
      .no-goods{
        margin-top: 20px;
        text-align: center;
        font-size: 14px;
        color: #999;
      }
      .goods-item{
        padding-right: 10px;
        box-sizing: border-box;
        margin: 8px 0;
        width: 283px;
      }
      .content{
        margin-top: 12px;
        div{
          display: flex;
          margin-top: 8px;
          align-items: center;
        }
        input{
          width: 120px;
          text-align: center;
          margin: 0 2px;
          border-bottom: 1px solid #999;
        }
      }
      .card-footer{
        display: flex;
        padding-bottom: 2px;
        justify-content: space-between;
        .add-order-btn{
          padding: 2px 8px;
          border-radius: 14px;
          color: white;
          background-color: #999;
          &:hover{
            background-color: #f12126;
          }
        }
      }
    }
    .order-footer{
      position: fixed;
      bottom: 0;
      background-color: #595959;
      height: 42px;
      width: 100%;
      z-index: 1000;
      .order-list{
        width: 100%;
        position: absolute;
        bottom: 42px;
        left: 0;
        color: #999;
        font-size: 14px;
        background-color: white;
        border-radius:14px 14px 0 0;
        border-top: 1px solid #eee;
        overflow: hidden;
        .order-list-title{
          height: 40px;
          display: flex;
          align-items: center;
          justify-content: space-between;
          padding: 0 14px;
          span{
            line-height: 40px;
            color: #999;
          }
        }
        .order-list-item{
          width: 100%;
          border-top: 1px solid #eee;
          display: flex;
          padding: 0 14px;
          box-sizing: border-box;
          justify-content: space-between;
          height: 40px;
          div{
            min-width: 80px;
            line-height: 40px;
          }
          .amount-change{
            display: flex;
            justify-content: center;
            align-items: center;
            span{
              width: 32px;
              height: 28px;
              border: 1px solid #eee;
              line-height: 28px;
              text-align: center;
            }
          }
        }
      }
      .footer-btns{
        display: flex;
        width: 100%;
        line-height: 42px;
        color: #eee;
        .submit{
          font-size: 16px;
          padding: 0 16px;
          background-color: #818181;
        }
        .see-order-list{
          position: relative;
          .order-count{
            position: absolute;
            top: 3px;
            left: 30px;
            border-radius: 50%;
            width: 16px;
            height: 16px;
            text-align: center;
            line-height: 12px;
            font-size: 12px;
            background-color: #f51247;
            color: white;
          }
          flex: 1;
          padding-left: 16px;
          box-sizing: border-box;
          .caigoudan{
            font-size: 24px;
            padding-right: 40px;
          }
          .total_amount{
            line-height: 24px;
          }
        }
      }
    }
  }
</style>
