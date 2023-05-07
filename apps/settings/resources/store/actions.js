import axios from 'axios'
import { getToken } from '../../../../resources/js/CSRFToken'

axios.defaults.headers.post['X-CSRF-TOKEN'] = getToken()
axios.defaults.headers.common['X-AJAX-CALL'] = true

const PREFIX = '/app/settings/'

export default {
    async saveUser({}, user) {
        return await axios.post(`${PREFIX}profile`, user)
    }
}
