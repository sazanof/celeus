import { createApp } from 'vue'
import { registerTranslationObject, getLocale, translate } from '../../../../resources/js/l10n'
import store from './store/store'
import router from './router/router'
import Mail from '../components/Mail.vue'
import Toast from 'vue-toastification'

const currentLocale = getLocale()
const translationObject = async () => {
    return await import(`../locales/${currentLocale}.json`)
}
const app = createApp(Mail)
app.use(store)
app.use(router)
app.use(Toast)
app.config.globalProperties.$t = translate
translationObject().then(res => {
    registerTranslationObject('mail', res.default)
    app.mount('#mail')
})