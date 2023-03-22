import { markRaw } from 'vue'
import i18n from '../../../resources/js/i18n'
import Email from 'vue-material-design-icons/Email.vue'
import Mail from '../components/Mail.vue'

export default {
    name: 'Mail',
    description: 'Mail app',
    icon: markRaw(Email),
    routes: [
        {
            path: '/mail',
            component: Mail,
            meta: {
                title: i18n.global.t('Mail')
            }
        },
    ]
}