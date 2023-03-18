export default {
    setLocales(state, locales) {
        state.locales = locales
    },

    setAuthenticated(state) {
        state.authenticated = state?.user?.authenticated
    },

    setUser(state, user) {
        state.user = user
    },

    setAcl(state, user) {
        state.acl = user.acl
    }
}