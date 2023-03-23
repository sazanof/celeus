import { createRouter, createWebHashHistory } from 'vue-router'

function generateRoutes() {
    return []
}

export const router = createRouter({
    history: createWebHashHistory(),
    routes: generateRoutes(), // short for `routes: routes`
})
