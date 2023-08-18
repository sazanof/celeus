import '../sass/variables.css.scss'
import { createApp } from 'vue'
import { registerTranslationObject, getLocale, translate } from '../../../../resources/js/l10n'
import store from './store/store'
import router from './router/router'
import Mail from '../components/Mail.vue'
import Toast from 'vue-toastification'
import moment from 'moment'

const currentLocale = getLocale()
const translationObject = async () => {
    return await import(`../locales/${currentLocale}.json`)
}
const app = createApp(Mail)
app.use(store)
app.use(router)
app.use(Toast)
app.config.globalProperties.$t = translate
app.config.globalProperties.$moment = moment
app.config.globalProperties.$locale = currentLocale
translationObject().then(res => {
    registerTranslationObject('mail', res.default)
    app.mount('#mail')
})
