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
      },
      {
        path: 'supplier',
        name: 'supplier',
        component: () => import('@/pages/system-manage/supplier'),
        meta: {
          name: '供应商管理'
        }
      },
      {
        path: 'category',
        name: 'category',
        component: () => import('@/pages/system-manage/category/treeType'),
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
        name: 'purchase-list',
        component: () => import('@/pages/purchase-manage/list'),
        meta: {
          name: '采购列表'
        }
      }
    ]
  },
  {
    path: '/warehouse',
    component: () => import('@/pages/warehouse-manage'),
    name: 'warehouse-manage',
    meta: {
      name: '仓库管理',
      icon: 'iconxitongguanli'
    },
    children: [
      {
        path: 'list',
        name: 'goods-list',
        component: () => import('@/pages/warehouse-manage/goodsList'),
        meta: {
          name: '商品管理'
        }
      },
      {
        path: 'list',
        name: 'warehouse-list',
        component: () => import('@/pages/warehouse-manage/outList'),
        meta: {
          name: '出库管理'
        }
      }
    ]
  }

]
export default dynamicRoutes
