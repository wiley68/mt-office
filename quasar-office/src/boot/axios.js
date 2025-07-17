import { boot } from 'quasar/wrappers'
import axios from 'axios'

const api = axios.create({
  baseURL: window.mt_office_rest?.root || 'http://avalon.local/wp-json/',
  withCredentials: true,
})

export default boot(({ app }) => {
  app.config.globalProperties.$axios = axios
  app.config.globalProperties.$api = api
})

export { axios, api }
