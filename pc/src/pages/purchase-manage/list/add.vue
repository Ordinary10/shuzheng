<template>
  <Row v-if="goodsLists">
    <Row v-for="(v,i) in fromdata" :key="v.id">
      <Form :label-width="80">
        <Col span="22">
          <Col span="8">
            <div class="ma-nomb-spacing">
              <FormItem label="商品">
                <Select @on-change="getfromid" v-model="fromdata[i].goods_id" >
                  <Option v-for="(list,index) in goodsLists" :value="list.id" :key="list.id">{{list.name}}</Option>
                </Select>
              </FormItem>
            </div>
          </Col>
          <Col span="8">
            <div class="ma-nomb-spacing">
              <FormItem label="采购数量">
                <Input v-model="fromdata[i].amount"></Input>
              </FormItem>
            </div>
          </Col>
          <Col span="8">
            <div class="ma-nomb-spacing">
              <FormItem label="采购单价">
                <Input v-model="fromdata[i].unit_price"></Input>
              </FormItem>
            </div>
          </Col>
        </Col>
        <Col span="2">
          <div class="iconfont icon_delete iconshanchuqq" @click="goodsarray('delete',i)"></div>
        </Col>
      </Form>
    </Row>
    <Col span="12">
      <div class="ma-spacing">
        <Button type="success" @click="save()">提交申请</Button>
      </div>
    </Col>
    <Col span="12">
      <div class="ma-spacing">
        <Button type="success" @click="goodsarray('add')">新增</Button>
      </div>
    </Col>
  </Row>
</template>

<script>
    export default {
      name: "add",
      data(){
          return {
            formSend: {
              name: '',
            },
            fromdata:[
              {
                goods_id:'',  //商品id
                amount:'',  //采购数量
                unit_price:'' //采购单价
              },
            ],
            goodsLists:''
          }
      },
      created () {
        this.getGoodsLists()
        console.log(this.fromdata[0]['goods_id'])
      },
      methods:{
        getfromid(val){
          console.log(123)
          console.log(val)
        },
        //新增商品
        goodsarray(judge,index){
          console.log(index)

          if (judge =='add'){
            let list={
              goods_id:'',  //商品id
              amount:'',  //采购数量
              unit_price:'' //采购单价
            };
            this.fromdata.push(list)
          } else {
            if (this.fromdata.length>1){
              this.fromdata.splice(index,1)
            }else {
              console.log('不能再减少了哦亲')
            }
          }
        },
        //获取商品列表
        async getGoodsLists(){
          const _this =this
          let res = await _this.$axios('goods/getGoodsLists',{})
          console.log(res)
          this.goodsLists = res.data
        },
        //提交请求
        async save(){
          const _this =this
          let res = await _this.$axios('purchaseOrder/apply',{order_id:0,data:this.fromdata})
          console.log(res)
        }
      }
    }
</script>

<style scoped>

</style>
