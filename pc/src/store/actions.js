export default {
  LOGIN_IN (state, loginData) {
    return new Promise(resolve => {
      sessionStorage.setItem('loginData', JSON.stringify(loginData))
      sessionStorage.setItem('token', loginData.token)
      resolve()
    }).catch(err => {
      console.log(err)
    })
  },
  LOGIN_OUT (state) {
    return new Promise(resolve => {
      sessionStorage.setItem('loginData', '')
      sessionStorage.setItem('token', '')
      resolve()
    }).catch(err => {
      console.log(err)
    })
  }
}
