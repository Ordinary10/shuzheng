<template>
  <div class="content flex">
    <div class="logo flex-item-1">1</div>
    <!-- <div class="flex-item-grow">
      <el-menu :default-active="activeIndex" class="el-menu-demo" mode="horizontal" @select="handleSelect">
        <el-menu-item index="1">处理中心</el-menu-item>
        <el-menu-item index="2">处理中心</el-menu-item>
        <el-menu-item index="3">处理中心</el-menu-item>
        <el-menu-item index="4">处理中心</el-menu-item>
      </el-menu>
    </div> -->
  </div>
</template>
<script>
  export default {
    data() {
      return {
        activeIndex: '1',
      };
    },
    methods: {
      handleSelect(key, keyPath) {
        console.log(key, keyPath);
      }
    }
  }
</script>
<style lang="scss" scoped>
  .content {
    height: 100%;
  }

  .el-menu.el-menu--horizontal {
    height: 100%;
  }

  .el-menu--horizontal>.el-menu-item {
    height: 100%;
    line-height: 70px;
  }
</style>