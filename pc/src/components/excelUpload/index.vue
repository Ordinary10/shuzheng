<template>
    <div class="excel-upload" v-if="configs">
      <div class="upload-btn-box">
        <Button class="upload-btn" type="warning" size="large" @click="exportBasicsData">基础数据</Button>
        <Button class="upload-btn" type="warning" size="large" v-if="configs.demo" @click="exportDemo">下载模板</Button>
        <Upload
          class="upload-btn"
          :before-upload="handleUpload"
          :action="$common.API_PATH"
          :data="uploadData"
          :format="configs.exts"
          :on-progress="onProgress"
          :on-success="onSuccess"
          :on-error="onError"
          :on-format-error="onFormatError">
          <Button icon="ios-cloud-upload-outline" size="large" type="primary" >选择文件</Button>
        </Upload>
      </div>
      <div class="file-name">{{fileName}}</div>
      <div class="prompt-text" v-if="configs.str">
        <p class="prompt-text-left">注：</p>
        <p class="prompt-text-right">{{configs.str}}</p>
      </div>
    </div>
</template>

<script type="text/jsx">
export default {
  name: 'excelUpload',
  props: {
    config: {
      type: Object,
      required: true
    }
  },
  data () {
    return {
      configs: null,
      uploadData: null,
      fileName: ''
    }
  },
  created () {
    this.configs = this.$props.config
    this.uploadData = {
      fun: this.configs.fun,
      token: sessionStorage.getItem('token')
    }
  },
  methods: {
    /* 导出基础数据 */
    exportBasicsData () {
      window.open(`${this.$common.API_PATH}?fun=ExportBasicsData/exportBasicsInfo&token=${sessionStorage.getItem('token')}`)
    },
    /* 下载模板 */
    exportDemo () {
      window.open(`${this.$common.API_PATH}?fun=BulkImport/exportDemo&token=${sessionStorage.getItem('token')}&params[type]=${this.configs.demo}`)
    },
    /* 文件上传前 */
    handleUpload (file) {
      this.fileName = file.name
    },
    /* 上传中 */
    onProgress (event, file, fileList) {
      // console.log('上传中：', event)
    },
    /* 上传成功 */
    onSuccess (response, file, fileList) {
      const _this = this
      if (response.status) {
        _this.$Message.success({
          content: response.msg,
          duration: 3
        })
        setTimeout(function () {
          _this.$emit('excelUploadSuccess')
        }, 1000)
      } else {
        _this.$Modal.confirm({
          title: '错误报告下载',
          content: '<p>文件内容有误，请下载错误报告查看详情</p>',
          onOk: () => {
            window.open(`${_this.$common.API_PATH}?token=${sessionStorage.getItem('token')}&fun=common/downFile&params[filename]=${response.data.path}`)
          }
        })
      }
    },
    /* 上传失败 */
    onError (e, file, fileList) {
      this.$Message.success({
        content: '网络不给力，上传文件失败',
        duration: 3
      })
    },
    /* 文件格式错误 */
    onFormatError () {
      this.$Message.error({
        content: '文件格式错误',
        duration: 3
      })
      return false
    }
  }
}
</script>
<style lang="scss">
  // 批量上传页upload样式
  .excel-upload{
    .ivu-upload-list{
      margin: 0;
      display: none;
    }
  }
  .file-error-prompt{
    width: 100%;
    text-align: center;
    font-size: 14px;
    line-height: 24px;
  }
</style>
<style scoped lang="scss">
.excel-upload{
  width: 500px;
  .upload-btn-box{
    position: relative;
    display: flex;
    width: 100%;
    justify-content: center;
    margin: 8px 0;
    .upload-btn{
      margin: 0 8px;
    }
  }
  .file-name{
    width: 100%;
    text-align: center;
    color: #999;
    height: 24px;
    line-height: 24px;
    margin-bottom:8px;
  }
  .prompt-text{
    display: flex;
    align-items: flex-start;
    color: #b57705;
    padding: 0 16px;
    .prompt-text-left{
      padding-right:4px;
      line-height: 18px;
    }
    .prompt-text-right{
      line-height: 18px;
      white-space: pre-line;
    }
  }
}
</style>
