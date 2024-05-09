import axios from 'axios'
import { getToken } from '../../../../../resources/js/CSRFToken'

axios.defaults.headers.post['X-CSRF-TOKEN'] = getToken()
axios.defaults.headers.put['X-CSRF-TOKEN'] = getToken()
axios.defaults.headers.common['X-AJAX-CALL'] = true

const PREFIX = '/apps/mail/'

export default {
    /**
     * Load user accounts
     * @param commit
     * @returns {Promise<axios.AxiosResponse<any>>}
     */
    async loadAccounts({ commit }) {
        return await axios.get(`${PREFIX}accounts`).then(res => {
            commit('setAccounts', res.data)
            return res.data
        })
    },

    /**
     * Add user account
     * @param commit
     * @param data
     * @returns {Promise<axios.AxiosResponse<any>>}
     */
    async addAccount({ commit }, data) {
        return await axios.post(`${PREFIX}accounts/add`, data).then(res => {
            return res.data
        })
    },

    /**
     * Save user account
     * @param commit
     * @param state
     * @param data
     * @returns {Promise<axios.AxiosResponse<any>>}
     */
    async saveAccount({ commit, state }, data) {
        return await axios.put(`${PREFIX}accounts/${data.id}`, data).then(res => {
            commit('saveAccount', res.data)
            return res.data
        })
    },

    /**
     * Get user's account mailboxes
     * @param commit
     * @param id
     * @returns {Promise<axios.AxiosResponse<any>>}
     */
    async getMailboxes({ commit }, id) {
        return await axios.get(`${PREFIX}accounts/${id}/mailboxes`).then(res => {
            commit('setMailboxes', {
                accountId: id,
                mailboxes: res.data
            })
        })
    },

    async getAccountSettings({ commit }, id) {
        return await axios.get(`${PREFIX}accounts/${id}`).then(res => res.data)
    },

    async saveAccountSettings({ commit }, id) {
        return await axios.get(`${PREFIX}accounts/${id}/settings`)
    },

    setActiveMailbox({ commit }, mailbox) {
        commit('setActiveMailbox', mailbox)
    },

    async syncMessages({ commit }, data) {
        return await axios.post(`${PREFIX}mailboxes/${data.id}/sync`, data).then(res => {

        })
    },

    async getMessages({ commit }, data) {
        return await axios.post(`${PREFIX}mailboxes/${data.id}/messages`, data).then(res => {
            commit('setMessages', res.data)
        })
    }
}
