<template>
    <div class="mailboxes">
        {{ mailboxes }}
    </div>
</template>

<script>
    export default {
        name: 'Mailboxes',
        props: {
            account: {
                type: Object,
                required: true
            }
        },
        computed: {
            id() {
                return this.account.id
            },
            isActive() {
                return this.$store.getters['getActiveAccount'].id === this.id
            },
            mailboxes() {
                return this.$store.getters.getAccountMailboxes(this.id)
            }
        },
        created() {
            if (this.isActive) {
                this.getMailboxes()
            }
        },
        methods: {
            async getMailboxes() {
                await this.$store.dispatch('getMailboxes', this.id)
            }
        }
    }
</script>

<style scoped>

</style>
