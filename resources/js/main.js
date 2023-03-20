import '../css/app.scss'
import 'vue-toastification/dist/index.css'
import Toast from 'vue-toastification'
import i18n from './i18n'
import store from './store'
import { createApp } from 'vue'
import App from '../components/App.vue'
import { router } from './router'

const app = createApp(App)
app.use(i18n)
app.use(store)
app.use(Toast, {})
app.use(router)
app.mount('#app')