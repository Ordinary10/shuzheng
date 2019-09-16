<template>
    <div class="goods-details-card" v-if="data&&status">
      <div class="goods-title">
        {{data.name}}
      </div>
      <slot name="content">
        <div class="goods-details">
          <div class="goods-details-item nowrap">预购数量：{{data.apply_amount}}{{data.unit}}</div>
          <div class="goods-details-item nowrap">预估费用：{{data.estimated_money}}元</div>
          <div class="goods-details-item nowrap" v-if="status != 'apply'&&status != 'pass'">实购数量：{{data.buy_amount}}{{data.unit}}</div>
          <div class="goods-details-item nowrap" v-if="status != 'apply'&&status != 'pass'">实购花费：{{data.buy_money}}元</div>
        </div>
      </slot>
    </div>
</template>

<script>
    export default {
      name: "index",
      data(){
        return {
          data: null,
          status: null
        }
      },
      props: {
        goodsData: {
          type: Object,
          required: true
        },
        orderStatus: {
            type: String,
            default: 'apply'
          }
      },
      onLoad(){
        this.data = null
        this.status = null
        this.data = this.$props.goodsData
        this.status = this.$props.orderStatus
      },
      onShow(){
      },
      mounted(){
      }
    }
</script>

<style scoped lang="scss">
.goods-details-card{
  width: 100%;
  box-sizing: border-box;
  border-radius: 6px;
  overflow: hidden;
  border: 1px solid #ddd;
  padding: 0 8px;
  .goods-title{
    line-height: 36px;
    font-weight: 600;
    font-size: 16px;
    border-bottom: 1px solid #ddd;
  }
  .goods-details{
    font-size: 14px;
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    padding: 10px 0;
    .goods-details-item{
      width: 50%;
      box-sizing: border-box;
      padding: 10px 0;
    }
  }
}
</style>
