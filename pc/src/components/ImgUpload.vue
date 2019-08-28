<template>
  <div class="upload-wrapper">
    <div class="title" v-if="config.title" v-html="config.title">{{config.title}}</div>
    <viewer class="img-box" ref="viewer" :images="uploadList">
        <div class="upload-list" v-for="(item,index) in uploadList" :key="index">
          <template v-if="item.status === 'finished'">
          <img :src="item.url" alt="">
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
          // multiple: false  // 是否多文件  不传入 默认为true
          // max: 5, // 最大文件数量  不传入 默认为5
          // type: 'Car', // 接口 图片保存的类型 不传入 默认为Car
          // onlyShow: true, // 是否仅仅作为图片回显  不传入 默认为false
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
        fun: 'common/uploadImg',
        token: JSON.parse(sessionStorage.getItem('loginData')).token,
        type: this.config.type || 'Car'
      },
      multiple: this.config.multiple === true || this.config.multiple === undefined,
      max: this.config.max === 0 || this.config.max ? this.config.max : 5,
      onlyShow: this.config.onlyShow,
      showUrl: '',
      visible: false,
      uploadList: []
    }
  },
  methods: {
    handleRemove (file) {
      const fileList = this.$refs.upload.fileList
      this.$refs.upload.fileList.splice(fileList.indexOf(file), 1)
    },
    handleSuccess (res, file) {
      // console.log(res, file)
      file.url = file.response.data.url
      setTimeout(e => {
        this.$refs.viewer.$viewer.update()
      }, 10)
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
    }
  },
  mounted () {
    this.uploadList = this.$refs.upload.fileList
  },
  computed: {
    API_PATH () {
      return this.$common.API_PATH
    },
    oldImgs () {
      if (Array.isArray(this.config.oldImg)) {
        return this.config.oldImg
      } else {
        let newarray = []
        let imgs = this.config.oldImg.split(',')
        for (let k of imgs) {
          newarray.push({url: k})
        }
        return newarray
      }
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
  watch: {
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
      margin-left: 20px;
      margin-top: 10px;
      display: flex;
    }
  }
  .upload-list{
    display: inline-block;
    width: 80px;
    height: 80px;
    text-align: center;
    line-height: 80px;
    border: 1px solid transparent;
    border-radius: 4px;
    background: #fff;
    position: relative;
    box-shadow: 0 1px 1px rgba(0,0,0,.2);
    margin-right: 15px;
    img{
      width: 100%;
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
