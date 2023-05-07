import axios from 'axios'

const PREFIX = 'mail'

export default {
    async loadAccounts() {
        return await axios.get('mail/accounts')
    }
}