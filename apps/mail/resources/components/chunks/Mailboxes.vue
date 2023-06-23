<template>
    <div class="mailboxes">
        {{ mailboxesTree }}
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
        data() {
            return {
                tree: [
                    'A',
                    'A|A-A',
                    'A|A-A|A-A-A',
                    'B',
                    'B|B-B',
                    'B|B-B|B-B-B',
                ]
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
            mailboxesTree() {

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
            },

        }
    }
</script>

<style scoped>

</style>
