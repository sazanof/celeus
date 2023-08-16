<template>
    <div
        id="mail-app"
        v-if="accounts !== null && visible">
        <div
            class="account-add"
            v-if="accounts.length === 0 && $route.path !== url.account_add">
            <NoAccounts />
        </div>
        <div v-else-if="visible">
            <router-view />
        </div>
    </div>
</template>

<script>
    import { ACCOUNT_ADD } from '../js/router/consts'
    import NoAccounts from './NoAccounts.vue'

    export default {
        name: 'Mail',
        components: {
            NoAccounts
        },
        data() {
            return {
                visible: false,
                url: {
                    account_add: ACCOUNT_ADD
                }
            }
        },
        computed: {
            accounts() {
                return this.$store.getters['getAccounts']
            },
            activeMailbox() {
                return this.$store.getters['getActiveMailbox']
            }
        },
        async created() {
            await this.$store.dispatch('loadAccounts').finally(() => {
                this.visible = true
            })
        }
    }
</script>

<style scoped>

</style>
