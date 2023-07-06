import { createRouter, createWebHashHistory } from 'vue-router'
import AddAccount from '../../components/AddAccount.vue'
import MailPage from '../../components/MailPage.vue'
import { ROOT_URL, ACCOUNT_ADD } from './consts'
import Mailbox from '../../components/MailboxThread.vue'

export default createRouter({
    history: createWebHashHistory(),
    routes: [
        {
            path: ROOT_URL,
            component: MailPage,
            children: [
                {
                    path: '/mbox/:id(\\d+)',
                    component: Mailbox
                }
            ]
        },
        {
            path: ACCOUNT_ADD,
            component: AddAccount,
            name: 'add_account'
        },
    ]
})
