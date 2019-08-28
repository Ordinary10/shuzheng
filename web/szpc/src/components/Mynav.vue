<template>
  <div>
    <el-menu :default-active="this.$router.path" router class="el-menu-vertical-demo">
      <el-menu-item v-for="(item,index) in navList" :key="index" :index="item.name">
        <i class="el-icon-setting"></i>
        <span slot="title">{{item.navItem}}</span>
      </el-menu-item>
    </el-menu>
  </div>
</template>
<script>
  export default {
    data() {
      return {
        navList: [{
            name: '/goods',
            navItem: '货品管理'
          },
          {
            name: '/stock',
            navItem: '库存管理'
          },
          {
            name: '/buy',
            navItem: '采购单管理'
          },
          {
            name: '/out_stock',
            navItem: '出库单管理'
          },
          {
            name: '/store',
            navItem: '门店管理'
          },
          {
            name: '/user',
            navItem: '用户管理'
          },
          {
            name: '/supplier',
            navItem: '供应商管理'
          }
        ]
      }
    }
  }
</script>