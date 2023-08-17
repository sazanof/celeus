<template>
    <div
        class="mailbox-thread"
        data-simplebar>
        <Message
            v-for="message in messages"
            :key="message.id"
            :message="message" />
    </div>
</template>

<script>
    import Message from './Message.vue'

    export default {
        name: 'MailboxThread',
        components: {
            Message
        },
        computed: {
            id() {
                return parseInt(this.$route.params.id)
            },
            activeMailbox() {
                return this.$store.getters['getActiveMailbox']
            },
            mailbox() {
                return this.$store.getters.getMailbox(this.id)
            },
            messages() {
                return this.$store.getters['getMessages']
            }
        },
        watch: {
            id() {
                this.$store.commit('setActiveMailbox', this.mailbox)
                console.log(`trigger sync mailbox ${this.mailbox.id}`)
                this.getMessages()
            }
        },
        mounted() {
            //TODO trigger account sync
            this.$store.commit('setActiveMailbox', this.mailbox)
        },
        methods: {
            async getMessages() {
                await this.$store.dispatch('getMessages', {
                    id: this.id
                })
            }
        }
    }
</script>

<style scoped>
.mailbox-thread {
	height: calc(100vh - var(--search-height));
}
</style>
