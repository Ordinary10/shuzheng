<!--使用递归 动态渲染菜单  组件使用-->
<template>
  <div class="menu-container">
    <template v-for="v in menuList">
      <Submenu v-if="v.children&&v.children.length>0" :key="v.name" :name="v.name" >
        <template slot="title">
          <div ref="title" :data-name="v.name" :data-title="v.meta.tabName" :class="{'LongWidth':PageMode===0}">
            <Icon :custom="'iconfont ' + v.meta.icon" v-if="v.meta.icon"></Icon>
            {{v.meta.name}}
          </div>
        </template>
        <dynamic-menu :menuList="v.children"></dynamic-menu>
      </Submenu>
      <Menu-item :key="v.name" @click.native="gotoRoute(v.name,v.meta.name)" v-else-if="v.meta.pass !== false" :name="v.name">
        <Icon :type="v.meta.icon" v-if="v.meta.icon"></Icon>
        {{v.meta.name}}
      </Menu-item>
    </template>
  </div>
</template>

<script>
import dynamicRoutes from '@/router/dynamic-router.js'
import {DynamicRoutes} from '@/router/index'
import {mapState} from 'vuex'

export default {
  name: 'dynamic-menu',
  props: {
    menuList: {
      type: Array,
      default: function () {
        return []
      }
    }
  },
  computed: {
    ...mapState([ 'TabPage', 'PageMode'])
  },
  methods: {
    gotoRoute (name, title) {
      this.$router.push({ name })
      if (this.$store.state.TabPage === 1) {
        let obj = {name, title}
        this.$store.commit('addTab', obj)
        this.$store.commit('CruTab', obj)
      }
    }
  },
  mounted () {
    if (this.$refs.title) {
      this.$refs.title.forEach(div => {
        div.addEventListener('click', e => {
          let name = div.dataset.name
          let title = div.dataset.title
          if (title) this.gotoRoute(name, title)
        })
      })
    }
  }
}
</script>
<style lang="scss" scoped>
  .LongWidth{
    width: 100%;
  }
</style>
