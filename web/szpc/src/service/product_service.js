import api from './api/product_api'
import base from './base'
export default {
  product_detail(item) {
    return base.get(api.product_detail, item)
  },
  product_select(item) {
    return base.get(api.product_select, item)
  },
  product_order(item) {
    return base.get(api.product_order, item)
  },
  selasman_login(item) {
    return base.post(api.selasman_login, item)
  },
  wx_login(item) {
    return base.get(api.wx_login, item)
  },
  signin(item) {
    return base.get(api.signin, item)
  },
  tokenHeart(item) {
    return base.get_header(api.tokenHeart, item)
  },
  pay(item) {
    return base.post(api.pay, item)
  },
  WxShare(item) {
    return base.get(api.WxShare, item)
  }
}