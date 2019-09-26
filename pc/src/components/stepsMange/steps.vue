<template>
  <div v-if="newdata">
    <Row >
      <Steps :current=current  size="small">
        <Step v-for="(list,index) in newdata" :title=list.title :content=list.content :key="list.id"></Step>

      </Steps>
    </Row>

  </div>

</template>

<script>
export default {
  name: 'steps',
  props: {
    type: {
      type: String,
      default: () => {
        return ''
      }
    },
    progress: {
      type: Array,
      default: () => {
        return []
      }
    }
  },
  data () {
    return {
      newdata: [],
      current:0,
      warehouse: [
        {
          title: ' 申请中',
          content: ''
        },
        {
          title: '审核',
          content: ''
        },
        {
          title: ' 配货',
          content: ''
        },
        {
          title: ' 签收',
          content: ''
        }
      ],
      purchase: [
        {
          title: ' 申请中',
          content: ''
        },
        {
          title: ' 审核',
          content: ''
        },
        {
          title: ' 采购',
          content: ''
        },
        {
          title: ' 入库',
          content: ''
        }
      ]
    }
  },
  mounted () {
    this.update(this.progress)
  },
  methods: {
    update (progress) {
      this.newdata = []
      for (let v in progress) {
        // console.log(progress[v].ctime.slice(5, 16))
        let title = `${progress[v].uname}${progress[v].status_name}`
        let content = progress[v].remark ? `${progress[v].ctime.slice(5, 16)} ${progress[v].remark}` : `${progress[v].ctime.slice(5, 16)}`
        // if (this.newdata.length < 4) {
        this.newdata.push({title, content})
        // }
      }
      this.current = this.newdata.length

      if (this.newdata.length < 4) {
        if (this.type === 'warehouse') {
          for (let s = this.newdata.length; this.newdata.length < 4; s++) {
            this.newdata.push(this.warehouse[s])
          }
        } else if (this.type === 'purchase') {
          for (let s = this.newdata.length; this.newdata.length < 4; s++) {
            this.newdata.push(this.purchase[s])
          }
        }
      }
    }
  },
  watch: {
    // 监听传入流程数据 更新
    progress: {
      deep: true,
      handler: function (v1, v2) {
        this.update(v1)
      }
    }
  }
}
</script>

<style scoped>

</style>
