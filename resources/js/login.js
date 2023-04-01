import '../css/app.extended.scss'
import 'vue-toastification/dist/index.css'
import Toast from 'vue-toastification'
import i18n from './i18n'
import store from './store'
import { createApp } from 'vue'
import Login from '../components/pages/Login.vue'

const app = createApp(Login)
app.use(i18n)
app.use(store)
app.use(Toast, {})
app.mount('#login')