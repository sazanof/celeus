import { createRouter, createWebHashHistory } from 'vue-router'
import ProfileEdit from '../../components/ProfileEdit.vue'

export default createRouter({
    history: createWebHashHistory(),
    routes: [
        {
            path: '/profile',
            component: ProfileEdit
        },
        {
            path: '/notifications',
            component: ProfileEdit
        },
    ]
})