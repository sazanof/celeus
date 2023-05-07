import UserDto from '../DTO/userDto.js'

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
        state.user = new UserDto(user)
    },

    setAcl(state, user) {
        state.acl = user.acl
    }
}