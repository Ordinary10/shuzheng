<template>
  <Row>
    <Col span="12">

      <div class="card-body">
        <div class="card-header">
          <p class="card-title">基本信息</p>
        </div>
        <div class="card">
          <Row class="ma-lr top-p">
            <Col span="12" v-for="(list,index) in vehic_data" :key="list.id">
              <div class="ma-spacing">
                {{list.name}}：<span class="key_text">{{list.value}}</span>
              </div>
            </Col>
            <Col span="24" v-for="(list,index) in basic_img() ":key="list.id">
              <div class="ma-nomb-spacing">
                <div class="bt-no-top">
                  {{list.name}}
                </div>
                <ImgUpload v-if="list.img_config.oldImg[0].url !='暂无'" :config="list.img_config"></ImgUpload>
                <div v-else>
                  <i class="iconfont iconzanwu" style="font-size: 73px"></i>
                  暂时没有{{list.name}}照片哦
                </div>
              </div>
            </Col>
          </Row>
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
              <Tabs value="name1">
                <TabPane label="购车信息" name="name1">
                  <Col span="12" v-for="(list,index) in purchase_data" :key="list.id">
                    <div class="ma-spacing">
                      {{list.name}}：<span class="key_text">{{list.value}}</span>
                    </div>
                  </Col>
                  <Col span="24" v-for="(list,index) in buy_img() ":key="list.id">
                    <div class="ma-nomb-spacing">
                      <div class="bt-no-top">
                        {{list.name}}
                      </div>
                      <ImgUpload v-if="list.img_config.oldImg[0].url !='暂无'" :config="list.img_config"></ImgUpload>
                      <div v-else>
                        <i class="iconfont iconzanwu" style="font-size: 73px"></i>
                        暂时没有{{list.name}}照片哦
                      </div>
                    </div>
                  </Col>
                </TabPane>
                <TabPane label="GPS信息" name="name2">
                  <Row>
                    <div class="ma-nomb-spacing">
                      <div class="bt-no-top">
                        无线设备
                      </div>
                    </div>
                    <Col span="12" v-for="(list,index) in gps_wx" :key="list.id">
                      <div class="ma-spacing">
                        {{list.name}}：<span class="key_text">{{list.value}}</span>
                      </div>
                    </Col>
                  </Row>
                  <Row>
                    <div class="ma-nomb-spacing">
                      <div class="bt-no-top">
                        有线设备
                      </div>
                    </div>
                    <Col span="12" v-for="(list,index) in gps_yx" :key="list.id">
                      <div class="ma-spacing">
                        {{list.name}}：<span class="key_text">{{list.value}}</span>
                      </div>
                    </Col>
                  </Row>
                  <Row>
                    <div class="ma-nomb-spacing">
                      <div class="bt-no-top">
                        GPS安装照
                      </div>
                    </div>

                  </Row>
                  <Row>
                    <div class="ma-nomb-spacing">
                      <div class="bt-no-top">
                        GPS设备状态照
                      </div>
                    </div>
                  </Row>
                </TabPane>
                <TabPane label="车钥匙信息" name="name3">
                  <Col span="12" v-for="(list,index) in keys" :key="list.id">
                    <div class="ma-spacing">
                      {{list.name}}：<span class="key_text">{{list.value}}</span>
                    </div>
                  </Col>
                </TabPane>
                <TabPane label="租赁记录" name="name4">
                    <div class="ma-spacing ">
                      <div class="tab-box">
                        <paging-table ref="pagingTable" :config="config" :searchData="searchData"></paging-table>
                      </div>
                    </div>
                </TabPane>
                <TabPane label="违章记录" name="name5">
                  <div class="ma-spacing ">
                    <div class="tab-box">
                      <paging-table ref="pagingTable" :config="detailConfig" :searchData="searchData"></paging-table>
                    </div>
                  </div>
                </TabPane>
              </Tabs>
          </Row>
        </div>
      </div>
    </Col>
  </Row>
</template>

<script>
import ImgUpload from '../../../components/ImgUpload'
export default {
  name: 'popup',
  props: {
    popupData: {
    },
    id: {}
  },
  data () {
    return {
      addData: '',
      dis_no: [false, false],
      detailConfig: {
        fun: 'Order/getIllegalByOrder',
        columns: [
          {
            key: 'ig_time',
            title: '日期'
          },
          {
            key: 'ig_address',
            title: '地点'
          },
          {
            key: 'ig_content',
            title: '违章'
          },
          {
            key: 'ig_fine',
            title: '罚款（元）'
          }
        ]
      },
      config: {
        fun: 'Order/getHistoryByOrder',
        columns: [
          {
            key: 'lease_time',
            title: '签约日期'
          },
          {
            key: 'periods',
            title: '期数'
          },
          {
            key: 'name',
            title: '金额'
          },
          {
            key: 'status',
            title: '状态'
          }
        ]
      },
      searchData: {
        id: this.popupData.car_id
      }
    }
  },
  created () {
    this.getlinst()
  },
  components: {
    ImgUpload
  },
  computed: {
    vehic_data: function () {
      var data = {
        plate_no: '车牌号码',
        dp_id_name: '门店',
        car_householder_name: '户主',
        type_name: '车辆类型',
        version_id_name: '品牌车型',
        color: '车身颜色',
        vin: '车架号',
        engine_no: '发动机号',
        on_plate: '注册时间',
        is_service_name: '营运性质',
        plate_city: '上牌城市',
        is_gas_name: '是否气罐',
        car_type_name: '车牌类型',
        storage_name: '车库',
        status_name: '状态',
        warehouse_place: '仓位',
        desc: '备注'
      }
      return this.clean(data, this.addData)
    },
    purchase_data: function () {
      if (this.addData) {
        let data = {
          buy_amount: '购车金额',
          tax_amount: '购置税金额'
        }
        return this.clean(data, this.addData)
      }
    },
    gps_wx: function () {
      if (this.addData) {
        let data = {
          device_number: '设备编号',
          device_manufacturer: '厂商',
          ctime: '续费日',
          status: 'GPS状态'
        }

        return this.clean(data, this.addData.gps_info[0])
      }
    },
    keys: function () {
      let data = {
        car_keys: '车钥匙数量'
      }
      return this.clean(data, this.addData)
    },
    gps_yx: function () {
      if (this.addData) {
        let data = {
          device_number: '设备编号',
          device_manufacturer: '厂商',
          ctime: '续费日',
          status: 'GPS状态'
        }
        return this.clean(data, this.addData.gps_info[0])
      }
    }
  },
  methods: {
    async getlinst () {
      const _this = this
      var res = await _this.$axios('car/getDetailInfo', {car_id: this.popupData.car_id})
      // 先将空数据全部改为暂无
      for (let k in res.data) {
        if (!res.data[k]) {
          res.data[k] = '暂无'
        }
      }
      this.addData = res.data
    },
    basic_img:function(){
        let data_img = {
          license_img:'行驶证照片',
          certificate_img:'合格证照片',
          register_img:'登记照片',
          car_img:'车身照'
        }
      return this.clean_img(this.addData,data_img)
    },
    buy_img:function(){
        let data_img = {
          buy_bill_img:'购车发票',
          tax_bill_img:'购置税发票',
        }
        return this.clean_img(this.addData,data_img)
    },
    clean (list, data) {
      if (data) {
        let newarray = []
        for (let key in list) {
          newarray.push({name: list[key], value: data[key], data_name: key, hover: data[key]})
        }
        return newarray
      }
    },

    clean_img (data,img) {
      if (this.addData){
        let newarray = []
        for (let k in img){
          let list=[];
          let img_list = data[k].split(',')

          for (let key in img_list){
            list.push({url:img_list[key]})
          }
          newarray.push({name:img[k],data_name:k,img_config:{onlyShow:true,oldImg:list}})

        }
      console.log(newarray)
        return newarray
      }
    }
  }
}
</script>

<style scoped>

</style>
