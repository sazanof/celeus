<template>
    <div
        class="container"
        :class="{visible: visible}"
        v-if="visible">
        <Login v-if="!authenticated" />
        <Page />
    </div>
</template>

<script>
    import { useToast } from 'vue-toastification'
    import Login from './pages/Login.vue'
    import Page from './pages/Page.vue'

    const toast = useToast()

    export default {
        name: 'App',
        components: {
            Login,
            Page
        },
        data() {
            return {
                visible: false,
            }
        },
        computed: {
            authenticated() {
                return this.$store.getters.isAuthenticated
            }
        },
        async beforeMount() {
            this.$store.state.currentLocale = this.$i18n.locale
            await this.$store.dispatch('checkUserIsAuthenticated')
            setTimeout(() => {
                this.visible = true
            }, 200)

        },
    }
</script>

<style lang="scss" scoped>
</style>
