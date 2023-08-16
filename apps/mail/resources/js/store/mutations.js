export default {
    setAccounts(state, accounts) {
        state.accounts = accounts
        this.commit('addAccountMailboxes')
    },
    addAccountMailboxes(state) {
        state.accounts.map(acc => {
            this.commit('mergeMailboxes', acc.mailboxes)
        })
    },
    mergeMailboxes(state, mailboxes) {
        Object.keys(mailboxes).map(el => {
            if (mailboxes[el].children !== undefined && mailboxes[el].children.length > 0) {
                this.commit('mergeMailboxes', mailboxes[el].children)
                //delete mailboxes[el].children // delete?
            }
            state.mailboxes.push(mailboxes[el])
        })
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
    },
    setActiveMailbox(state, mailbox) {
        state.activeMailbox = mailbox
    },
    setActiveAccount(state, account) {
        state.activeAccount = state.accounts.find(a => a.id === account.id)
    },
    setMessages(state, messages) {
        state.messages = messages
    }
}
