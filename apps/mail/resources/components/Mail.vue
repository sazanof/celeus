<template>
    <div
        id="mail-app"
        v-if="accounts !== null">
        <div
            class="account-add"
            v-if="accounts.length === 0 && $route.path !== url.account_add">
            <NoAccounts />
        </div>
        <div v-else>
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
                url: {
                    account_add: ACCOUNT_ADD
                }
            }
        },
        computed: {
            accounts() {
                return this.$store.getters['getAccounts']
            }
        },
        async beforeCreate() {
            await this.$store.dispatch('loadAccounts')
        }
    }
</script>

<style scoped>

</style>