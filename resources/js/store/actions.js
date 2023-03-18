import axios from 'axios'
//axios.defaults.headers.common['X-CSRF-TOKEN'] = token;
//TODO CSRF Protection
export default {
    async getLocales({ commit, state }) {
        const locales = await axios.get('/locales')
        commit('setLocales', locales.data)
    },

    async checkUserIsAuthenticated({ commit }) {
        return await axios.get('/login/check').then(res => {
            commit('setAuthenticated', res.data.success)
        })
    },

    async getUser({ commit }) {
        return await axios.get('/user').then(res => {
            commit('setUser', res.data)
            commit('setAcl', res.data)
        })
    },

    async logIn({ commit, state }, credentials) {
        const username = credentials.username
        const password = credentials.password
        console.log(username, password)
    }
}