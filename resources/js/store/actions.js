import axios from 'axios'
//axios.defaults.headers.common['X-CSRF-TOKEN'] = token;
//TODO CSRF Protection
export default {
    async getLocales({ commit, state }) {
        const locales = await axios.get('/locales')
        commit('setLocales', locales.data)
    }
}