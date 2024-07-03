<template>
    <div class="mail-initialized">
        <div class="mail-menu">
            <NewMessageHeader />
            <div
                class="accounts"
                data-simplebar>
                <AccountItem
                    v-for="account in accounts"
                    :key="account.id"
                    :active="true"
                    :account="account"
                    @click="setAccountActive(account)" />
            </div>
        </div>
        <div class="mail-list">
            <SearchMessages />
            <router-view />
        </div>
        <div class="mail-content">
            envelope
        </div>
    </div>
</template>

<script>
    import AccountItem from './chunks/AccountItem.vue'
    import SearchMessages from './chunks/SearchMessages.vue'
    import NewMessageHeader from './chunks/NewMessageHeader.vue'

    export default {
        name: 'MailPage',
        components: {
            AccountItem,
            NewMessageHeader,
            SearchMessages
        },
        computed: {
            accounts() {
                return this.$store.getters['getAccounts']
            },
            defaultAccount() {
                return this.$store.getters['getDefaultAccount']
            },
            activeAccount() {
                return this.$store.getters['getActiveAccount']
            },
            activeMailbox() {
                return this.$store.getters['getActiveMailbox']
            }

        },
        created() {
            this.$store.commit('setActiveAccount', this.defaultAccount)
        },
        methods: {
            setAccountActive(account) {
                this.$store.commit('setActiveAccount', account)
            }
        }
    }
</script>

<style lang="scss" scoped>
.mail-initialized {
    margin: calc(var(--padding-box) * -1);
    height: calc(100vh - var(--header-height));
    display: flex;
    flex-wrap: wrap;

    .mail-menu {
        height: inherit;
        width: 350px;
        background: var(--color-primary-opacity5);
    }

    .mail-list {
        height: inherit;
        width: 480px;
    }

    .mail-list {
        border-right: var(--border-width) solid var(--border-color);
    }

    .mail-content {
        padding: var(--padding-box);
        width: calc(100% - 830px);
    }

    .accounts {
        height: calc(100vh - var(--header-height) - var(--new-message-height));
    }
}

</style>
