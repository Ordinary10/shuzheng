<template>
  <div style="height: 100%;">
    <Top @refresh="refresh"></Top>
    <mainContent :active="active" :PageMode="PageMode" v-if="TabPage !== 1"/>
    <TabMainContent :PageMode="PageMode" :active="active" v-else></TabMainContent>
    <side v-if="PageMode!==1"></side>
  </div>
</template>

<script>
import mainContent from './component/content'
import TabMainContent from './component/TabContent'
import Top from './component/top-aside'
import side from './side/side'
import {mapState} from 'vuex'

export default {
  data () {
    return {
      active: true
    }
  },
  computed: {
    ...mapState(['PageMode', 'TabPage'])
  },
  components: {
    mainContent,
    Top,
    side,
    TabMainContent
  },
  methods: {
    refresh (e) {
      this.active = !this.active
    }
  },
  mounted () {
  }
}
</script>

<style lang="scss" scoped>

</style>
