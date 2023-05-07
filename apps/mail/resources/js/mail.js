import { createApp } from 'vue'
import store from './store/store'
import Mail from '../../components/Mail.vue'

const app = createApp(Mail)
app.use(store)
app.mount('#mail')