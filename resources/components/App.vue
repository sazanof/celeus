<template>
    <div>test <span>аtrrrуа111</span></div>
</template>

<script>
    import axios from 'axios'

    export default {
        name: 'App',
        data() {
            return {
                authenticate: false,
            }
        },
        mounted() {
            this.authenticate = this.$store.state.authenticate
        }
    }
</script>

<style lang="scss" scoped>
</style>
