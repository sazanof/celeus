export default {
    getAccounts(state) {
        return state.accounts
    },
    getDefaultAccount(state) {
        return state.accounts.find(acc => acc.isDefault === true)
    },
    getActiveAccount(state) {
        return state.activeAccount
    },
    getAccountMailboxes: (state) => (accountId) => {
        return state.accounts.find(acc => acc.id === accountId).mailboxes
    },
    getMailbox: (state) => (mailboxId) => {
        return state.mailboxes.find(m => m.id === mailboxId)
    },
    getActiveMailbox(state) {
        return state.activeMailbox
    },
    getMessages(state) {
        return state.messages
    }
}
