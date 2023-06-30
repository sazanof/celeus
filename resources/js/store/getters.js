export default {
    getLocales(state) {
        return state.locales
    },

    isAuthenticated(state) {
        return state.authenticated
    },

    getUser(state) {
        return state.user
    },

    getAcl(state) {
        return state?.user?.acl
    },
}
