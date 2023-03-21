import Email from 'vue-material-design-icons/Email.vue'
import Mail from '../components/Mail.vue'

export default {
    name: 'Mail',
    description: 'Mail app',
    icon: Email,
    routes: [
        {
            path: '/mail',
            component: Mail

        },
    ]
}