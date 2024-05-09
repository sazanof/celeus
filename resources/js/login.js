import '../css/app.extended.scss'
import 'vue-toastification/dist/index.css'
import Toast from 'vue-toastification'
import { translate, registerTranslationObject, getLocale } from './l10n'
import store from './store'
import { createApp } from 'vue'
import Login from '../components/pages/Login.vue'

const currentLocale = getLocale()

const translationObject = async () => {
    return await import(`../locales/${currentLocale}.json`)
}

translationObject().then(res => {
    registerTranslationObject('core', res.default)

    const app = createApp(Login)
    app.config.globalProperties.$t = translate
    app.use(store)
    app.use(Toast, {})
    app.mount('#login')
})

