import '../css/app.extended.scss'
import 'vue-toastification/dist/index.css'
import Toast from 'vue-toastification'
import i18n from './i18n'
import store from './store'
import { createApp } from 'vue'
import Install from '../components/install/Install.vue'

const options = {
    // You can set your default options here
}

const app = createApp(Install)
//app.config.globalProperties.emitter = emitter
app.use(i18n)
app.use(store)
app.use(Toast, options)
//app.use(createRouter)
app.mount('#install')
