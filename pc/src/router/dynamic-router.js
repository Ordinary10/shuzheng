/* 需要权限判断的路由 */
const dynamicRoutes = [
  {
    path: '/car',
    component: () => import('@/pages/car-manage'),
    name: 'car-manage',
    meta: {
      name: '车辆管理',
      icon: 'iconcheliang',
      // tabName名称和第一个子路由一致 用于tab的展示
      tabName: '车辆统计'
    },
    children: [
      {
        path: 'main',
        name: 'car-main',
        component: () => import('@/pages/car-manage/main'),
        meta: {
          name: '车辆统计',
          pass: true
        }
      },
      {
        path: 'list',
        name: 'car-list',
        component: () => import('@/pages/car-manage/car_list'),
        meta: {
          name: '车辆列表'
        }
      }
    ]
  },
  {
    path: '/business',
    component: () => import('@/pages/business-manage'),
    name: 'business-manage',
    meta: {
      name: '业务管理',
      icon: 'iconyewu',
      tabName: '业务统计'
    },
    children: [
      {
        path: 'main',
        name: 'business-main',
        component: () => import('@/pages/business-manage/main'),
        meta: {
          name: '业务统计',
          pass: true
        }
      },
      {
        path: 'extract',
        name: 'extract-car',
        component: () => import('@/pages/business-manage/extract'),
        meta: {
          name: '提车退车'
        }
      },
    ]
  },
  {
    path: '/system',
    component: () => import('@/pages/system-setup'),
    name: 'system-setup',
    meta: {
      name: '系统设置',
      icon: 'iconxitongguanli'
    },
    children: [
      {
        path: 'illegalStatistical',
        name: 'illegal-statistical',
        component: () => import('@/pages/system-setup/illegal-statistical'),
        meta: {
          name: '违章统计'
        }
      },
      {
        path: 'illegalQuery',
        name: 'illegal-query',
        component: () => import('@/pages/system-setup/illegal-query'),
        meta: {
          name: '违章查询'
        }
      },
      {
        path: 'interfaceLog',
        name: 'interface-log',
        component: () => import('@/pages/system-setup/interface-log'),
        meta: {
          name: '接口日志'
        }
      }
    ]
  }
]
export default dynamicRoutes
