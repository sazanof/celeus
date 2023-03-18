<template>
    <Login v-if="!authenticated" />
</template>

<script>
    import { useToast } from 'vue-toastification'
    import Login from './pages/Login.vue'

    const toast = useToast()

    export default {
        name: 'App',
        components: {
            Login
        },
        data() {
            return {
                authenticated: false,
            }
        },
        async beforeMount() {
            await this.$store.dispatch('checkUserIsAuthenticated')
        },
        created() {
            this.authenticated = this.$store.getters['isAuthenticated']
        }
    }
</script>

<style lang="scss" scoped>
</style>
