<template>
    <div class="mailbox">
        {{ mailbox.name }}
    </div>
</template>

<script>
    export default {
        name: 'MailboxItem',
        props: {
            mailbox: {
                type: Object,
                required: true
            }
        }
    }
</script>

<style scoped>

</style>
