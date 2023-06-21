import { createStore } from 'vuex'
import actions from './actions'
import state from './state'
import mutations from './mutations'
import getters from './getters'

export default createStore({
    actions,
    mutations,
    getters,
    state
})