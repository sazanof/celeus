<template>
    <div id="mail">
        Mail
    </div>
</template>

<script>
    export default {
        name: 'Mail',
        beforeCreate() {
            this.$store.dispatch('loadAccounts')
        }
    }
</script>

<style scoped>

</style>