import { translate, registerTranslationObject, getLocale } from './l10n'
import 'animate.css'
import 'simplebar/dist/simplebar.css'
import 'vue-toastification/dist/index.css'
import 'vue-advanced-cropper/dist/style.css'
import 'floating-vue/dist/style.css'
import '../css/sass/app.scss'
import moment from 'moment'
import Toast from 'vue-toastification'
import store from './store'

import Emitter from './emitter'
import { createApp } from 'vue'
import PageHeader from '../components/chunks/PageHeader.vue'
import 'simplebar' // or "import SimpleBar from 'simplebar';" if you want to use it manually.

import ResizeObserver from 'resize-observer-polyfill'

window.ResizeObserver = ResizeObserver

const currentLocale = getLocale()

const translationObject = async () => {
    return await import(`../locales/${currentLocale}.json`)
}

 
translationObject().then(res => {
    registerTranslationObject('core', res.default)
    const app = createApp(PageHeader)
    app.config.globalProperties.$moment = moment
    app.config.globalProperties.$locale = currentLocale
    app.config.globalProperties.$t = translate
    app.use(store)
    app.use(Toast, {})
    app.mount('#header')
})

