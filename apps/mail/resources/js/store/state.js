export default {
    activeAccount: null,
    accounts: [],
    mailboxes: [],
    messages: [],
    activeMailbox: localStorage.getItem('activeMailbox') !== 'undefined' ? JSON.parse(localStorage.getItem('activeMailbox')) : null,
    page: 1,
    limit: 25
}
