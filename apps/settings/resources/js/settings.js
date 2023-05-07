import { registerTranslationObject, getLocale, translate } from '../../../../resources/js/l10n'
import { createApp } from 'vue'
import store from '../store'
import Toast from 'vue-toastification'
import Settings from '../components/Settings.vue'
import router from './private/router.js'

const currentLocale = getLocale()
const translationObject = async () => {
    return await import(`../locales/${currentLocale}.json`)
}
const app = createApp(Settings)
app.config.globalProperties.$t = translate
app.use(router)
app.use(store)
app.use(Toast, {})
translationObject().then(res => {
    registerTranslationObject('settings', res.default)
    app.mount('#settings')
})

