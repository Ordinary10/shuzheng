<template>
    <div v-if="data">
      <Tooltip v-if="data.car_status_name" :content="data.car_status_name" placement="bottom-start">
        <span class="plate_no_style car_status_name">{{data.car_status_name|car_status_name}}</span>
      </Tooltip>
      <Tooltip v-if="data.is_service_name" :content="data.is_service_name" placement="bottom-start">
        <span class="plate_no_style">{{data.is_service_name|substr}}</span>
      </Tooltip>
      <Tooltip v-if="data.type_name" :content="data.type_name" placement="bottom-start">
        <span class="plate_no_style">{{data.type_name === '二手车' ? '旧' : '新'}}</span>
      </Tooltip>
      <Tooltip v-if="data.car_householder" :content="data.car_householder" placement="bottom-start">
        <span class="plate_no_style car_householder">{{data.car_householder|substr}}</span>
      </Tooltip>
      <span>{{data.plate_no}}</span>
    </div>
</template>

<script>
export default {
  name: 'license-plate',
  data () {
    return {
      data: null
    }
  },
  filters: {
    car_status_name (val) {
      switch (val) {
        case '租赁中':
          return '已租'
        case '租赁中-以租代购':
          return '经租'
        case '租赁中-纯租车':
          return '融资'
        case '租赁中-全款车':
          return '全款'
        case '租赁中-打工模式':
          return '打工'
        case '在库-待处理':
          return '在库'
        case '在库-待销售':
          return '在库'
        case '在库-已收回':
          return '在库'
        case '已售出':
          return '已售'
        case '已过户':
          return '过户'
        case '已报废':
          return '报废'
        default:
          return '未知'
      }
    },
    substr (val) {
      return val.substr(0, 1)
    }
  },
  props: {
    row: {
      type: Object
    }
  },
  created () {
    this.data = this.$props.row
  }
}
</script>

<style scoped lang="scss">
  span.plate_no_style {
    color: red;
    display: inline-block;
    padding: 0 6px;
    height: 23px;
    background-color: #f7e4e4;
    vertical-align: middle;
    line-height: 22px;
    border-radius: 5px;
    margin-right: 5px;
    font-size: 12px;
    text-align: center;
  }
  span.car_householder.plate_no_style {
    color: #6f6bf9;
    background-color: #dfdffb;
  }
  span.car_status_name.plate_no_style{
    color: #c81ff9;
    background-color: #fbd1f7;
  }
</style>
