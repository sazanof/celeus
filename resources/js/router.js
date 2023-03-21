import { createRouter, createWebHashHistory } from 'vue-router'
import store from './store'
import axios from 'axios'
import i18n from './i18n'

function generateRoutes() {
    let routes = []
    let menu = []
    if (typeof window._MENU_ !== 'undefined') {
        console.log(_MENU_)
        _MENU_.forEach((el) => {
            const appInfo = require(`../../apps/${el}/inc/${el}.js`)
            routes = routes.concat(appInfo.default.routes)
            menu.push({
                key: el,
                name: appInfo.default.name,
                icon: appInfo.default.icon
            })
        })
    }
    store.state.menu = menu
    return routes
}

export const router = createRouter({
    history: createWebHashHistory(),
    routes: generateRoutes(), // short for `routes: routes`
})

router.beforeEach(async (to, from, next) => {
    console.log(i18n.global)
    const l = store.state.currentLocale
    const appName = to.path.replace('/', '')
    if (_MENU_.indexOf(to.path.replace('/', '')) !== -1) {
        const appMessages = await axios.get(`locales/${l}/${appName}`).then(res => {
            return res.data
        })
        //console.log(i18n.global.messages)
        i18n.global.mergeLocaleMessage(l, appMessages)
    } else {
        next(`/${_MENU_[0]}`)
    }
    return next()
})


