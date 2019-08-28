<template>
  <Row>
    <Col span="8">
      <div class="card-body">
        <div class="card">
          <Row class="ma-lr">
            <div class="ma-nomb-spacing">
              <div class="bt-top">
                表格标题
              </div>
            </div>
            <Form :model="formLeft" label-position="right" :label-width="80">
              <Col span="12">
                <div class="ma-nomb-spacing">
                  <FormItem label="姓名">
                    <Input v-model="formLeft.input1"></Input>
                  </FormItem>
                </div>
              </Col>
              <Col span="12">
                <div class="ma-nomb-spacing">
                  <FormItem label="年龄">
                    <Input v-model="formLeft.input1"></Input>
                  </FormItem>
                </div>
              </Col>
              <Col span="12">
                <div class="ma-nomb-spacing">
                  <FormItem label="年龄">
                    <Input v-model="formLeft.input1"></Input>
                  </FormItem>
                </div>
              </Col>
              <Col span="12">
                <div class="ma-nomb-spacing">
                  <FormItem label="年龄">
                    <Input v-model="formLeft.input1"></Input>
                  </FormItem>
                </div>
              </Col>
            </Form>
          </Row>
        </div>
      </div>
    </Col>

    <Col span="8">
      <div class="card-body">
        <div class="card">
          <Row class="ma-lr">
            <div class="ma-nomb-spacing">
              <div class="bt-top">
                表格标题
              </div>
            </div>
            <Form :model="formLeft" label-position="top"  >
              <Col span="12">
                <div class="ma-nomb-spacing">
                  <FormItem label="姓名">
                    <Input v-model="formLeft.input1"></Input>
                  </FormItem>
                </div>
              </Col>
              <Col span="12">
                <div class="ma-nomb-spacing">
                  <FormItem label="年龄">
                    <Input v-model="formLeft.input1"></Input>
                  </FormItem>
                </div>
              </Col>
              <Col span="12">
                <div class="ma-nomb-spacing">
                  <FormItem label="年龄">
                    <Input v-model="formLeft.input1"></Input>
                  </FormItem>
                </div>
              </Col>
              <Col span="12">
                <div class="ma-nomb-spacing">
                  <FormItem label="年龄">
                    <Input v-model="formLeft.input1"></Input>
                  </FormItem>
                </div>
              </Col>
            </Form>
          </Row>
        </div>
      </div>
    </Col>
    <Col span="9">
      <div class="card-body-nob">
        <div class="card">
          <Row class="vehicle-text vehicle-bg">
            <Row class="vehile-ma-l">
              <Col span="8">
                <div class="ma-nomb-spacing">
                  车型：<span class=""></span>
                </div>
              </Col>
              <Col span="8">
                <div class="ma-nomb-spacing">
                  车牌：<span class=""></span>
                </div>
              </Col>
              <Col span="8">
                <div class="ma-nomb-spacing">
                  门店：<span class=""></span>
                </div>
              </Col>
            </Row>
          </Row>
          <Row class="vehicle-text">
            <Row class="vehile-ma-l">
              <Col span="8">
                <div class=" underline">
                  车型：<span class=""></span>
                </div>
              </Col>
              <Col span="8">
                <div class=" underline">
                  车牌：<span class=""></span>
                </div>
              </Col>
              <Col span="8">
                <div class=" underline">
                  门店：<span class=""></span>
                </div>
              </Col>
            </Row>
          </Row>
          <Row class="vehicle-text">
            <Row class="vehile-ma-l">
              <Col span="8">
                <div class="underline">
                  车型：<span class=""></span>
                </div>
              </Col>
              <Col span="8">
                <div class="underline">
                  车牌：<span class=""></span>
                </div>
              </Col>
              <Col span="8">
                <div class="underline">
                  门店：<span class=""></span>
                </div>
              </Col>
            </Row>
          </Row>
          <div class="vehicle-iconbg">
            <div class="vehicle-icon">
              <i class="iconfont iconcheliangxinxi fs-28"></i>
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="81px" height="82px">
              <defs>
                <filter filterUnits="userSpaceOnUse" id="Filter_0" x="0px" y="0px" width="81px" height="82px">
                  <feOffset in="SourceAlpha" dx="0" dy="4"></feOffset>
                  <feGaussianBlur result="blurOut" stdDeviation="3"></feGaussianBlur>
                  <feFlood flood-color="rgb(0, 0, 0)" result="floodOut"></feFlood>
                  <feComposite operator="atop" in="floodOut" in2="blurOut"></feComposite>
                  <feComponentTransfer>
                    <feFuncA type="linear" slope="0.19"></feFuncA>
                  </feComponentTransfer>
                  <feMerge>
                    <feMergeNode></feMergeNode>
                    <feMergeNode in="SourceGraphic"></feMergeNode>
                  </feMerge>
                </filter>
              </defs>
              <g filter="url(#Filter_0)">
                <path fill-rule="evenodd" fill="rgb(18, 105, 219)" d="M5.000,69.987 L5.000,9.000 C5.000,6.791 6.791,5.000 9.000,5.000 L69.000,5.000 C69.000,5.000 69.000,5.000 69.000,5.000 C69.000,40.564 40.437,69.452 5.000,69.987 Z"></path>
              </g>
            </svg>
          </div>
          <p class="data-btn vehicle-btn">展<br>开<br>资<br>料<br></p>
        </div>
      </div>
    </Col>
    <Col span="9">
      <div class="card-body-nob">
        <div class="card">
          <Row class="vehicle-text vehicle-bg user-top">
            <Row class="vehile-ma-l">
              <Col span="8">
                <div class="ma-nomb-spacing">
                  车型：<span class=""></span>
                </div>
              </Col>
              <Col span="8">
                <div class="ma-nomb-spacing">
                  车牌：<span class=""></span>
                </div>
              </Col>
              <Col span="8">
                <div class="ma-nomb-spacing">
                  门店：<span class=""></span>
                </div>
              </Col>
            </Row>
          </Row>
          <Row class="vehicle-text">
            <Row class="vehile-ma-l">
              <Col span="8">
                <div class=" underline">
                  车型：<span class=""></span>
                </div>
              </Col>
              <Col span="8">
                <div class=" underline">
                  车牌：<span class=""></span>
                </div>
              </Col>
              <Col span="8">
                <div class=" underline">
                  门店：<span class=""></span>
                </div>
              </Col>
            </Row>
          </Row>
          <Row class="vehicle-text">
            <Row class="vehile-ma-l">
              <Col span="8">
                <div class="underline">
                  车型：<span class=""></span>
                </div>
              </Col>
              <Col span="8">
                <div class="underline">
                  车牌：<span class=""></span>
                </div>
              </Col>
              <Col span="8">
                <div class="underline">
                  门店：<span class=""></span>
                </div>
              </Col>
            </Row>
          </Row>
          <div class="vehicle-iconbg">
            <div class="vehicle-icon">
              <i class="iconfont iconyonghu fs-24"></i>
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="81px" height="82px">
              <defs>
                <filter filterUnits="userSpaceOnUse" id="Filter_0" x="0px" y="0px" width="81px" height="82px">
                  <feOffset in="SourceAlpha" dx="0" dy="4"></feOffset>
                  <feGaussianBlur result="blurOut" stdDeviation="3"></feGaussianBlur>
                  <feFlood flood-color="rgb(0, 0, 0)" result="floodOut"></feFlood>
                  <feComposite operator="atop" in="floodOut" in2="blurOut"></feComposite>
                  <feComponentTransfer>
                    <feFuncA type="linear" slope="0.19"></feFuncA>
                  </feComponentTransfer>
                  <feMerge>
                    <feMergeNode></feMergeNode>
                    <feMergeNode in="SourceGraphic"></feMergeNode>
                  </feMerge>
                </filter>
              </defs>
              <g filter="url(#Filter_0)">
                <path fill-rule="evenodd" fill="rgb(252, 97, 128)" d="M5.000,69.987 L5.000,9.000 C5.000,6.791 6.791,5.000 9.000,5.000 L69.000,5.000 C69.000,5.000 69.000,5.000 69.000,5.000 C69.000,40.564 40.437,69.452 5.000,69.987 Z"></path>
              </g>
            </svg>
          </div>
          <p class="data-btn vehicle-btn">展<br>开<br>资<br>料<br></p>
        </div>
      </div>
    </Col>
    <Col span="9">
      <vehicleHead></vehicleHead>
    </Col>
    <Col span="12">
      <div class="card-body">
        <div class="card">
          <Row class="ma-lr">
            <div class="ma-nomb-spacing tabs-body">
              <div class="bt-top">
                表格标题
              </div>
              <Tabs value="name1">
                <TabPane label="标签一" name="name1">标签一的内容</TabPane>
                <TabPane label="标签二" name="name2">标签二的内容</TabPane>
                <TabPane label="标签三" name="name3">标签三的内容</TabPane>
              </Tabs>
            </div>
          </Row>
        </div>
      </div>
    </Col>
    <Col span="12">
      <i class="iconfont iconzanwu" style="font-size: 100px"></i>
      暂时没有记录哦
    </Col>
  </Row>

</template>
<script>
  import vehicleHead from '../../components/headPublic/vehicleHead'
  // import {reqSmsLogin} from '../../api'
  export default {
    data () {
      return {
        columns1: [
          {
            title: 'Name',
            key: 'name'
          },
          {
            title: 'Age',
            key: 'age'
          },
          {
            title: 'Address',
            key: 'address'
          }
        ],
        formLeft: {
          input1: '',
          input2: '',
          input3: ''
        },
      }
    },
    mounted(){
      this.login()
    },
    created(){
    },
    methods: {
      async login () {
        let result
        var phone = 1;
        var code = 2;
        result = await reqSmsLogin(phone, code)
      }
    },
    components:{
      vehicleHead,
    }

  }
</script>
<style scoped lang="scss">


</style>
