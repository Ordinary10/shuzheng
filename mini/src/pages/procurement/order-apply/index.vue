<template>
  <div class="pages orderPages">
    <i-toast id="toast" />
    <div class="no-goods" v-show="orderList.length===0">
      <image class="img" src="http://test.c.zdxrchina.com/images/wechat/search_bgc.png"></image>
      <div class="no-goods-text">
        <span>还没有商品</span><br>
        <span>请点击新增商品添加</span>
      </div>
    </div>
    <div class="mini-list" v-show="orderList.length>0">
      <div class="mini-list-item">

      </div>
      <div class="mini-list-item">

      </div>
      <div class="mini-list-item">

      </div>
    </div>
    <div class="footer">
      <div class="footer-btn">
        <picker @change="bindPickerChange" @columnchange="bindColumnChange" :value="goodsIndex" mode="multiSelector" :range="multiArray" range-key="type_name">
          <div class="picker">
            <span>新增商品</span>
          </div>
        </picker>
      </div>
      <div class="footer-btn" @click="submitOrder">
        提交订单
      </div>
    </div>
  </div>
</template>
<script>
  export default {
    components: {},

    data() {
      return {
        goodsList: [],
        orderList: [],
        multiArray: [],
        goodsIndex: [0,0,0],
      }
    },

    created() {
    },
    onShow() {
    },
    mounted(){
      this.init()
    },
    methods:{
      init(){
        const  _this = this
        _this.$ajax('goods/getGoodsType','',function (res) {
          _this.goodsList = res.data
          /**** 利用JSON方法达到深拷贝数组的目的，让vue数据监视能正确识别数组的变化 *******/
          let _copy_data = JSON.parse(JSON.stringify(res.data))
          _this.multiArray[0] = _copy_data
          _this.multiArray[1] = _copy_data[0].children
          _this.multiArray[2] = _copy_data[0].children[0].children
        })
      },
      bindPickerChange (e) {
        const _this = this,
              val = e.mp.detail.value
        for(let i in _this.goodsIndex) {
          if(val[i] !== _this.goodsIndex[i]){
            return
          }
        }
        let _copy_data = JSON.parse(JSON.stringify(_this.goodsList))
        console.log(_copy_data)
        _this.orderList.push(_copy_data[_this.goodsIndex[0]][_this.goodsIndex[1]][_this.goodsIndex[2]])
        console.log(_this.orderList)
      },
      bindColumnChange (e) {
        const _this = this
        /**** 利用JSON方法达到深拷贝数组的目的，让vue数据监视能正确识别数组的变化 *******/
        let _copy_data = JSON.parse(JSON.stringify(_this.goodsList)),
            _copy_multiArray = JSON.parse(JSON.stringify(_this.multiArray))
        _this.goodsIndex[e.mp.detail.column] = e.mp.detail.value
        if(e.mp.detail.column === 0){
          _this.goodsIndex[1]=0
          _this.goodsIndex[2]=0
          _copy_multiArray[1]=_copy_data[_this.goodsIndex[0]].children
          _copy_multiArray[2]=_copy_data[_this.goodsIndex[0]].children[_this.goodsIndex[1]].children

        } else if(e.mp.detail.column === 1) {
          _this.goodsIndex[2]=0
          _copy_multiArray[2]=_copy_data[_this.goodsIndex[0]].children[_this.goodsIndex[1]].children
        }
        _this.multiArray = _copy_multiArray
      },
      submitOrder(){
        console.log(this.orderList)
      }
    }
  }
</script>

<style lang="wxss">
  page{
    background-color: #F3F3F3;
  }
</style>
<style scoped lang="scss">
  .pages{
    display: flex;
    .no-goods{
      margin: auto;
      .img{
        width: 198px;
        height: 100px;
        margin-bottom: 10px;
      }
      .no-goods-text{
        font-size: 14px;
        text-align: center;
        color: #999;
      }
    }
  }
</style>
