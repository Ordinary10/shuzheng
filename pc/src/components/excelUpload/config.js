export default {
  /*
  *批量导出
  * */
  // 导出基础资料信息
  'exportBasicsData': 'ExportBasicsData/exportBasicsInfo',
  // 导出车辆
  'exportCar': 'car/exportCars',
  // 导出保单
  'exportInsurance': 'car/isuranceExportData',
  // 导出年审
  'exportAnnual': 'car/exportAnnual',
  // 导出违章
  'exportIllegal': 'IllegalSearch/DerivedTable',
  // 导出维修保养
  'exportUpkeep': 'CarUpkeep/exportCarUpkeep',
  // 导出合同
  'exportContract': 'LeasingCompany/getContract',
  // 导出订单
  'exportOrder': 'Order/exportiOrder',
  // 导出收费
  /*
  * 批量导入
  * */
  // 导入车辆
  'importCar': {
    'fun': 'BulkImport/carImportData',
    'demo': 'carImport',
    'exts': 'xlsx',
    'str': `
                       <p>门店编号,户主编号,车型编号,仓库编号为数字，请下载基础数据查看 </p>
                       <p>日期格式必须设置为yyyy-mm-dd格式，如：2019-07-02</p>
                       <p>上传的车辆默认为新车，状态为在库待销售</p>
                       <p>年审日期自动在上牌时间基础上累加</p>
                       <p>上传格式支持xlsx或xls</p>
                       <p>模板中标题标红的为必填项</p>
                        `
  },
  // 导入GPS
  'importGps': {
    'fun': 'BulkImport/carGpsImport',
    'demo': 'carGps',
    'exts': 'xlsx',
    'str': ` 
                <p> </p>
                  `
  },
  // 导入保单
  'importInsurance': {
    'fun': 'BulkImport/insuranceImportData',
    'demo': 'insuranceImport',
    'exts': 'xlsx',
    'str': `
                        <p>日期格式必须设置为yyyy-mm-dd格式，如：2019-07-02</p>
                        <p>有录入交强险金额则更新交强险到期时间</p>
                       <p>有录入商业险金额则更新商业险到期时间</p>
                       <p>保单开始日期和结束日期必填</p>
                        <p>商业险与交强险必填其中一项</p>
                        <p>填写保单号有则更新,无则添加</p>
                       <p>无保单号则直接新增保单</p>
                       <p>上传格式支持xlsx或xls</p>
                        `
  },
  // 导入报价
  'importQuote': {
    'fun': 'BulkImport/insuranceQuote',
    'demo': 'insuranceQuote',
    'exts': 'xlsx',
    'str': `
                        <p>日期格式必须设置为yyyy-mm-dd格式，如：2019-07-02</p>
                       <p>上传格式支持xlsx或xls</p>
                        `

  },
  // 导入年审
  'importAnnual': {
    'fun': 'BulkImport/annualImportData',
    'demo': 'annualImport',
    'exts': 'xlsx',
    'str': `
                        <p>日期格式必须设置为yyyy-mm-dd格式，如：2019-07-02</p>
                       <p>上传格式支持xlsx或xls</p>
                       
                        `
  },
  // 导入维修
  'repairImport': {
    'fun': 'BulkImport/carRepairsImportData',
    'demo': 'carRepairs',
    'exts': 'xlsx',
    'str': `
                       <p>日期格式必须设置为yyyy-mm-dd格式，如：2019-07-02</p>
                       <p>上传格式支持xlsx或xls</p>
                        `
  },
  // 导入保养
  'importUpkeep': {
    'fun': 'BulkImport/carUpkeepImportData',
    'demo': 'carUpkeep',
    'exts': 'xlsx',
    'str': `
                       <p>日期格式必须设置为yyyy-mm-dd格式，如：2019-07-02</p>
                       <p>上传格式支持xlsx或xls</p>
                        `
  },
  // 导入合同
  'importContract': {
    'fun': 'Picture/uploadContract',
    'demo': '',
    'exts': 'word',
    'str': '注：请上传word文件或pdf文件'
  },

  // 流水信息
  'driverSn': {
    'driverSnUploadIn': {
      'upload_fun': 'bulkImport/importDriverSn',
      'upload_demo': 'driverSn',
      'exts': 'xlsx',
      'str': `
                       <p>订单ID，流水金额，接单数，服务分为数字，请下载基础数据查看 </p>
                       <p>日期格式必须设置为yyyy-mm-dd格式，如：2019-07-02 </p>
                       <p>上传格式支持xlsx或xls </p>
                       <p>模板中标题标红的为必填项 </p>
                        `
    }
  },
  // 平账信息
  'repay': {
    // 期款平账
    'staging': {
      'upload_fun': 'BulkImport/repayImportData',
      'upload_demo': 'repayImport',
      'exts': 'xlsx',
      'str': `
                       <p>日期格式必须设置为yyyy-mm-dd格式，如：2019-07-02</p>
                       <p>实收月供只能等于0或者应收月供，0则不平账</p>
                      <p>上传格式支持xlsx或xls</p>
                        `
    },
    // 保险平账
    'insurance': {
      'upload_fun': 'BulkImport/importChargeQuote',
      'upload_demo': 'chargeQuote',
      'exts': 'xlsx',
      'str': `
                         <p>日期格式必须设置为yyyy-mm-dd格式，如：2019-07-02</p>
                         <p>上传格式支持xlsx或xls</p>
                         <p>报单价ID从保费催收下载列表查看</p>
                        `
    },
    'export': ''
  },
  // 金融方案
  'financal': {
    // 金融方案
    'plan': {
      'upload_fun': 'BulkImport/carFinancialImportData',
      'upload_demo': 'carFinancial',
      'exts': 'xlsx',
      'str': `
                       <p>日期格式必须设置为yyyy-mm-dd格式，如：2019-07-02</p>
                       <p>还款金额只能等于应还金额</p>
                       <p>上传格式支持xlsx或xls</p>
                        `
    },
    'export': 'CarFinancial/exportTableData'
  },
  // 出险理赔
  'claim': {
    'claim': {
      'upload_fun': 'BulkImport/carThreatenedImportData',
      'upload_demo': 'carThreatened',
      'exts': 'xlsx',
      'str': `
                       <p>日期格式必须设置为yyyy-mm-dd格式，如：2019-07-02</p>
                       <p>上传格式支持xlsx或xls</p>
                        `
    },
    'export': 'CarThreatened/exportThreatened'
  },
  // 保费催收
  'collection': {
    'editorList': {
      'upload_fun': 'BulkImport/carPremiumimport',
      'upload_demo': 'carPremium',
      'exts': 'xlsx',
      'str': `
                       <p>日期格式必须设置为yyyy-mm-dd格式，如：2019-07-02</p>
                       <p>上传格式支持xlsx或xls</p>
                        `
    },
    'export': ''
  },
  // 批量编辑
  'orderList': {
    'editor': {
      'upload_fun': 'bulkImport/orderAmendImportData',
      'upload_demo': 'orederAmend',
      'exts': 'xlsx',
      'str': `
                        <p>请下载基础数据查看订单编号</p>
                        <p>日期格式必须设置为yyyy-mm-dd格式，如：2019-07-02</p>
                        <p>上传格式支持xlsx或xls</p>
                        `
    },
    'export': ''
  },
  // 批量分配司管
  'distributionList': {
    'options': {
      'upload_fun': 'bulkImport/importDriverManager',
      'upload_demo': 'driverManager',
      'exts': 'xlsx',
      'str': `
                        <p>订单编号、司管编号等，请下载基础数据表进行核对</p>
                        <p>上传格式支持xlsx或xls</p>
                        `
    },
    'export': ''
  },
  // 批量保证金
  'depositList': {
    'options': {
      'upload_fun': 'bulkImport/importDepositAmount',
      'upload_demo': 'depositAmount',
      'exts': 'xlsx',
      'str': `<p>请下载基础数据查看订单编号</p>
                        <p>日期格式必须设置为yyyy-mm-dd格式，如：2019-07-02</p>
                        <p>上传格式支持xlsx或xls</p>
                        `
    },
    'export': ''
  },
  // 批量导入车队
  'driveruesn': {
    'options': {
      'upload_fun': 'BulkImport/motorcadeImport',
      'upload_demo': 'motorcade',
      'exts': 'xlsx',
      'str': `<p>请下载基础数据查看订单编号</p>
                        <p>上传格式支持xlsx或xls</p>
                        `
    },
    'export': ''
  }

}
