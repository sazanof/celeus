import axios from 'axios'
import { getToken } from '../../../../resources/js/CSRFToken'

axios.defaults.headers.post['X-CSRF-TOKEN'] = getToken()
axios.defaults.headers.common['X-AJAX-CALL'] = true

const PREFIX = '/app/settings/'

export default {
    async saveUser({}, user) {
        return await axios.post(`${PREFIX}profile`, user)
    },
    async saveUserPhoto({ state }, { file, coordinates }) {
        const res = await axios.post(`${PREFIX}profile/photo`, { file, coordinates }, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        })
        return res.data
    }
}
