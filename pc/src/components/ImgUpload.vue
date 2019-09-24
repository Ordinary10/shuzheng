<template>
  <div class="upload-wrapper">
    <div class="title" v-if="config.title" v-html="config.title">{{config.title}}</div>
    <viewer class="img-box" ref="viewer" :style="{
    margin:onlyShow?0:'10px 0 0 10px',
    display: uploadList.length === 1 && onlyShow?'inline-block':'flex'
    }" :images="uploadList">
        <div class="upload-list"
             v-for="(item,index) in uploadList"
             :style="{
             marginRight: uploadList.length === 1 && onlyShow?'':'15px',
             width: config.width || '80px',
             height: config.width || '80px'
             }"
             :key="index">
          <template v-if="item.status === 'finished'">
          <img :src="item.url" alt="" :style="{borderRadius:config.borderRadius || '10px'}">
            <Icon type="md-close-circle" v-if="!onlyShow" @click.native="handleRemove(item)"/>
          </template>
          <template v-else>
            <Progress v-if="item.showProgress" :percent="item.percentage" hide-info></Progress>
          </template>
        </div>
      <Upload
        v-show="uploadList.length < max && !onlyShow"
        ref="upload"
        :show-upload-list="false"
        :default-file-list="oldImgs"
        :on-success="handleSuccess"
        :format="['jpg', 'jpeg', 'png']"
        :max-size="2048"
        :on-format-error="handleFormatError"
        :on-exceeded-size="handleMaxSize"
        :before-upload="handleBeforeUpload"
        :multiple="multiple"
        type="drag"
        :action="API_PATH"
        :data="networkConfig"
        style="display: inline-block;width:78px;">
        <div style="width: 78px;height:78px;line-height: 78px;">
          <Icon type="ios-camera" size="20"></Icon>
        </div>
      </Upload >
    </viewer>
  </div>
</template>

<script>
export default {
  props: {
    config: {
      type: Object,
      default: () => {
        return {
          // title: '图片标题', // 图片标题 不传入 默认空
          // width: '80px', // 图片框宽度 默认为80px
          // height: '80px', // 图片框高度 默认为80px
          // borderRadius: '80px', // 图片框倒角 默认为10px
          // multiple: false  // 是否多文件  不传入 默认为true
          // max: 5, // 最大文件数量  不传入 默认为5  为1是图片间距为0 否则是15px
          // onlyShow: true, // 是否仅仅作为图片回显  不传入 默认为false
          //                    图片回显时 么有删除 添加按钮  img-box的margin为0
          // oldImg 为数组 或者 ,分割的字符串
          oldImg: [
            {url: 'http://zucheguanjia.oss-cn-qingdao.aliyuncs.com/car/15664441942763.png'},
            {url: 'http://zucheguanjia.oss-cn-qingdao.aliyuncs.com/car/15664441942763.png'},
            {url: 'http://zucheguanjia.oss-cn-qingdao.aliyuncs.com/Car/15664600948748.png'}
          ]
          // oldImg: 'http://zucheguanjia.oss-cn-qingdao.aliyuncs.com/car/15664441942763.png'
        }
      }
    }
  },
  data () {
    return {
      networkConfig: {
        token: JSON.parse(sessionStorage.getItem('loginData')).token
      },
      multiple: this.config.multiple === true || this.config.multiple === undefined,
      max: this.config.max === 0 || this.config.max ? this.config.max : 5,
      onlyShow: this.config.onlyShow,
      showUrl: '',
      visible: false,
      API_PATH: this.$common.API_PATH + '/Common/uploadImg',
      // 图片上传的列表  必须随时和组件内部的上传列表信息保持一致
      uploadList: []
    }
  },
  methods: {
    handleRemove (file) {
      const fileList = this.$refs.upload.fileList
      this.$refs.upload.fileList.splice(fileList.indexOf(file), 1)
      this.uploadList = this.$refs.upload.fileList
    },
    handleSuccess (res, file) {
      file.url = file.response.data.url
      setTimeout(e => {
        this.$refs.viewer.$viewer.update()
      }, 10)
      this.uploadList = this.$refs.upload.fileList
    },
    handleFormatError (file) {
      this.$Notice.warning({
        title: '上传失败',
        desc: file.name + ' 文件后缀必须为' + ['jpg', 'jpeg', 'png'].join(' ')
      })
    },
    handleMaxSize (file) {
      this.$Notice.warning({
        title: '上传失败',
        desc: file.name + ' 文件超出2M 大小.'
      })
    },
    handleBeforeUpload () {
      // 上传文件之前的钩子，参数为上传的文件，若返回 false s或者 Promise 则停止上传
      return true
    },
    // 获取上传的url字符串
    getImgUrl () {
      let res = ''
      for (let i = 0; i < this.uploadList.length; i++) {
        let url = this.uploadList[i].url
        res += url
        if (i !== this.uploadList.length - 1) res += ','
      }
      return res
    }
  },
  mounted () {
    this.uploadList = this.$refs.upload.fileList
  },
  computed: {
    oldImgs () {
      if (!this.config.oldImg) this.uploadList = []
      if (Array.isArray(this.config.oldImg)) {
        for (let k of this.config.oldImg) {
          if (k) k.status = 'finished'
        }
        this.uploadList = this.config.oldImg
        return this.config.oldImg
      } else {
        let newarray = []
        let imgs = this.config.oldImg.split(',')
        for (let k of imgs) {
          if (k) newarray.push({url: k, status: 'finished'})
        }
        this.uploadList = newarray
        return newarray
      }
    },
    oldImgData () {
      return this.config.oldImg
    }
  },
  watch: {
    // 监听传入的旧图片数据 更新
    oldImgData: {
      deep: true,
      handler: function (v1, v2) {
        if (v2) {
          this.uploadList = this.oldImgs
        } else {
          this.uploadList = []
          this.$refs.upload.fileList = []
        }
      }
    }
  }
}
</script>

<style scoped lang="scss">
  .upload-wrapper{
    margin: 0 5px;
    overflow: hidden;
    .title{
      margin: 10px 10px 0;
      border-left: 3px solid red;
      height: 16px;
      line-height: 16px;
      padding-left: 7px;
    }
    .img-box{
      display: flex;
    }
  }
  .upload-list{
    display: inline-block;
    text-align: center;
    line-height: 80px;
    border: 1px solid transparent;
    border-radius: 4px;
    background: #fff;
    position: relative;
    box-shadow: 0 1px 1px rgba(0,0,0,.2);
    img{
      width: 100%;
      position:absolute;
      left: 0;
      top: 0;
      height: 100%;
    }
    .ivu-icon{
      position: absolute;
      right: -10px;
      top: -10px;
      font-size: 20px;
      color: red;
      cursor: pointer;
    }
  }
</style>
