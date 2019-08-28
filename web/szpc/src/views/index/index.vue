<template>
  <el-container :style="{height: boxheight+'px'}">
    <el-header style="background:red;">
      <!-- 1级导航 -->
      <Header></Header>
    </el-header>
    <el-container>
      <el-aside width="200px" style="background:green;">
        <!-- 2级导航 -->
        <Mynav></Mynav>
      </el-aside>
      <el-container>
        <!-- 内容 -->
        <el-main style="background:yellow;">
          <router-view />
        </el-main>
      </el-container>
    </el-container>
  </el-container>
</template>
<script>
  import Header from '../../components/Header'
  import Mynav from '../../components/Mynav'
  export default {
    components: {
      Header,
      Mynav
    },
    data() {
      return {
        boxheight: 0,
      }
    },
    mounted() {
      this.boxheight = window.innerHeight
    }
  };
</script>
<style scoped lang="scss">
  .el-header {
    padding: 0;
    position: relative;
  }
</style>