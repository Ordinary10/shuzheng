<template>
  <div class="pages">
    <i-toast id="toast" />
    <div class="detail-box" v-if="orderDetail">
      <div class="detail-list" v-if="orderDetail.detail_info.length>0">
        <div class="divider-title">
          <divider content="配货详情" :css="{'font-size': '17px'}"></divider>
        </div>
        <div class="detail-list-item" v-for="(item,index) in upDateList" :key="item.id">
          <div class="goods-title">
            {{item.name}}
          </div>
          <div class="goods-content">
            <div class="content-item nowrap" style="width: 100%;">
              申请数：{{item.apply_amount}}
              <span @click="addData(item)" class="add_btn" id="add-data">+</span>
            </div>
            <div class="goods-barCode-list">
              <div class="content-item nowrap content-max-item">
                <div class="goods-number">配货数</div>
                <div class="goods-barCode flex_1">条形码</div>
              </div>
              <div class="content-item nowrap content-max-item" v-for="(v,i) in item.data" :key="i">
                <div class="goods-number">
                  <input type="number" v-model="v.num" placeholder="请输入配货数">
                </div>
                <div class="goods-barCode">
                  <input type="text" v-model="v.bar_code" placeholder="请输入或扫描条形码">
                  <icon class="iconfont iconQR-code1" @click="barCode(v)"></icon>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="detail-list-item">
          <textarea v-model="remark" id="textarea" placeholder="请输入配货备注(商品条形码或者数量为空不会被计入配货单)" max="100" ></textarea>
        </div>
      </div>
    </div>
    <div class="details-operation-btns">
      <button type="button" @click="submitEntry" class="large_btn_primary">提交</button>
    </div>
  </div>
</template>
<script>
  import divider from '@/components/divider'
  export default {
    components: {divider},

    data() {
      return {
        order_id: '',
        orderDetail: null,
        searchText: '',
        remark: ''
      }
    },
    created() {
    },
    onShow() {
      if(this.$root.$mp.query.order_id&&this.order_id != this.$root.$mp.query.order_id){
        this.orderDetail = null
        this.order_id = this.$root.$mp.query.order_id
        this.getDetail()
      }
    },
    mounted(){
    },
    computed:{
      upDateList(){
        return this.orderDetail?this.orderDetail.detail_info:[]
      }
    },
    methods:{
      getDetail() {
        const _this = this
        _this.$ajax('checkout/getDetailInfo',{order_id:_this.order_id},function (res) {
          _this.orderDetail =  _this.dataFilter(res.data)
        })
      },
      dataFilter (data) {
        if(data){
          Object.keys(data).forEach(key => {
            if(key == 'ctime' || key =='up_time') {
              data[key] = data[key].split(' ')[0]
            }
            if(key=='detail_info') {
              for(let item of data.detail_info){
                this.$set(item,'data',[{
                  bar_code: '',
                  num: item.apply_amount
                }])
              }
            }
          })
        }
        return data
      },
      barCode(v) {
        const _this = this
        wx.scanCode({
          success (res) {
            v.bar_code = res.result
          },
          fail(){
            _this.$common.error_tip('扫码失败，请重试')
          }
        })
      },
      addData(item){
        item.data.push({
          bar_code: '',
          num: item.apply_amount
        })
        this.orderDetail = JSON.parse(JSON.stringify(this.orderDetail))
        console.log(this.orderDetail)
      },
      submitEntry(){
        const _this = this
        if(_this.remark === ''){
          _this.$common.error_tip('请填写配货备注')
          return
        }
        let detail = []
        for(let v of _this.orderDetail.detail_info){
          for(let val of v.data) {
            if(val.bar_code!==''&&val.num!==''){
              detail.push({
                detail_id: v.id,
                bar_code: val.bar_code,
                num: val.num
              })
            }
          }
        }
        let data = {
          remark: _this.remark,
          order_id: _this.order_id,
          data: detail
        }
        console.log(data)
        _this.$ajax('checkout/distribute',data,function (res) {
          if(res.code === 1){
            _this.$common.success_tip('配货成功',function () {
              wx.navigateBack()
            })
          }
        })
      }
    }
  }
</script>

<style scoped lang="scss">
  .pages{
    height: 100%;
    background-color: #FF4B5B;
    box-sizing: border-box;
    padding: 15px 0 40px 0;
    display: flex;
    font-size: 16px;
    color:#495060;
    flex-direction: column;
    .detail-box{
      flex: 1;
      width: 100%;
      background-color: white;
      box-sizing: border-box;
      box-shadow:0 0 19px 6px rgba(0, 0, 0, 0.11);
      border-radius:20px 20px 0 0;
      padding: 0 20px;
      overflow: scroll;
    }
    #add-data{
      position: absolute;
      top: 0;
      right: 0;
    }
  }
</style>
