import 'animate.css'
import '@vueform/multiselect/themes/default.css'
import 'vue-toastification/dist/index.css'
import moment from 'moment'
import Toast from 'vue-toastification'
import i18n from './i18n'
import store from './store'
import { createApp } from 'vue'
import PageHeader from '../components/chunks/PageHeader.vue'

const _moment = moment().locale(i18n.global.locale.value)

const app = createApp(PageHeader)
app.config.globalProperties.$moment = _moment
app.use(i18n)
app.use(store)
app.use(Toast, {})
app.mount('#header')