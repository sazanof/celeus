import { translate, registerTranslationObject, getLocale } from './l10n'
import 'animate.css'
import 'vue-toastification/dist/index.css'
import moment from 'moment'
import Toast from 'vue-toastification'
import store from './store'
import { createApp } from 'vue'
import PageHeader from '../components/chunks/PageHeader.vue'

const currentLocale = getLocale()
const _moment = moment().locale(currentLocale)

const translationObject = async () => {
    return await import(`../locales/${currentLocale}.json`)
}

translationObject().then(res => {
    registerTranslationObject('core', res.default)
    const app = createApp(PageHeader)
    app.config.globalProperties.$moment = _moment
    app.config.globalProperties.$t = translate
    app.use(store)
    app.use(Toast, {})
    app.mount('#header')
})

