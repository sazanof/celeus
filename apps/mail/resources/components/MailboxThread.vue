<template>
    <div class="mailbox">
        {{ mailbox }} {{ id }}
    </div>
</template>

<script>
    export default {
        name: 'Mailbox',
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
        },
        watch: {
            id() {
                this.$store.commit('setActiveMailbox', this.mailbox)
                console.log(`trigger sync mailbox ${this.mailbox.id}`)
            }
        },
        mounted() {
            //TODO trigger account sync
            this.$store.commit('setActiveMailbox', this.mailbox)
            console.log(`trigger sync mailbox ${this.mailbox.id}`)
        }
    }
</script>

<style scoped>

</style>
