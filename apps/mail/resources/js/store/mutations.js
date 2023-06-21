export default {
    setAccounts(state, accounts) {
        state.accounts = accounts
    },
    saveAccount(state, account) {
        if (state.accounts.length === 0) {
            state.accounts.push(account)
        } else {
            state.accounts = state.accounts.map(acc => {
                console.log(acc, account)
                if (acc.id === account.id) {
                    return account
                }
                return acc
            })
        }
    },
    setActiveAccount(state, account) {
        state.activeAccount = state.accounts.find(a => a.id === account.id)
    }
}
