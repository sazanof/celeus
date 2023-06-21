import axios from 'axios'
import { getToken } from '../../../../../resources/js/CSRFToken'

axios.defaults.headers.post['X-CSRF-TOKEN'] = getToken()
axios.defaults.headers.put['X-CSRF-TOKEN'] = getToken()
axios.defaults.headers.common['X-AJAX-CALL'] = true

const PREFIX = '/apps/mail/'

export default {
    async loadAccounts({ commit }) {
        return await axios.get(`${PREFIX}accounts`).then(res => {
            commit('setAccounts', res.data)
            return res.data
        })
    },

    async addAccount({ commit }, data) {
        return await axios.post(`${PREFIX}accounts/add`, data).then(res => {
            return res.data
        })
    },

    async saveAccount({ commit, state }, data) {
        return await axios.put(`${PREFIX}accounts/${data.id}`, data).then(res => {
            commit('saveAccount', res.data)
            return res.data
        })
    }
}