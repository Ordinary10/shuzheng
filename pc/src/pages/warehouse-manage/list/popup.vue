<template>
  <Row v-if="this.ApplyData">
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
        <Col span="24" v-for="(list,index) in ApplyData.detail" :key="list.id">
          <Row>
            <Col span="6">
              <div class="ma-spacing">
                商品名称：<span class="key_text">{{list.name}}</span>
              </div>
            </Col>
            <Col span="6">
              <div class="ma-spacing">
                买入金额：<span class="key_text">{{list.buy_amount}}</span>
              </div>
            </Col>
            <Col span="6">
              <div class="ma-spacing">
                申请金额：<span class="key_text">{{list.apply_amount}}</span>
              </div>
            </Col>
            <Col span="6">
              <div class="ma-spacing">
                购买金额：<span class="key_text">{{list.buy_money}}</span>
              </div>
            </Col>
            <Col span="6">
              <div class="ma-spacing">
                预计金额：<span class="key_text">{{list.estimated_money}}</span>
              </div>
            </Col>
            <Col span="6">
              <div class="ma-spacing">
                单位：<span class="key_text">{{list.buy_amount}}</span>
              </div>
            </Col>
            <Col span="6">
              <div class="ma-spacing">
                单价：<span class="key_text">{{list.buy_money}}</span>
              </div>
            </Col>
            <Col span="6">
              <div class="ma-spacing">
                商品条码：<span class="key_text">{{list.bar_code}}</span>
              </div>
            </Col>
            <Col span="6">
              <div class="ma-spacing">
                仓库：<span class="key_text">{{list.place}}</span>
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
        <Col span="24">
          <div class="ma-spacing">
            总金额：<span class="key_text">{{ApplyData.total_amount}}</span>
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
    <Row v-else>
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
          <Form :ref="'seeData' + index" :model="list" :rules="ruleInline" :label-width="80">
            <Col span="6">
              <div class="ma-nomb-spacing">
                <FormItem label="商品名称" prop="name">
                  <Input v-model="list.name"></Input>
                </FormItem>
              </div>
            </Col>
            <Col span="6">
              <div class="ma-nomb-spacing">
                <FormItem label="买入数量" prop="name">
                  <Input v-model="list.buy_amount"></Input>
                </FormItem>
              </div>
            </Col>
            <Col span="6">
              <div class="ma-nomb-spacing">
                <FormItem label="申请金额"prop="name">
                  <Input v-model="list.apply_amount"></Input>
                </FormItem>
              </div>
            </Col>
            <Col span="6">
              <div class="ma-nomb-spacing">
                <FormItem label="购买金额"prop="buy_money">
                  <Input v-model="list.buy_money"></Input>
                </FormItem>
              </div>
            </Col>
            <Col span="6">
              <div class="ma-nomb-spacing">
                <FormItem label="预计金额"prop="estimated_money">
                  <Input v-model="list.estimated_money"></Input>
                </FormItem>
              </div>
            </Col>
            <Col span="6">
              <div class="ma-nomb-spacing">
                <FormItem label="单位"prop="unit">
                  <Input v-model="list.unit"></Input>
                </FormItem>
              </div>
            </Col>
            <Col span="6">
              <div class="ma-nomb-spacing">
                <FormItem label="单价"prop="unit_price">
                  <Input v-model="list.unit_price"></Input>
                </FormItem>
              </div>
            </Col>
            <Col span="6">
              <div class="ma-nomb-spacing">
                <FormItem label="商品条码"prop="bar_code">
                  <Input v-model="list.bar_code"></Input>
                </FormItem>
              </div>
            </Col>
            <Col span="6">
              <div class="ma-nomb-spacing">
                <FormItem label="仓库"prop="place">
                  <Input v-model="list.place"></Input>
                </FormItem>
              </div>
            </Col>
          </Form>
          <Divider />
        </Col>
      </Row>
      <Col span="24">
        <div class="ma-spacing">
          <Input v-model="storageremark" type="textarea" placeholder="出库备注备注" />
        </div>
      </Col>

      <Col span="24">
        <div class="ma-spacing">
          <Button type="success" @click="handleSubmit()">出库</Button>
        </div>
      </Col>
    </Row>
  </Row>
</template>

<script>
  export default {
    name: "popup",
    props: {
      ApplyData: {
      },
      id: {},
      ruleInline: {
        name: [
          { required: true, message: '请输入内容', trigger: 'blur' }
        ],
        buy_amount: [
          { required: true, message: '请输入购买数量', trigger: 'blur' }
        ],
        apply_amount: [
          { required: true, message: '请输入申请金额', trigger: 'blur' }
        ],
        buy_money: [
          { required: true, message: '请输入购买金额', trigger: 'blur' }
        ],
        estimated_money: [
          { required: true, message: '请输入预计金额', trigger: 'blur' }
        ],
        unit: [
          { required: true, message: '请输入单位', trigger: 'blur' }
        ],
        unit_price: [
          { required: true, message: '请输入单价', trigger: 'blur' }
        ],
        bar_code: [
          { required: true, message: '请输入商品条码', trigger: 'blur' }
        ],
        place: [
          { required: true, message: '请输入仓库', trigger: 'blur' }
        ],
      },

    },
    data(){
      return {
        seeData:'',
        examineremark:'',
        storageremark:''
      }
    },
    created () {
      this.seeData = this.ApplyData.detail
    },
    methods:{
      //点击出库进行表单验证
      handleSubmit() {
        console.log(this.$refs);
        let arr = [];
        this.seeData.forEach((data, key) => {
          let form = 'seeData' + key;
          console.log([form][0],key)

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
      //审核请求
      async examine(type,remark){
        const _this = this
        let res = await _this.$axios('Checkout/verify', {verify_status:type,remark:remark,order_id:this.ApplyData.id})
        console.log(res)
      },
      //请求出库
      async storage(){
        const _this = this
        let res = await _this.$axios('checkout/distribute', {order_id: this.ApplyData.id,remark:this.storageremark,data:this.seeData})
        console.log(res)
      }
    }

  }
</script>

<style scoped>

</style>
