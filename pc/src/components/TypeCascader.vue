<template>
  <Cascader :data="type_data"
            @on-change="cleanTypeID"
            v-model="typeList"
            :change-on-select="changeOnSelect"
            filterable
            placeholder="请选择商品类目"></Cascader>
</template>
<script type="text/jsx">
export default {
  props: {
    echoId: {},
    maxNum: {
    },
    changeOnSelect: {
      type: Boolean,
      default: true
    }
  },
  data () {
    /*
      * echoId: 回显类目的id
      * maxNum: 开启最大限定级别的 级别数
      * */
    return {
      // 默认最大级别的限定
      typeList: [],
      type_id: '',
      top_type_id: '',
      // 类目数据
      type_data: []
    }
  },
  components: {
  },
  created () {
  },
  mounted () {
    this.init()
  },
  methods: {
    init () {
      let _this = this
      this.$axios('goods/getGoodsType', {}).then((res) => {
        if (res.code === 1) {
          _this.type_data = res.data
          // 清洗数据
          function recursion (list) {
            list.forEach(e => {
              // e.id = String(e.id)
              e.value = e.id
              e.label = e.type_name
              if (e.children && e.children.length) recursion(e.children)
            })
          }
          recursion(_this.type_data)
          // 触发事件init事情 以便其它页面使用该数据
          _this.$emit('init')
        }
      })
    },
    // 提去目录的id
    cleanTypeID (value) {
      // value 就是 _this.typeList
      // console.log(value)
      let _this = this
      // 搜索得到的数据为字符串,全部转化为数组
      for (let key in value) {
        value[key] = +value[key]
      }
      if (!value.length) {
        value = []
      } else if (_this.maxNum && value.length >= +_this.maxNum) {
        // 超过设置的最多级别直接清空
        value = []
        _this.$Message.warning({
          content: `级别超出！最多${_this.maxNum}级`
        })
      }
      let active = value[value.length - 1]
      _this.type_id = active || ''
      _this.top_type_id = value[0] || ''
      setTimeout(() => {
        _this.$emit('typeid')
      }, 100)
    },
    // 根据id 逐步查询出层级的数组, 例如 id=6  [1,3,6]
    echo_TypeID (id) {
      let _this = this
      let ok = false
      _this.typeList = []
      for (let i = 0; i < _this.type_data.length; i++) {
        _this.typeList = []
        _this.typeList.push(_this.type_data[i].id)
        if (_this.type_data[i].id === id) {
          break
        } else if (_this.type_data[i].children && _this.type_data[i].children.length) {
          recursion(_this.type_data[i].children, id)
          if (_this.typeList.length) {
            break
          }
        }
      }
      function recursion (list, typeId) {
        for (let i = 0; i < list.length; i++) {
          _this.typeList.push(list[i].id)
          if (list[i].id === typeId) {
            ok = true
            break
          } else if (list[i].children && list[i].children.length) {
            recursion(list[i].children, typeId)
          } else {
            _this.typeList.pop()
          }
        }
        if (!ok) _this.typeList.pop()
      }
      let idIndex = _this.typeList.indexOf(id)
      if (idIndex !== -1) {
        // 去除多余数据
        // console.log(_this.typeList, idIndex)
        _this.typeList = _this.typeList.slice(0, idIndex + 1)
      }
      _this.cleanTypeID(_this.typeList)
    },
    // 监听传入的id进行渲染的动作
    watchEchoId () {
      if (this.echoId === 0 || this.echoId === '') {
        this.typeList = []
        this.type_id = ''
      } else {
        this.echo_TypeID(this.echoId)
      }
    }
  },
  watch: {
    // 监听传入的id进行渲染
    echoId () {
      this.watchEchoId()
    }
  }
}
</script>
<style lang="scss" scoped>

</style>
