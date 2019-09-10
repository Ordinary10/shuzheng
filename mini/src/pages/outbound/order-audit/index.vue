<template>
  <div class="pages">
    <i-toast id="toast" />
    <div class="audit-page">
      <div class="audit-page-item form-input">
        <i-cell-group>
          <i-cell title="审核">
            <i-switch :value="switch1" size="large" @change="verifyStatusChange" slot="footer">
              <view slot="open">通过</view>
              <view slot="close">拒绝</view>
            </i-switch>
          </i-cell>
        </i-cell-group>
      </div>
      <div class="audit-page-item form-input remark-input">
        <span>备注</span>
        <input type="text" v-model="remark" placeholder="请输入备注" max="100">
      </div>
      <div class="audit-page-item">
        <button type="button" @click="submitAudit" class="login_btn">提交</button>
      </div>
    </div>
  </div>
</template>
<script>
  export default {
    components: {},

    data() {
      return {
        order_id: null,
        verify_status: 'pass',
        switch1: true,
        remark: '',
        pages: null
      }
    },

    created() {
    },
    onShow(){
    },
    mounted(){
      this.order_id = this.$root.$mp.query.order_id
    },
    methods:{
      verifyStatusChange(e) {
        this.switch1 = e.mp.detail.value
        this.verify_status=this.switch1?'pass':'deny'
      },
      submitAudit(){
        const _this = this
        let data = {
          order_id: _this.order_id,
          verify_status: _this.verify_status,
          remark: _this.remark
        }
        _this.$ajax('Checkout/verify',data,function (res) {
          if(res.code === 1 ){
            _this.$common.success_tip('提交成功',function () {
              wx.navigateBack()
            })
          }
        })
      }
    }
  }
</script>

<style lang="wxss">
  page{
    background-color: white;
    height: 100%;
  }
</style>
<style scoped lang="scss">
  .pages{
    height: 100%;
    width: 100%;
    position: relative;
    font-size: 14px;
    .audit-page-item{
      margin: 8px 0;
    }
    .form-input{
      .i-input-input{
        line-height: 36px;
      }
    }
    .login_btn{
      width:320px;
      font-size: 18px;
      color: white;
      line-height: 40px;
      background:#EC181F;
      box-shadow:0px 7px 15px 3px rgba(236, 31, 24, 0.35);
      border-radius:20px;
      margin-top: 30px;
      &:hover{
        background: #EC5B5A;
      }
    }
    .remark-input{
      display: flex;
      height: 38px;
      padding: 12px 15px;
      align-items: center;
      line-height: 38px;
      input{
        flex: 1;
        border-bottom: 1px solid #eee;
        margin-left: 8px;
      }
    }
  }
</style>
