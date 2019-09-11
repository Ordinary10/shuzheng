<template>
  <Row v-if="ApplyData.status!=='apply'">
    <Row>
      <Col span="6">
        <div class="ma-spacing">
          申请人：<span class="key_text">{{ApplyData.uname}}</span>
        </div>
      </Col>
      <Col span="6">
        <div class="ma-spacing">
          门店：<span class="key_text">{{ApplyData.store_name}}</span>
        </div>
      </Col>
      <Col span="6">
        <div class="ma-spacing">
          申请时间：<span class="key_text">{{ApplyData.ctime}}</span>
        </div>
      </Col>
      <Col span="6">
        <div class="ma-spacing">
          状态：<span class="key_text">{{ApplyData.status_name}}</span>
        </div>
      </Col>
    </Row>
    <Divider />
    <Row class="examine-body roll">
      <Col span="24" v-for="(list,index) in seeData" :key="list.id">
        <Row>
          <Col span="6">
            <div class="ma-spacing">
              商品名称：<span class="key_text">{{list.name}}</span>
            </div>
          </Col>
          <Col span="6">
            <div class="ma-spacing">
              应收金额：<span class="key_text">{{list.apply_amount}}</span>
            </div>
          </Col>
          <Col span="6">
            <div class="ma-spacing">
              实收金额：<span class="key_text">{{list.delivery_amount}}</span>
            </div>
          </Col>
        </Row>
        <Divider />
      </Col>
    </Row>
    <Row>
      <Col span="6">
        <div class="ma-spacing box-ib" v-for="(list,index) in commoData"
             :key="list.id">
          <Button
            @click="commodity(list.name)"

            class="box-ib"
            :class="{primary:arr.includes(list.name)}"
          >{{list.name}}</Button>
        </div>
      </Col>
    </Row>
    <Form ref="formValidate">
      <Col span="24">
        <div class="ma-nomb-spacing">
          <FormItem label="审核意见">
            <Input v-model="examineremark" type="textarea" placeholder="审核意见" />
          </FormItem>
        </div>
      </Col>
      <Col span="24">
        <div class="ma-spacing">
          <Button type="success" @click="examine('pass',examineremark)">同意</Button>
          <Button type="error" @click="examine('deny',examineremark)">拒绝</Button>
        </div>
      </Col>
    </Form>
  </Row>
</template>

<script>
  export default {
    name: "popup",
    props: {
      ApplyData: {
      },
      id: {},
    },
    data(){
      return {
        arr:[],
        commoData:'',
        seeData:'',
        examineremark:'',
        storageremark:''
      }
    },
    created () {
      this.seeData = this.ApplyData.detail_info
      console.log(this.seeData)
      this.commoData = JSON.parse(JSON.stringify(this.seeData))
      console.log(this.ApplyData)
    },
    methods:{
      commodity(name){
        if (this.arr.includes(name)){
          this.arr=this.arr.filter(function (ele){return ele != name;});
        } else {
          this.arr.push(name)
        }
        this.seeData = []
        for (let key in this.arr){
          for (let i in this.commoData) {
            if (this.commoData[i].name==this.arr[key]){
              this.seeData.push(this.commoData[i])
            }
          }
        }
        //如果没有过滤则显示全部
        if (!this.seeData[0]){
          this.seeData = this.commoData
        }
        console.log(this.seeData)
      },
      //审核请求
      async examine(type,remark){
        const _this = this
        let res = await _this.$axios('Checkout/verify', {verify_status:type,remark:remark,order_id:this.ApplyData.id})
        console.log(res)
      },

    }

  }
</script>

<style scoped>

</style>
