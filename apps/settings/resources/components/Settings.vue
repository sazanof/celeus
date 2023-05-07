<template>
    <div id="settings">
        <div class="settings-wrapper">
            <VfSidebar>
                <VfSidebarMenuItem to="/profile">
                    <template #button>
                        <AccountIcon :size="20" />
                    </template>
                    {{ $t('settings', 'My profile') }}
                </VfSidebarMenuItem>
                <VfSidebarMenuItem to="/notifications">
                    <template #button>
                        <BellIcon :size="20" />
                    </template>
                    {{ $t('settings', 'Notification settings') }}
                </VfSidebarMenuItem>
            </VfSidebar>
            <VfContent>
                <router-view />
            </VfContent>
        </div>
    </div>
</template>

<script>
    import AccountIcon from 'vue-material-design-icons/Account.vue'
    import BellIcon from 'vue-material-design-icons/Bell.vue'
    import VfContent from '../../../../resources/components/elements/VfContent.vue'
    import VfSidebar from '../../../../resources/components/elements/VfSidebar.vue'
    import VfSidebarMenuItem from '../../../../resources/components/elements/VfSidebarMenuItem.vue'

    export default {
        name: 'Settings',
        components: {
            VfSidebar,
            VfContent,
            VfSidebarMenuItem,
            AccountIcon,
            BellIcon
        },
        computed: {},
        beforeCreate() {
            if (this.$route.path === '/') {
                this.$router.push('/profile')
            }
        },
        beforeMount() {
            console.log('Settings,vue Emitter.on(\'store.update.user\') ')
            Emitter.on('store.update.user', (user) => {
                this.$store.commit('saveUser', user)
            })
        },

    }
</script>

<style lang="scss" scoped>
.settings-wrapper {
  display: flex;
  flex-wrap: wrap;
  width: 100%;
}
</style>