<template>
  <div class="pages">
    <i-toast id="toast" />
    <div class="pages_header">
      <div class="pages_top_modified"></div>
    </div>
    <div class="form-box flex_parent">
      <div class="form-item">
        <div class="form-label">签收</div>
        <div class="form-input">
          <radio-group class="radio-group" @change="radioChange">
            <radio class="radio" v-for="(item,index) in radioList" :key="index" :value="item.name">
              <text>{{item.value}}</text>
            </radio>
          </radio-group>
        </div>
      </div>
      <div class="form-item">
        <div class="form-label">备注</div>
        <div class="form-input">
          <textarea name="remark"  id="textarea" v-model="form.remark"></textarea>
        </div>
      </div>
      <div class="form-item flex_1">
        <div class="form-label">凭证</div>
        <div class="form-input">
          <scroll-view scroll-y class="upfile-box" :enable-flex="true">
            <div class="file-box" v-for="(path,index) in imageList" :key="index">
              <icon class="iconfont iconshanchuqq" @click.stop="delImage(index)"></icon>
              <image :src="path" alt="" class="images" mode="aspectFit" @click.stop="seeImageList(index)"></image>
            </div>
            <div class="file-box add-file" @click="addFile">
              <icon class="iconfont iconxiangji"></icon>
            </div>
          </scroll-view>
        </div>
      </div>
    </div>
    <div class="details-operation-btns">
      <span class="large_btn_primary" @click="submit">提交</span>
    </div>
  </div>
</template>
<script>
  export default {
    components: {},

    data() {
      return {
        type:'',
        form:{
          remark: '',
          sign_type: '',
          proof: [],
          order_id: ''
        },
        radioList: [
          {
            name: 1,
            value: '全部'
          },
          {
            name: 2,
            value: '部分'
          },
          {
            name: 3,
            value: '拒收'
          }
        ]
      }
    },
    onLoad(){
      if(this.form.order_id !== this.$mp.query.order_id){
        this.type = this.$mp.query.type
        this.form = {
          remark: '',
          sign_type: '',
          proof: [],
          order_id:this.$mp.query.order_id
        }
      }
    },
    onShow() {
    },
    mounted(){
    },
    computed:{
      imageList(){
        return this.form.proof
      }
    },
    methods:{
      radioChange(e){
        this.form.sign_type = e.mp.detail.value
      },
      delImage(index){
        this.form.proof.splice(index,1)
      },
      seeImageList(index){
        const imgList = this.form.proof
        wx.previewImage({
          current: imgList[index],
          urls: imgList
        })
      },
      addFile(){
        const _this =this
        wx.chooseImage({
          count: 1,
          sizeType: ['original', 'compressed'],
          sourceType: ['album', 'camera'],
          success (res) {
            _this.$common.uploadFile(res.tempFilePaths[0],'/Common/uploadImg',{},function (res) {
            _this.form.proof.push(res.data.url)
            }),function (err) {
              console.log(err)
            }
          }
        })
      },
      submit(){
        const _this = this
        if(_this.form.sign_type === ''){
          _this.$common.error_tip('请选择签收类型')
        } else if(_this.form.sign_type !== 1&&_this.form.remark===''){
          _this.$common.error_tip('非全部签收请填写备注说明情况')
        } else {
          let fun = _this.type==='outbound'?'checkout/confirmReceipt':'',
              url = _this.type==='outbound'?'/pages/outbound/main':'/pages/procurement/main'
          _this.$ajax(fun,_this.form,function (res) {
            if(res.code===1){
              _this.$common.success_tip('签收成功')
              wx.reLaunch({url})
            }
          })
        }
      }
    }
  }
</script>

<style scoped lang="scss">
  .pages_header{
    padding-bottom: 8px !important;
  }
  .form-box{
    font-size: 14px;
    flex: 1;
    padding-bottom: 50px;
    .form-item{
      background-color: white;
      margin: 8px 0;
      display: flex;
      min-height: 40px;
      padding: 4px;
      .form-label{
        line-height: 40px;
        padding-right: 8px;
        box-sizing: border-box;
      }
      .form-input{
        flex: 1;
        line-height: 40px;
        .radio{
          margin-right: 20px;
        }
        #textarea{
          width: 100%;
          height: 150px;
          box-sizing: border-box;
          line-height: 20px;
          resize: none;
          padding: 4px;
          border: 1px solid #ddd;
          border-radius: 5px;
        }
      }
      .upfile-box{
        height: 100%;
        display: flex;
        flex-wrap: wrap;
        .file-box{
          width: 80px;
          height: 80px;
          margin: 10px;
          border: 1px dashed #ddd;
          border-radius: 5px;
          display: flex;
          justify-content: center;
          align-items: center;
          position: relative;
          .images{
            width: 80px;
            height: 80px;
          }
          .iconxiangji{
            font-size: 48rpx;
            color: #ddd;
          }
          .iconshanchuqq{
            position: absolute;
            right: -7px;
            top: -7px;
            width: 18px;
            height: 18px;
            color: white;
            background-color: #aaa;
            border-radius: 50%;
            z-index: 999;
            display: flex;
            justify-content: center;
            align-items: center;
          }
        }
      }
    }
  }
</style>
