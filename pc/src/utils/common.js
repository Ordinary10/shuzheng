/* 全局方法对象 */
import {request} from './request'

const common = {
  API_PATH: 'http://47.104.57.174:8070/api',
  pageInitInfo: JSON.parse(sessionStorage.getItem('pageInitInfo')) || {},
  /**
   * day 几天后 number
   * */
  getNowFormatDate: function (day) {
    var date = new Date()
    if (day && typeof day === 'number') date.setDate(date.getDate() + day)
    var seperator1 = '-'
    var year = date.getFullYear()
    var month = date.getMonth() + 1
    var strDate = date.getDate()
    if (month >= 1 && month <= 9) {
      month = '0' + month
    }
    if (strDate >= 0 && strDate <= 9) {
      strDate = '0' + strDate
    }
    return year + seperator1 + month + seperator1 + strDate
  },
  /*
  * 金额数据处理
  * 大于一万返回xx万，反之返回自身并保留2位小数
  * */
  amount_processing: function (amount) {
    if (Math.abs(amount) < 10000) {
      return amount.toFixed(2)
    } else {
      return (amount / 10000).toFixed(2) + '万'
    }
  },
  /*
* 浮点数运算函数
* */
  // 加法
  float_add: function (arg1, arg2) {
    if (!(typeof (arg1) === 'number') && (arg1 !== Infinity) && !isNaN(arg1)) {
      return 0
    }
    if (!(typeof (arg2) === 'number') && (arg2 !== Infinity) && !isNaN(arg2)) {
      return 0
    }
    let r1, r2, m
    try {
      r1 = arg1.toString().split('.')[1].length
    } catch (e) {
      r1 = 0
    }
    try {
      r2 = arg2.toString().split('.')[1].length
    } catch (e) {
      r2 = 0
    }
    m = Math.pow(10, Math.max(r1, r2))
    return (arg1 * m + arg2 * m) / m
  },
  // 减法
  float_sub: function (arg1, arg2) {
    if (!(typeof (arg1) === 'number') && (arg1 !== Infinity) && !isNaN(arg1)) {
      return 0
    }
    if (!(typeof (arg2) === 'number') && (arg2 !== Infinity) && !isNaN(arg2)) {
      return 0
    }
    let r1, r2, m, n
    try {
      r1 = arg1.toString().split('.')[1].length
    } catch (e) {
      r1 = 0
    }
    try {
      r2 = arg2.toString().split('.')[1].length
    } catch (e) {
      r2 = 0
    }
    m = Math.pow(10, Math.max(r1, r2))
    n = (r1 >= r2) ? r1 : r2
    return ((arg1 * m - arg2 * m) / m).toFixed(n)
  },
  // 除法
  float_div: function (arg1, arg2) {
    if (!(typeof (arg1) === 'number') && (arg1 !== Infinity) && !isNaN(arg1)) {
      return 0
    }
    if (!(typeof (arg2) === 'number') && (arg2 !== Infinity) && !isNaN(arg2)) {
      return 0
    }
    let t1, t2, r1, r2
    try {
      t1 = arg1.toString().split('.')[1].length
    } catch (e) {
      t1 = 0
    }
    try {
      t2 = arg2.toString().split('.')[1].length
    } catch (e) {
      t2 = 0
    }
    r1 = Number(arg1) * Math.pow(10, Math.max(t1, t2))
    r2 = Number(arg2) * Math.pow(10, Math.max(t1, t2))
    return (r1 / r2)
  },
  // 乘法
  float_mul: function (arg1, arg2) {
    if (!(typeof (arg1) === 'number') && (arg1 !== Infinity) && !isNaN(arg1)) {
      return 0
    }
    if (!(typeof (arg2) === 'number') && (arg2 !== Infinity) && !isNaN(arg2)) {
      return 0
    }
    // eslint-disable-next-line one-var
    let m = 0, s1 = arg1.toString(), s2 = arg2.toString()
    try {
      m += s1.split('.')[1].length
    } catch (e) {
    }
    try {
      m += s2.split('.')[1].length
    } catch (e) {
    }
    return Number(s1.replace('.', '')) * Number(s2.replace('.', '')) / Math.pow(10, m)
  },
  // 获得基础数据
  /**  type  数组  包含以下
   *  role 角色
   *  company 门店
   *  supplier 供应商
   * */
  PageInitInfo: function (type) {
    return request('Common/getPageInitInfo', {type}, true)
  },
  // 递归在数组中查询件满足条件的对象
  /**  list  数组
   *  att 满足条件的属性名
   *  value 满足条件的属性值
   *  children 需要递归查询的属性
   * */
  recursiveQuery: function (list, att, value, children) {
    function test (list, att, value, children) {
      if (Array.isArray(list)) {
        for (let i = 0; i < list.length; i++) {
        // if (res) break
          if (list[i][att] === value) {
            res = list[i]
            break
          }
          if (children && list[i][children] && list[i][children].length) {
            res = test(list[i][children], att, value, children)
          }
        }
      } else {
        alert('参数错误')
      }
      if (res) return res
    }
    let res
    return test(list, att, value, children)
  }
}
export default common
