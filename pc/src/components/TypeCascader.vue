<template>
  <Cascader :data="type_data" @on-change="cleanTypeID" trigger="hover" v-model="typeList" change-on-select></Cascader>
</template>
<script type="text/jsx">
export default {
  props: ['echoId'],
  data () {
    /*
      * echoId: 回显类目的id
      * */
    return {
      typeList: [],
      type_id: '',
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
              e.value = e.id
              e.label = e.type_name
              if (e.children && e.children.length) recursion(e.children)
            })
          }
          recursion(_this.type_data)
        }
      })
    },
    // 提去目录的id
    cleanTypeID (value) {
      // console.log(value)
      let _this = this
      let active = value[value.length - 1]
      _this.type_id = active || ''
      _this.$emit('typeid')
    },
    // 回显目录信息
    echo_TypeID (id) {
      let _this = this
      _this.typeList = []
      function recursion (list, typeId) {
        for (let i = 0; i < list.length; i++) {
          _this.typeList.push(list[i].id)
          // console.log(list[i].id, typeId)
          if (list[i].id === typeId) {
            break
          } else if (list[i].children && list[i].children.length) {
            recursion(list[i].children, typeId)
          } else {
            _this.typeList.pop()
            // 当退至最外层直接重置数组
            if (_this.typeList.length === 1) _this.typeList = []
          }
        }
      }
      recursion(_this.type_data, id)
      let idIndex = _this.typeList.indexOf(id)
      if (idIndex !== -1) {
        // 去除多余数据
        // console.log(_this.typeList, idIndex)
        _this.typeList = _this.typeList.slice(0, idIndex + 1)
      }
      _this.cleanTypeID(_this.typeList)
    }
  },
  watch: {
    // 刷新组件
    echoId () {
      this.echo_TypeID(this.echoId)
    }
  }
}
</script>
<style lang="scss" scoped>

</style>
