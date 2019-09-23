// 项目基础配置参数
export default {
  PROJECT_NAME: '蜀蒸',
  VERSION: 'v1.0',
  COPYRIGHT: '',
  PROJECT_START_DAY: '2019-08-30',
  API_PATH: 'http://47.104.57.174:8070/api',
  TIP_TIME: 3, // 提示语自动消失时间
  PAGE_LIMIT: 5, // 每页展示条数
  /*用户的tabbar列表*/
  tabBarRoleList: {
    boss:[
      {
        name: '采购单',
        path: '/pages/procurement/main',
        img: 'http://zucheguanjia.oss-cn-qingdao.aliyuncs.com/car/15673875747481.png',
        active_img: 'http://zucheguanjia.oss-cn-qingdao.aliyuncs.com/car/15673875775113.png'
      },
      {
        name: '出库单',
        path: '/pages/outbound/main',
        img: 'http://zucheguanjia.oss-cn-qingdao.aliyuncs.com/car/15673875808670.png',
        active_img: 'http://zucheguanjia.oss-cn-qingdao.aliyuncs.com/car/15673875855758.png'
      }
    ],
    storage:[
      {
        name: '采购单',
        path: '/pages/procurement/main',
        img: 'http://zucheguanjia.oss-cn-qingdao.aliyuncs.com/car/15673875747481.png',
        active_img: 'http://zucheguanjia.oss-cn-qingdao.aliyuncs.com/car/15673875775113.png'
      },
      {
        name: '出库单',
        path: '/pages/outbound/main',
        img: 'http://zucheguanjia.oss-cn-qingdao.aliyuncs.com/car/15673875808670.png',
        active_img: 'http://zucheguanjia.oss-cn-qingdao.aliyuncs.com/car/15673875855758.png'
      }
    ],
    purchase:[
      {
        name: '采购单',
        path: '/pages/procurement/main',
        img: 'http://zucheguanjia.oss-cn-qingdao.aliyuncs.com/car/15673875747481.png',
        active_img: 'http://zucheguanjia.oss-cn-qingdao.aliyuncs.com/car/15673875775113.png'
      }
    ],
    store: [
      {
        name: '采购单',
        path: '/pages/procurement/main',
        img: 'http://zucheguanjia.oss-cn-qingdao.aliyuncs.com/car/15673875747481.png',
        active_img: 'http://zucheguanjia.oss-cn-qingdao.aliyuncs.com/car/15673875775113.png'
      },
      {
        name: '出库单',
        path: '/pages/outbound/main',
        img: 'http://zucheguanjia.oss-cn-qingdao.aliyuncs.com/car/15673875808670.png',
        active_img: 'http://zucheguanjia.oss-cn-qingdao.aliyuncs.com/car/15673875855758.png'
      }
    ]
  },
  /*用户的操作权限表
  * specialAudit 特殊审核
  * procurement 采购
  * outbound 出库
  * receive 签收
  * distribution 配货
  * audit 普通审核
  * */
  operationRoleList:{
    admin: ['specialAudit','distribution','procurement','receive','audit','outbound'],
    store: ['procurement','outbound','receive'],
    buyer: ['audit'],
    warehouse: ['distribution','procurement']
  }
}
