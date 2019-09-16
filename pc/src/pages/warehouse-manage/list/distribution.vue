<template>
  <Row>
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
    <Row class="storage-body roll">
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
        <Form :ref="'seeData' + index" :model="list" :rules="ruleInline" :label-width="80">
          <Col span="6">
            <div class="ma-nomb-spacing">
              <FormItem label="出库条码"prop="bar_code">
                <Input v-model="list.bar_code"></Input>
              </FormItem>
            </div>
          </Col>
          <Col span="6">
            <div class="ma-nomb-spacing">
              <FormItem label="数量"prop="num">
                <Input v-model="list.num"></Input>
              </FormItem>
            </div>
          </Col>
        </Form>
        <Divider />
      </Col>
    </Row>
    <Row>
      <Col span="24">
        <div class="ma-spacing">
          <Input v-model="storageremark" type="textarea" placeholder="配货备注" />
        </div>
      </Col>
      <Col span="24">
        <div class="ma-spacing">
          <Button type="success" @click="handleSubmit()">配货</Button>
        </div>
      </Col>
    </Row>
  </Row></template>

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
        storageremark:'',
        ruleInline: {
          bar_code: [
            { required: true, message: '请填写出库条码', trigger: 'blur' }
          ],
          num: [
            { required: true, message: '请填写数量', trigger: 'blur' }
          ],
        },

      }
    },
    created () {
      this.seeData = this.ApplyData.detail_info
      this.commoData = JSON.parse(JSON.stringify(this.seeData))
    },
    methods:{
      //点击配货进行表单验证
      handleSubmit() {
        let arr = [];
        this.seeData.forEach((data, key) => {
          let form = 'seeData' + key;
          this.$refs[form][0].validate((valid) => {
            if (valid) {
              arr.push(true);
            } else {
              arr.push(false);
            }
          });
        });
        let flag = arr.every((item) => {
          return item === true;
        });
        if (flag) {
          this.storage()
        } else {
          this.$Message.error('请填写相关内容');
        }
      },
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
      },
      //请求配货
      async storage(){
        const _this = this
        let data = [];
        let admins={
          place:'',
          bar_code:'',
          detail_id:'',
        };
        this.seeData.map((value,index,arry)=>{
          data.push({
            num:value.num,
            bar_code:value.bar_code,
            })
        })
        let res = await _this.$axios('checkout/distribute', {order_id: this.ApplyData.id,data:data})
        this.$parent.$parent.modasear(res,'modal3');

      }
    }
  }
</script>

<style scoped>

</style>
