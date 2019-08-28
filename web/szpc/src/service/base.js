import axios from 'axios'
import qs from 'qs'
import url from './api/url'

export default {
  get(api, params = {}) {
    return new Promise((resolve, reject) => {
      let apiUrl = `${url}${api}${params}`
      axios.get(apiUrl, {
        headers: {
          'token': localStorage.getItem('token')
        }
      }).then((response) => {
        resolve(response.data)
      })
    })
  },
  post(api, params = {}) {
    return new Promise((resolve, reject) => {
      let apiUrl = `${url}${api}`
      axios.post(apiUrl, qs.stringify(params), {
        headers: {
          'token': localStorage.getItem('token')
        }
      }).then((response) => {
        resolve(response.data)
      })
    })
  },
  get_header(api, params = {}) {
    return new Promise((resolve, reject) => {
      let apiUrl = `${url}${api}`
      axios.get(apiUrl, {
        headers: {
          'token': params.token
        }
      }).then((response) => {
        resolve(response.data)
      })
    })
  }
}