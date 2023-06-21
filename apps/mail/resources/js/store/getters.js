export default {
    getAccounts(state) {
        return state.accounts
    },
    getDefaultAccount(state) {
        return state.accounts.find(acc => acc.isDefault === true)
    },
    getActiveAccount(state) {
        return state.activeAccount
    }
}
