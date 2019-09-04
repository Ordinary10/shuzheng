<template>
    <div class="yd-divider">
      <span class="line" :style="lineStyle"></span>
      <span class="text" :style="textStyle">{{text}}</span>
    </div>
</template>

<script>
    export default {
        name: "divider",
        data(){
          return {
            lineStyle: '',
            textStyle: '',
            text: ''
          }
        },
        props: {
          content: {
            type: String,
            default: "分隔线"
          },
          css: {
            type: Object,
            default: {}
          }
        },
        mounted() {
          this.text = this.$props.content
          this.divider_style(this.$props.css)
        },
        methods: {
          divider_style(css){
            Object.keys(css).forEach(key=>{
              let type = key.split('_')
              if(type[0] === 'l'){
                this.lineStyle+=`${type[1]}:${css[key]};`
              } else {
                this.textStyle+=`${key}:${css[key]};`
              }
            })
          }
        }
    }
</script>

<style scoped lang="scss">
  .yd-divider{
    width: 100%;
    min-height: 14px;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    .line{
      width: 100%;
      height: 1px;
      background-color: #D9D9D9;
      position: absolute;
      top: 50%;
    }
    .text{
      background-color: white;
      padding: 0 10px;
      color: #8A98AC;
      font-size: 14px;
      z-index: 999;
    }
  }
</style>
