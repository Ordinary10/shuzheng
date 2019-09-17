<template>
  <div>
    <search>
      <div class="search-box">
        <div class="commonly-used-btn-box">
          <Tooltip content="添加新类目" placement="bottom-start">
            <Button class="commonly-used-btn" type="warning" size="large" icon="ios-add-circle-outline" @click="add" style="font-size: 18px"></Button>
          </Tooltip>
        </div>
      </div>
    </search>
  <div class="content-block">
    <Tree class="max_role" :data="type_data" :render="renderContent" @on-select-change="editor" ref="tree" ></Tree>
    <Modal
      v-model="modal1"
      :title="modal1Title"
      :width='600'
      class-name="vertical-center-modal"
      class="overstepModel"
    >
      <Form :model="formItem" :label-width="100" :rules="rule" ref="form" >
        <FormItem label="上级类目" v-show="typeCascaderShow">
          <typeCascader ref="typeCascader" @init="init"  @typeid = 'letType' :echoId="formItem.pid" max-num="2"></typeCascader>
        </FormItem>
        <FormItem label="类目名称" prop="type_name">
          <Input v-model="formItem.type_name" type="text" placeholder="请输入类目名"></Input>
        </FormItem>
      </Form>
      <div slot="footer">
        <Button type="text" size="large" @click="cancel">取消</Button>
        <Button type="primary" size="large" @click="save">确定</Button>
      </div>
    </Modal>
  </div>
  </div>
</template>

<script>
export default {
  name: 'treeType',
  data () {
    return {
      modal1Title: '',
      modal1: false,
      type_data: [],
      typeCascaderShow: true,
      formItem: {
        id: '',
        pid: '',
        type_name: ''
      },
      rule: {
        type_name: [{required: true, message: '必输项不能为空', trigger: 'blur'}
        ]
      }
    }
  },
  created () {
  },
  mounted () {
  },
  methods: {
    init () {
      let typeData = this.$refs.typeCascader.type_data
      let _this = this
      function recursion (list) {
        list.forEach(e => {
          if (e.children && e.children.length) {
            e.expand = true
            recursion(e.children)
          } else {
            e.last = true
            // 第一级显示添加按钮
            if (e.pid === 0) {
              e.last = false
            }
          }
        })
      }
      recursion(typeData)
      // 一次性添加  不然expand会出bug
      _this.type_data = JSON.parse(JSON.stringify(typeData))
    },
    // 重置输入框
    cleanFormItem () {
      this.$refs.form.resetFields()
      for (let key in this.formItem) {
        this.formItem[key] = ''
      }
      this.modal1 = true
    },
    // 左上角从头添加
    add () {
      this.modal1Title = '添加类目'
      this.$refs.form.resetFields()
      for (let key in this.formItem) {
        this.formItem[key] = ''
      }
      this.typeCascaderShow = false
      this.formItem.pid = 0
      this.modal1 = true
    },
    letType () {
      this.formItem.pid = this.$refs.typeCascader.type_id
    },
    renderContent (h, { root, node, data }) {
      return h('span', {
        style: {
          display: 'inline-block',
          width: 'calc(95% - 12px)',
          paddingLeft: '5%',
          height: '32px',
          lineHeight: '32px',
          marginLeft: '5%'
        },
        class: 'item'

      }, [
        h('span', {
          class: 'TypeTitle',
          on: {
            click: () => { this.editor(data) }
          }
        }, [
          h('Icon', {
            style: {
            }
          }),
          h('span', {
            domProps: {
              innerHTML: data.type_name
            },
            style: {
              fontSize: '15px'
            }
          })
        ]
        ),
        h('span', {
          style: {
            display: 'inline-block',
            float: 'right'
          }
        }, [
          h('Button', {
            class: ['addTreeButton', data.last ? 'v-hide' : ''],
            props: {
              icon: 'ios-add',
              type: 'primary',
              ghost: true
            },
            style: {
              marginRight: '8px'
            },
            on: {
              click: (e) => {
                e.stopPropagation()
                this.append(data)
              }
            }
          }),
          h('Button', {
            class: 'removeTreeButton',
            props: {
              icon: 'ios-remove',
              type: 'error',
              ghost: true
            },
            on: {
              click: (e) => {
                e.stopPropagation()
                this.remove(data)
              }
            }
          })
        ])
      ])
    },
    cancel () {
      this.modal1 = false
    },
    // 从类目表中添加
    append (item) {
      // console.log(item)
      this.typeCascaderShow = true
      this.modal1Title = '添加类目'
      this.cleanFormItem()
      this.formItem.pid = item.id
    },
    // 从类目表中删除
    remove (item) {
      // console.log(item)
      let _this = this
      let res = []
      function test (res, list) {
        res.push(list.id)
        if (list.children && list.children.length) {
          list.children.forEach(e => {
            test(res, e)
          })
        }
      }
      test(res, item)
      let content = `<p>确认删除<span class="prominentText">${item.type_name}</span>？</p>
                    <p>${res.length > 1 ? '级下其它子类目也会将删除哦！' : ''}</p>
                    `
      let params = {
        type_id: res.join(',')
      }
      this.$Modal.confirm({
        title: '删除',
        content,
        loading: true,
        onOk: () => {
          _this.$axios('goods/delGoodsType', params, true).then((res) => {
            _this.$Modal.remove()
            if (res.code === 1) {
              _this.$Message.success({
                content: res.msg,
                duration: 2
              })
              this.$refs.typeCascader.init()
            }
          })
        }
      })
    },
    save () {
      let _this = this
      // console.log(this.formItem)
      if (!this.formItem.pid) this.formItem.pid = 0
      if (this.formItem.pid === this.formItem.id) {
        this.$Modal.error({
          title: '类目错误',
          content: '上级类目和自身重复！'
        })
        return false
      }
      _this.$refs.form.validate(valid => {
        if (valid) {
          _this.$axios('goods/editGoodsType', this.formItem).then((res) => {
            if (res.code === 1) {
              _this.$Message.success({
                content: res.msg,
                duration: 2
              })
              this.modal1 = false
              // _this.pageRefresh()
              this.init()
              this.$refs.typeCascader.init()
            }
          })
        } else {
          return false
        }
      })
    },
    editor (item) {
      this.typeCascaderShow = true
      this.cleanFormItem()
      this.modal1Title = `修改类目：${item.type_name}`
      this.formItem.pid = item.pid
      if (this.formItem.pid === 0) {
        this.typeCascaderShow = false
      }
      this.formItem.id = item.id
      this.formItem.type_name = item.type_name
      // console.log(item)
    }
  }
}
</script>

<style scoped lang="scss">
/deep/ .ivu-tree-children{
  .v-hide{
    display: none;
  }
  .ivu-icon{
    font-size: 25px;
  }
}
  .content-block{
    padding: 1%;
    background-color: #fff;
  }
  /deep/ .ivu-tree {
      display: flex;
      flex-wrap: wrap;
      &>.ivu-tree-children{
        width: calc(25% - 40px);
        margin: 20px;
        padding: 0.5% 1% 0.5% 0.5%;
        border: 1px solid #57a3f3;
        border-radius: 10px;
      }
      .item:hover{
        background-color: #f4f4f4;
        border-radius: 5px;
      }

      .TypeTitle{
        cursor: pointer;
        &:hover{
          color: #57a3f3;
        }
      }
        .addTreeButton{
          padding: 0 10px;
          .ivu-icon{
            font-size: 18px;
            font-weight: 700;
          }
        }
      .removeTreeButton{
        padding: 0 10px;
        .ivu-icon{
          font-size: 18px;
          font-weight: 700;
        }
      }

  }
</style>
