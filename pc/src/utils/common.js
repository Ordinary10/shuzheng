/* 全局方法对象 */
const common = {
  API_PATH: 'http://test.c.zdxrchina.com/index/index',
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
    try { r1 = arg1.toString().split('.')[1].length } catch (e) { r1 = 0 }
    try { r2 = arg2.toString().split('.')[1].length } catch (e) { r2 = 0 }
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
    try { r1 = arg1.toString().split('.')[1].length } catch (e) { r1 = 0 }
    try { r2 = arg2.toString().split('.')[1].length } catch (e) { r2 = 0 }
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
    try { t1 = arg1.toString().split('.')[1].length } catch (e) { t1 = 0 }
    try { t2 = arg2.toString().split('.')[1].length } catch (e) { t2 = 0 }
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
    try { m += s1.split('.')[1].length } catch (e) {}
    try { m += s2.split('.')[1].length } catch (e) {}
    return Number(s1.replace('.', '')) * Number(s2.replace('.', '')) / Math.pow(10, m)
  }
}
export default common
