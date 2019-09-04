/* 需要权限判断的路由 */
const dynamicRoutes = [
  {
    path: '/system',
    component: () => import('@/pages/system-manage'),
    name: 'system-manage',
    meta: {
      name: '系统管理',
      icon: 'iconxitongguanli'
    },
    children: [
      {
        path: 'user',
        name: 'user',
        component: () => import('@/pages/system-manage/user'),
        meta: {
          name: '用户管理'
        }
      },
      {
        path: 'company',
        name: 'company',
        component: () => import('@/pages/system-manage/company'),
        meta: {
          name: '门店管理'
        }
      },
      {
        path: 'role',
        name: 'role',
        component: () => import('@/pages/system-manage/role'),
        meta: {
          name: '角色管理'
        }
      }
    ]
  },
  {
    path: '/goods',
    component: () => import('@/pages/goods-manage'),
    name: 'goods-manage',
    meta: {
      name: '商品管理',
      icon: 'iconxitongguanli'
    },
    children: [
      {
        path: 'list',
        name: 'goods-list',
        component: () => import('@/pages/goods-manage/list'),
        meta: {
          name: '商品列表'
        }
      },
      {
        path: 'type',
        name: 'goods-type',
        component: () => import('@/pages/goods-manage/type'),
        meta: {
          name: '类目列表'
        }
      }
    ]
  },
  {
    path: '/purchase',
    component: () => import('@/pages/purchase-manage'),
    name: 'purchase-manage',
    meta: {
      name: '采购管理',
      icon: 'iconxitongguanli'
    },
    children: [
      {
        path: 'list',
        name: 'goods-list',
        component: () => import('@/pages/purchase-manage/list'),
        meta: {
          name: '采购管理'
        }
      }
    ]
  }

]
export default dynamicRoutes
