<template>
    <div class="mail-initialized">
        <div class="mail-menu">
            <NewMessageHeader />
            <AccountItem
                @click="setAccountActive(account)"
                v-for="account in accounts"
                :key="account.id"
                :active="activeAccount.id === account.id"
                :account="account" />
        </div>
        <div class="mail-list">
            <SearchMessages />
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
            }
        },
        created() {
            this.$store.commit('setActiveAccount', this.defaultAccount)
        },
        mounted() {
            //TODO trigger account sync
            console.log('trigger sync')
        },
        methods: {
            syncAccount(id) {

            },
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
  }

  .mail-list {
	height: inherit;
	width: 400px;
  }

  .mail-list {
	border-left: var(--border-width) solid var(--border-color);
	border-right: var(--border-width) solid var(--border-color);
  }

  .mail-content {
	width: calc(100% - 750px);
  }
}

</style>
