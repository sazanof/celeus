export default {

    getMenu(state) {
        return state.menu
    },
    setLocales(state, locales) {
        state.locales = locales
    },

    setAuthenticated(state, authenticated) {
        state.authenticated = authenticated
    },

    setUser(state, user) {
        state.user = user
    },

    setAcl(state, user) {
        state.acl = user.acl
    }
}