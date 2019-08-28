<template>
    <Row>
      <div class="card-body-nob">
        <div class="card">
          <Row class="vehicle-text vehicle-bg">
            <Row class="vehile-ma-l">
              <Col span="6">
                <div class="ma-nomb-spacing">
                  车型：<span class=""></span>
                </div>
              </Col>
              <Col span="6">
                <div class="ma-nomb-spacing">
                  车牌：<span class=""></span>
                </div>
              </Col>
              <Col span="6">
                <div class="ma-nomb-spacing">
                  门店：<span class=""></span>
                </div>
              </Col>
              <Col span="6">
                <div class="ma-nomb-spacing">
                  门店：<span class=""></span>
                </div>
              </Col>
            </Row>
          </Row>
          <Row class="vehicle-text">
            <Row class="vehile-ma-l">
              <Col span="6">
                <div class="underline">
                  车型：<span class=""></span>
                </div>
              </Col>
              <Col span="6">
                <div class="underline">
                  车型：<span class=""></span>
                </div>
              </Col>
              <Col span="6">
                <div class="underline">
                  车型：<span class=""></span>
                </div>
              </Col>
              <Col span="6">
                <div class="underline">
                  车型：<span class=""></span>
                </div>
              </Col>
            </Row>
          </Row>
          <Row class="vehicle-text">
            <Row class="vehile-ma-l">
              <Col span="6">
                <div class="underline">
                  车型：<span class=""></span>
                </div>
              </Col>
              <Col span="6">
                <div class="underline">
                  车型：<span class=""></span>
                </div>
              </Col>
              <Col span="6">
                <div class="underline">
                  车型：<span class=""></span>
                </div>
              </Col>
              <Col span="6">
                <div class="underline">
                  车型：<span class=""></span>
                </div>
              </Col>
            </Row>
          </Row>
          <div class="vehicle-iconbg">
            <div class="vehicle-icon">
              <i class="iconfont iconcheliangxinxi fs-28"></i>
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="81px" height="82px">
              <defs>
                <filter filterUnits="userSpaceOnUse" id="Filter_0" x="0px" y="0px" width="81px" height="82px">
                  <feOffset in="SourceAlpha" dx="0" dy="4"></feOffset>
                  <feGaussianBlur result="blurOut" stdDeviation="3"></feGaussianBlur>
                  <feFlood flood-color="rgb(0, 0, 0)" result="floodOut"></feFlood>
                  <feComposite operator="atop" in="floodOut" in2="blurOut"></feComposite>
                  <feComponentTransfer>
                    <feFuncA type="linear" slope="0.19"></feFuncA>
                  </feComponentTransfer>
                  <feMerge>
                    <feMergeNode></feMergeNode>
                    <feMergeNode in="SourceGraphic"></feMergeNode>
                  </feMerge>
                </filter>
              </defs>
              <g filter="url(#Filter_0)">
                <path fill-rule="evenodd" fill="rgb(18, 105, 219)" d="M5.000,69.987 L5.000,9.000 C5.000,6.791 6.791,5.000 9.000,5.000 L69.000,5.000 C69.000,5.000 69.000,5.000 69.000,5.000 C69.000,40.564 40.437,69.452 5.000,69.987 Z"></path>
              </g>
            </svg>
          </div>
        </div>
      </div>
    </Row>

</template>
<script>
  export default {
    data () {
      return {
      }
    },
    comments:{},
    methods:{}
  }
</script>
<style scoped lang="scss">

</style>
