import axios from 'axios'
import { getToken, setToken } from '../CSRFToken'

axios.defaults.headers.post['X-CSRF-TOKEN'] = getToken()
axios.defaults.headers.common['X-AJAX-CALL'] = true

//TODO CSRF Protection
export default {
    async getLocales({ commit, state }) {
        const locales = await axios.get('/locales')
        commit('setLocales', locales.data)
    },

    async checkUserIsAuthenticated({ commit, state }) {
        return await axios.get('/login/check').then(res => {
            commit('setAuthenticated', res.data.success === undefined && res.data.id !== undefined)
            if (state.authenticated) {
                commit('setUser', res.data)
                Emitter.emit('store.update.user', state.user)
                console.log('1) Check user and fire event в App.vue, ')
            }
        })
    },

    async getUser({ commit }) {
        return await axios.get('/user').then(res => {
            commit('setUser', res.data)
            commit('setAcl', res.data)
        })
    },

    async logIn({ commit, state }, credentials) {
        return await axios.post('/login/process', credentials).then(res => {
            if (res.data !== undefined && res.data !== null) {
                commit('setAuthenticated', res.data.username === credentials.username)
            }
            return res.data
        })
    },

    async logOut({ commit, state }, credentials) {
        return await axios.get('/logout').then(res => {
            if (res.data !== undefined && res.data !== null) {
                commit('setAuthenticated', false)
            }
        })
    }
}
