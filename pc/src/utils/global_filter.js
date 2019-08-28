/* vue全局过滤器 */
const filters = {
  // 对返回的年月日时分秒时间取年月日
  dateMonth: function (ctime) {
    return ctime ? ctime.split(' ')[0] : ''
  },
  // 金额数据处理,大于一万返回xx万，反之返回自身并保留2位小数
  amount_processing: function (amount) {
    if (Math.abs(amount) < 10000) {
      return amount.toFixed(2)
    } else {
      return (amount / 10000).toFixed(2) + '万'
    }
  }
}
export default filters
