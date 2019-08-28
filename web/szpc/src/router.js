import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

export default new Router({
  mode: 'hash',
  base: process.env.BASE_URL,
  routes: [{
    path: '/login',
    name: 'index',
    meta: {
      title: '蜀蒸系统'
    },
    component: resolve => require(['@/views/index/index.vue'], resolve),
  }, {
    path: '/',
    name: 'index',
    component: resolve => require(['@/views/index/index'], resolve),
    children: [{
      path: '/goods',
      name: 'goods',
      meta: {
        title: '商品管理'
      },
      component: resolve => require(['@/views/goods/index'], resolve),
    }, {
      path: '/stock',
      name: 'stock',
      meta: {
        title: '库存管理'
      },
      component: resolve => require(['@/views/stock/index'], resolve),
    }, {
      path: '/buy',
      name: 'buy',
      meta: {
        title: '采购单管理'
      },
      component: resolve => require(['@/views/buy/index'], resolve),
    }, {
      path: '/out_stock',
      name: 'out_stock',
      meta: {
        title: '出库单管理'
      },
      component: resolve => require(['@/views/outStock/index'], resolve),
    }, {
      path: '/store',
      name: 'store',
      meta: {
        title: '门店管理'
      },
      component: resolve => require(['@/views/store/index'], resolve),
    }, {
      path: '/user',
      name: 'user',
      meta: {
        title: '用户管理'
      },
      component: resolve => require(['@/views/user/index'], resolve),
    }, {
      path: '/supplier',
      name: 'supplier',
      meta: {
        title: '供应商管理'
      },
      component: resolve => require(['@/views/supplier/index'], resolve),
    }]
  }]
})