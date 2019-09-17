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
    name: "steps",
    props: ["file","type"],

    data(){
      return{
        newdata:[],
        current:'',
        warehouse : [
          {
            title:' 申请中',
            content:''
          },
          {
            title:'审核',
            content:''
          },
          {
            title:' 配货',
            content:''
          },
          {
            title:' 签收',
            content:''
          },
        ],
        purchase:[
          {
            title:' 申请中',
            content:''
          },
          {
            title:' 审核',
            content:''
          },
          {
            title:' 采购',
            content:''
          },
          {
            title:' 入库',
            content:''
          },
        ]
      }
    },
    created () {
      let progress =  this.file.progress
      for (let v in progress){
        let title = `${progress[v].uname}${progress[v].status_name}`
        let content = progress[v].remark ?  progress[v].remark :'暂无备注'
        if (this.newdata.length<4){
          this.newdata.push({title,content})
        }
      }
      this.current = this.newdata.length
      console.log(this.type)

      if (this.newdata.length<4){
        if (this.type =='warehouse'){
          for (let s=this.newdata.length;this.newdata.length<4;s++){
            this.newdata.push(this.warehouse[s])

          }

        }else if (this.type=='purchase'){
          for (let s=this.newdata.length;this.newdata.length<4;s++){
            this.newdata.push(this.purchase[s])

          }

        }



      }
    },
    }
</script>

<style scoped>

</style>
