<template>
  <Row v-if="this.addData">
    <Col span="12">
      <div class="card-body-nob">
        <div class="card">
          <Row class="vehicle-text vehicle-bg">
            <Row class="vehile-ma-l">
              <Col span="8" v-for="(list,index) in toplist" :key="list.id">
                <div class="ma-nomb-spacing">
                  <Tooltip top="Here is the prompt text">
                    <div slot="content">
                      <p>{{list.value}}</p>
                    </div>
                  {{list.name}}：<span class="">{{list.value}}</span>
                  </Tooltip>

                </div>
              </Col>
            </Row>
          </Row>
          <Row class="vehicle-text">
            <Row class="vehile-ma-l" >
              <Col span="8" v-for="(list,index) in addlist" :key="list.id">
                <div class="ma-nomb-spacing">
                  <Tooltip content="Bottom Center text" >
                    <div slot="content">
                      <p>{{list.hover}}</p>
                    </div>
                    {{list.name}}：<span :class="list.state">{{list.value}}</span>
                  </Tooltip>
                </div>
              </Col>
            </Row>
          </Row>
          <Row class="vehicle-text" v-if="dis_no[0]">
            <Row class="vehile-ma-l" >
              <Col span="8" v-for="(list,index) in addlist_surplus" :key="list.id">
                <div class="ma-nomb-spacing">
                  <Tooltip content="Bottom Center text" >
                    <div slot="content">
                      <p>{{list.hover}}</p>
                    </div>
                    {{list.name}}：<span class="">{{list.value}}</span>
                  </Tooltip>
                </div>
              </Col>
            </Row>
          </Row>
          <div class="vehicle-iconbg">
            <div class="vehicle-icon">
              <i class="iconfont iconcheliangxinxi fs-28"></i>
            </div>
            <svg data-v-cb35e4d4="" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="81px" height="82px"><defs data-v-cb35e4d4=""><filter data-v-cb35e4d4="" filterUnits="userSpaceOnUse" id="Filter_0" x="0px" y="0px" width="81px" height="82px"><feOffset data-v-cb35e4d4="" in="SourceAlpha" dx="0" dy="4"></feOffset> <feGaussianBlur data-v-cb35e4d4="" result="blurOut" stdDeviation="3"></feGaussianBlur> <feFlood data-v-cb35e4d4="" flood-color="rgb(0, 0, 0)" result="floodOut"></feFlood> <feComposite data-v-cb35e4d4="" operator="atop" in="floodOut" in2="blurOut"></feComposite> <feComponentTransfer data-v-cb35e4d4=""><feFuncA data-v-cb35e4d4="" type="linear" slope="0.19"></feFuncA></feComponentTransfer> <feMerge data-v-cb35e4d4=""><feMergeNode data-v-cb35e4d4=""></feMergeNode> <feMergeNode data-v-cb35e4d4="" in="SourceGraphic"></feMergeNode></feMerge></filter></defs> <g data-v-cb35e4d4="" filter="url(#Filter_0)"><path data-v-cb35e4d4="" fill-rule="evenodd" fill="rgb(18, 105, 219)" d="M5.000,69.987 L5.000,9.000 C5.000,6.791 6.791,5.000 9.000,5.000 L69.000,5.000 C69.000,5.000 69.000,5.000 69.000,5.000 C69.000,40.564 40.437,69.452 5.000,69.987 Z"></path></g></svg>
          </div>
          <p class="data-btn vehicle-btn"
             @click="activeClass('veh')"
          >展<br>开<br>资<br>料<br></p>
        </div>
      </div>
    </Col>
    <Col span="12">
      <div class="card-body-nob">
        <div class="card">
          <Row class="vehicle-text vehicle-bg user-top">
            <Row class="vehile-ma-l">
              <Col span="8" v-for="(list,index) in topuser" :key="list.id">
                <div class="ma-nomb-spacing">
                  <Tooltip content="Bottom Center text" >
                    <div slot="content">
                      <p>{{list.hover}}</p>
                    </div>
                    {{list.name}}：<span class="">{{list.value}}</span>
                  </Tooltip>
                </div>

              </Col>
            </Row>
          </Row>
          <Row class="vehicle-text">
            <Row class="vehile-ma-l">
              <Col span="8" v-for="(list,index) in userlist" :key="list.id">
                <div class="ma-nomb-spacing">
                  <Tooltip content="Bottom Center text" >
                    <div slot="content">
                      <p>{{list.hover}}</p>
                    </div>
                    {{list.name}}：<span class="">{{list.value}}</span>
                  </Tooltip>
                </div>
              </Col>
            </Row>
          </Row>
          <Row class="vehicle-text" v-if="dis_no[1]">
            <Row class="vehile-ma-l">
              <Col span="8" v-for="(list,name) in userlist_surplus" :key="list.id">
                <div class="ma-nomb-spacing">
                  <Tooltip content="Bottom Center text" >
                    <div slot="content">
                      <p>{{list.hover}}</p>
                    </div>
                    {{list.name}}：<span class="">{{list.value}}</span>
                  </Tooltip>
                </div>
              </Col>
            </Row>
          </Row>
          <div class="vehicle-iconbg">
            <div class="vehicle-icon">
              <i class="iconfont iconyonghu fs-24"></i>
            </div>
            <svg data-v-4aeec2ca="" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="81px" height="82px"><defs data-v-4aeec2ca=""><filter data-v-4aeec2ca="" filterUnits="userSpaceOnUse" id="Filter_0" x="0px" y="0px" width="81px" height="82px"><feOffset data-v-4aeec2ca="" in="SourceAlpha" dx="0" dy="4"></feOffset> <feGaussianBlur data-v-4aeec2ca="" result="blurOut" stdDeviation="3"></feGaussianBlur> <feFlood data-v-4aeec2ca="" flood-color="rgb(0, 0, 0)" result="floodOut"></feFlood> <feComposite data-v-4aeec2ca="" operator="atop" in="floodOut" in2="blurOut"></feComposite> <feComponentTransfer data-v-4aeec2ca=""><feFuncA data-v-4aeec2ca="" type="linear" slope="0.19"></feFuncA></feComponentTransfer> <feMerge data-v-4aeec2ca=""><feMergeNode data-v-4aeec2ca=""></feMergeNode> <feMergeNode data-v-4aeec2ca="" in="SourceGraphic"></feMergeNode></feMerge></filter></defs> <g data-v-4aeec2ca="" filter="url(#Filter_0)"><path data-v-4aeec2ca="" fill-rule="evenodd" fill="rgb(252, 97, 128)" d="M5.000,69.987 L5.000,9.000 C5.000,6.791 6.791,5.000 9.000,5.000 L69.000,5.000 C69.000,5.000 69.000,5.000 69.000,5.000 C69.000,40.564 40.437,69.452 5.000,69.987 Z"></path></g></svg>
          </div>
          <p class="data-btn vehicle-btn"
             @click="activeClass()"
          >展<br>开<br>资<br>料<br></p>
        </div>
      </div>
    </Col>
    <Col span="12">

      <div class="card-body">
        <div class="card-header">
          <p class="card-title">车辆信息</p>
        </div>
        <div class="card">

          <Row class="ma-lr">
            <div class="ma-nomb-spacing">
              <div class="bt-top">
                基本信息
              </div>
            </div>
            <div class="ma-nomb-spacing ma-top">
            </div>
          </Row>
        </div>
      </div>
    </Col>

    <Col span="12">

      <div class="card-body">
        <div class="card-header">
            <p class="card-title">表格标题</p>
        </div>
        <div class="card">
          <Row class="ma-lr">
            <div class="ma-nomb-spacing ma-top">
              <Table :columns="columns1" ></Table>
            </div>
          </Row>
        </div>
      </div>
    </Col>
  </Row>


</template>

<script>
  export default {
    name: 'userVehicleHead',
    props:{
      param:{
      }
    },
    data:function(){
      return {
        addData:'',
        dis_no:[false,false],
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
      }
    },
    created(){
      this.getlinst()
    },
    computed:{
      addlist:function () {
        var addlist_data = {car_status:'车辆',illegal_info:'违章',insurance_etime_day:'保单',annual:'车审',license_annual:'证审',is_gas:'气罐'}
        return this.cleans(addlist_data,this.addData)
      },
      //清洗数据
      addlist_surplus:function () {
        var addlist_data = {vin:'车架号'}
        return this.clean(addlist_data,this.addData)
      },
      toplist:function () {
        var toplist_data = {brand:'车型',plate_no:'车牌',company:'门店'}
        return this.clean(toplist_data,this.addData)
      },
      topuser:function () {
        var toplist_data = {name:'姓名',mobile:'电话',driver_manage:'司管'}
        return this.clean(toplist_data,this.addData)
      },
      userlist:function () {
        var userlist_data = {monthly_fee:'月供(元)',periods:'期限(月)',type_name:'产品类型',status:'还款状态',deposit_fee:'履约保证金',on_account_fee:'挂账'}
        return this.clean(userlist_data,this.addData)
      },
      userlist_surplus:function () {
        // var userlist_datas= {'紧急联系人':'customer_contact','地址':'address','评分':'driver_star'}
        // var userlist_datas= {'月供(元)':'monthly_fee'}
        var userlist_data = {customer_contact:'紧急联系人',address:'地址',driver_star:'评分'}
        return this.cleans(userlist_data,this.addData)
      },
    },
    methods:{
       async getlinst() {
         const _this = this
         var res  = await _this.$axios('Common/getCommonalityHead',{plate_no:this.param})
         //先将空数据全部改为暂无
         for (let k in res.data){
           if (!res.data[k]){
             res.data[k]='暂无'
           }
         }
         this.addData  = res.data
         console.log(res.data)
      },
      //展开切换
      activeClass(v){
         if (v=='veh') {
           this.dis_no[0] = !this.dis_no[0]
         }else {
           this.dis_no[1] = !this.dis_no[1]
         }
        this.dis_no.push(true)
        this.dis_no.pop();
      },
      //不需要判断状态的进行清洗数据
      clean(list,data){
         if (data){
           let newarray = []
           for (let key in list){
             newarray.push({name:list[key],value:data[key],data_name:key,hover:data[key]})
           }
           console.log(newarray)
           return newarray
         }
      },
      cleans(list,data){
        if (data){
          var newarray = []
          let state = 'a', //a默认状态，redtext红色，green-color绿色，3蓝色
            violationState,
            hover_text,
            red = 'redtext',
            gre = 'green-color',
            value;
          //先清洗数据，与传过来的值对比，交集则留下
          for (let key in list){
            for (let k in data){
              if (k ==key) {
                value = data[k]
                hover_text = data[k]
                //循环判断是否有违章
                if (k =='illegal_info') {
                  for (let s in data[k]) {
                    if (data[k][s] != 0) {
                      violationState = 1
                      break
                    }
                  }
                  violation(violationState, data[k])
                }
                //车辆有违章信息进行渲染
                function violation(violationState, data) {
                  if (violationState == '1') { //判断有违章
                    value = `${data.ig_fine}元/${data.ig_score}分/${data.ig_nums}条`
                    state = red
                  } else {
                    hover_text =value = `无违章`
                  }
                }
                //紧急联系人判断
                if (k=='customer_contact'){
                  if (data[k].length>0){
                    var address
                    data[k].forEach(e =>{
                      address += `${e.name}&nbsp${e.mobile}&nbsp${e.relation}`
                    })
                    value = address
                  }else {
                    value = '暂无'
                  }
                }
                //判断气罐
                if (k=='is_gas'){
                  var day = data.gastank_annual_day
                  if (day <=0){
                    hover_text = value = `${day}天`
                    state = gre
                  }if (day=='暂无'){
                    hover_text = value = `${day}`
                    state = 'a'
                  }  else {
                    value = `${day}天`
                    state = red
                    hover_text = `${value}/${data.gastank_annual}`
                  }
                }
                //判断保单
                if (k=='insurance_etime_day'){
                  var day = data.insurance_etime_day
                  if (day>0){
                    value = `${day}天`
                    state = red
                    hover_text = `${value}/${data.insurance_etime}`
                  }else {
                    value = `${Math.abs(day)}天`
                    state = gre
                    hover_text = `${value}/${data.insurance_etime}`
                  }
                }
                //判断车审证审
                if (k=='annual' || k=='license_annual'){
                  var day = k == 'annual' ? data.annual_day : data.license_annual_day;
                  var time = k == 'annual' ? data.annual : data.license_annual;
                  vehicleLicense(day, time)
                }
                //判断车审和证审
                function vehicleLicense(day, time,) {
                  if (day <= 0) { //正常
                    value = `${Math.abs(day)}天`
                    hover_text = `${value}/${time}`
                    state =gre
                  } else {
                    value = `${day}天`
                    hover_text = `${value}/${time}`
                    state =red
                  }
                }
                //增加到数组
                newarray.push({name:list[k],value:value,data_name:k,state:state,hover:hover_text})
              }
            }
          }
          console.log(newarray)
          return newarray
        }
      },
    }
  }
</script>

<style scoped>

</style>
