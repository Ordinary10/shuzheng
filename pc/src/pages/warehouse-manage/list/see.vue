<template>
  <Row v-if="this.ApplyData">
    <Col span="12">
      <div class="ma-spacing">
        申请人：<span class="key_text">{{ApplyData.uname}}</span>
      </div>
    </Col>
    <Col span="12">
      <div class="ma-spacing">
        门店：<span class="key_text">{{ApplyData.store_name}}</span>
      </div>
    </Col>
    <Col span="12">
      <div class="ma-spacing">
        申请时间：<span class="key_text">{{ApplyData.ctime}}</span>
      </div>
    </Col>
    <Col span="12">
      <div class="ma-spacing">
        状态：<span class="key_text">{{ApplyData.status_name}}</span>
      </div>
    </Col>
    <Divider />
    <Col span="24" v-for="(list,index) in ApplyData.detail_info" :key="list.id">
      <Col span="12">
        <div class="ma-spacing">
          商品名称：<span class="key_text">{{list.name}}</span>
        </div>
      </Col>
      <Col span="12">
        <div class="ma-spacing">
          应付金额：<span class="key_text">{{list.apply_amount}}</span>
        </div>
      </Col>
      <Col span="12">
        <div class="ma-spacing">
          实收金额：<span class="key_text">{{list.delivery_amount}}</span>
        </div>
      </Col>
      <Col span="12">
        <div class="ma-spacing">
          单位：<span class="key_text">{{list.unit}}</span>
        </div>
      </Col>
      <Col span="12">
        <div class="ma-spacing">
          数量：<span class="key_text">{{list.unit_price}}</span>
        </div>
      </Col>
      <Col span="12">
        <div class="ma-spacing">
          商品条码：<span class="key_text">{{list.bar_code}}</span>
        </div>
      </Col>
      <Col span="12">
        <div class="ma-spacing">
          仓库：<span class="key_text">{{list.place}}</span>
        </div>
      </Col>
      <Divider />

    </Col>
  </Row>
</template>

<script>
    export default {
      name: "see",
      props: {
        ApplyData: {
        },
        id: {}
      },
      data(){
        return {
          seeData:''
        }
      },
      created () {
        this.seeData = this.ApplyData.detail
      },

    }
</script>

<style scoped>

</style>
