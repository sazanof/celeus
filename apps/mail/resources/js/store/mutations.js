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
    },
    setMailboxes(state, data) {
        //TODO check existing
        const accountId = data.accountId
        const mailboxes = data.mailboxes
        state.accounts = state.accounts.map(acc => {
            if (acc.id === accountId) {
                acc.mailboxes = mailboxes
            }
            return acc
        })
    }
}
