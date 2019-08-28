<template>
  <div id="app">
    <router-view />
  </div>
</template>

<style lang="scss">
  #app {
    font-family: "Avenir", Helvetica, Arial, sans-serif;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    color: #2c3e50;

    .content {
      position: absolute;
      background: #f2f2f2;
      width: 100%;
    }

    //无数据
    .no-data {
      // background: #fff;
      text-align: center;
      padding-top: 200px;

      img {
        width: 140px;
        height: 140px;
      }
    }

    //加载动画
    .data-loding {
      display: flex;
      justify-content: center;
      align-items: center;
      padding-top: 200px;

      img {
        width: 30px;
        height: 30px;
      }
    }

    // router-link样式隐藏
    a,
    a:hover,
    a:active,
    a:visited,
    a:link,
    a:focus {
      -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
      -webkit-tap-highlight-color: transparent;
      outline: none;
      background: none;
      text-decoration: none;
    }

    .router-link-active {
      text-decoration: none;
    }

    // flex布局
    .flex {
      display: flex;
    }

    .flex-item-1 {
      flex: 1;
    }

    .flex-item-grow {
      flex-grow: 2;
    }

    .center {
      text-align: center;
    }

    .all-center {
      display: flex;
      justify-content: center;
      align-items: center;
    }
  }
</style>