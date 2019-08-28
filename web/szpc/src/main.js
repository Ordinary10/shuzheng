import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
// import 'lib-flexible' //适配插件
import ElementUI from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';
//toast
import './components/Toast/index.css';
import Toast from './components/Toast/index.js';
import './plugins/element.js'
Vue.use(ElementUI);
Vue.use(Toast);
Vue.config.productionTip = false
new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app')