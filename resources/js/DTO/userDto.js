export default class UserDto {
    constructor(user = {}) {
        if (typeof user === 'object') {
            this.user = Object.assign({
                username: '',
                email: '',
                firstname: '',
                lastname: '',
                photo: '',
                language: '',
                organization: '',
                position: '',
                phone: null,
                about: '',
                groups: [],
            }, user)
        }
        return this.user
    }

    set(key, value) {
        if (this.user.hasOwnProperty(key)) {
            this.user[key] = value
        }
    }
}