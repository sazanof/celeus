<template>
    <div
        class="mailboxes">
        <MailboxItem
            v-for="mailbox in mailboxes"
            :key="mailbox.id"
            :mailbox="mailbox" />
    </div>
</template>

<script>
    import MailboxItem from './MailboxItem.vue'

    export default {
        name: 'Mailboxes',
        components: {
            MailboxItem
        },
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
            },
            activeMailbox() {
                return this.$store.getters['getActiveMailbox']
            }
        },
        async created() {
            if (this.isActive && Object.keys(this.mailboxes).length) {
                this.$store.commit('setActiveMailbox', this.mailboxes[0])
                if (this.$route.params.id === undefined) {
                    this.$router.push(`/mbox/${this.activeMailbox.id}`)
                }
            }
            await this.getMailboxes()
        },
        methods: {
            async getMailboxes() {
                await this.$store.dispatch('getMailboxes', this.id)
            },
        }
    }
</script>

<style scoped>

</style>
