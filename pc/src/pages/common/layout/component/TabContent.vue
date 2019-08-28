<template>
    <div type="card" class="content-wrapper" :style="{'padding':PageMode===1?'75px 15px 15px':'75px 15px 15px 255px'}">
      <div class="tabs">
        <div class="tab" v-for="(item,index) in Tab.list" :key="item.index" ref="tab">
          <span @click="changeTab(item.name,$event)" :ref="item.name" :class="{'active':item.name===Tab.cru.name}" :style="{'padding-right':index?'5px':'10px'}">
            {{item.title}}
            <Icon type="ios-close" v-if="index" @click.stop="removeTab(item.name,$event)"/>
          </span>
        </div>
        <div class="removeTab" @click="removeTabAll">
        关闭所有
        </div>
      </div>
      <transition name="fade">
        <router-view class="router_view" v-if="refresh"></router-view>
      </transition>
    </div>
</template>

<script>
import { mapState } from 'vuex'
export default {
  props: {
    // 刷新组件
    active: {
    }
  },
  data () {
    return {
      cruButton: '',
      refresh: true
    }
  },
  computed: {
    ...mapState(['Tab', 'PageMode'])
    // cru: {
    //   get () {
    //     return this.$store.state.Tab.cru
    //   },
    //   set (val) {
    //     this.$store.state.Tab.cru = val
    //   }
    // }
  },
  components: {
  },
  mounted () {
    if (!this.Tab.list.length) this.initTab()
    this.removeTabAll()
  },
  methods: {
    initTab () {
      this.$router.push({name: 'home'})
      this.$store.commit('addTab', {name: 'home', title: '首页'})
      this.$store.commit('CruTab', {name: 'home', title: '首页'})
    },
    changeTab (name, e) {
      this.$refs.tab.forEach(e => {
        e.childNodes[0].setAttribute('class', '')
      })
      this.$router.push({name})
      this.$store.commit('CruTab', {name})
    },
    removeTab (name) {
      this.$store.commit('RemoveTab', name)
      // this.$router.go(-1)
      let last = this.Tab.list[this.Tab.list.length > 0 ? this.Tab.list.length - 1 : 0]
      this.$store.commit('CruTab', last)
      this.$router.push({name: last.name})
    },
    removeTabAll () {
      this.$store.commit('clearTab')
      this.initTab()
    }
  },
  watch: {
    // 刷新组件
    active () {
      this.refresh = false
      this.$nextTick(() => (this.refresh = true))
    }
  }
}
</script>

<style lang="scss" scoped>
  .fade-enter-active{
    animation:bounce-in 0.3s;
  }
  .fade-leave-active{
    animation:bounce-in 0.3s reverse;
  }
  @keyframes bounce-in{
  }
  @mixin active(){
    color: #fff;
    background-color: #4c7cf3;
  }
  .content-wrapper{
    padding: 75px 15px 15px;
    height:100%;
    border-radius:15px;
    .tabs{
      height: 40px;
      border-bottom: 1px solid #4c7cf3;
      margin-bottom: 5px;
      &::after{
        content: '';
        display: block;
        clear: both;
      }
      .tab{
        margin-right: 5px;
        float: left;
        >span{
          padding: 5px 5px 5px 10px;
          border-radius: 5px;
          line-height: 21px;
          display: inline-block;
          border: 1px solid #ccc;
          cursor: pointer;
          position: relative;
          &:hover{
            @include active;
            .ivu-icon-ios-close{
              color: #fff;
            }
          }
          .ivu-icon-ios-close{
            font-size: 18px;
            margin-left: 5px;
            color: #000;
            &:hover{
                background-color: red;
                border-radius: 4px;
          }
          }
        }
        .active{
          @include active;
          .ivu-icon-ios-close{
            color: #fff;
          }
        }
      }
      .removeTab{
        display: inline-block;
        float: right;
        padding: 5px 10px 5px 10px;
        border-radius: 5px;
        line-height: 21px;
        border: 1px solid #ccc;
        cursor: pointer;
        &:hover{
          @include active;
      }
      }
    }
    .router_view{
      height: calc(100% - 50px);
      /deep/ >div{
        height: 100%;
        display: flex;
        flex-direction: column;
      }
    }
  }
</style>
