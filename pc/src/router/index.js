import Vue from 'vue'
import Router from 'vue-router'
import Layout from '@/pages/common/layout/index'
import Home from '@/pages/common/home/index'
import NotFound from '@/pages/common/errorPage/404'
import Forbidden from '@/pages/common/errorPage/403'

Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/login',
      name: 'Login',
      component: () => import('@/pages/common/login/index')
      // component: Layout
    },
    // 加载公共样式的页面-zhw
    {
      path: '/assembly',
      name: 'assembly',
      component: () => import('@/pages/assembly/assembly')
      // component: Layout
    },
    {
      path: '/com',
      name: 'com',
      component: () => import('@/components/ImgUpload')
      // component: Layout
    }
  ]
})

export const DynamicRoutes = [
  {
    path: '',
    component: Layout,
    name: 'container',
    redirect: 'home',
    meta: {
      requiresAuth: true,
      name: '租车管家'
    },
    children: [
      {
        path: 'home',
        component: Home,
        name: 'home',
        meta: {
          name: '首页',
          icon: 'md-home'
        }
      }
    ]
  },
  {
    path: '/403',
    component: Forbidden
  },
  {
    path: '*',
    component: NotFound
  }
]
