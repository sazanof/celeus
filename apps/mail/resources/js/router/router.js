import { createRouter, createWebHashHistory } from 'vue-router'
import AddAccount from '../../components/AddAccount.vue'
import MailPage from '../../components/MailPage.vue'
import { ROOT_URL, ACCOUNT_ADD } from './consts'

export default createRouter({
    history: createWebHashHistory(),
    routes: [
        {
            path: ROOT_URL,
            component: MailPage
        },
        {
            path: ACCOUNT_ADD,
            component: AddAccount
        },
    ]
})