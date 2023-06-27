<template>
    <div class="mailbox">
        {{ mailbox.name }}
        <div
            v-if="mailbox.children.length > 0"
            class="children">
            <MailboxItem
                v-for="mbox in mailbox.children"
                :key="mbox.id"
                :mailbox="mbox" />
        </div>
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
        },
        computed: {}
    }
</script>

<style lang="scss" scoped>
.mailbox {
  .children {
	margin-left: 16px;
  }
}
</style>
