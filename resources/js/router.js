import { createRouter, createWebHashHistory } from 'vue-router'
import store from './store'

function generateRoutes() {
    let routes = []
    let menu = []
    if (typeof window._MENU_ !== 'undefined') {
        console.log(_MENU_)
        _MENU_.forEach((el) => {
            const appInfo = require(`../../apps/${el}/inc/${el}.js`)
            routes = routes.concat(appInfo.default.routes)
            menu.push({
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


