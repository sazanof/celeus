import axios from 'axios'

export default {
    async getLocales({ commit, state }){
        const locales = await axios.get('/locales')
        commit('setLocales', locales.data)
    }
}