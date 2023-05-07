import { createStore } from 'vuex'
import actions from './actions'
import state from './state'

export default createStore({
    actions,
    state
})