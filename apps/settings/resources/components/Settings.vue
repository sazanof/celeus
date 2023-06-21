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
                <VfSidebarMenuItem to="/regional">
                    <template #button>
                        <EarthIcon :size="20" />
                    </template>
                    {{ $t('settings', 'Regional settings') }}
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
    import EarthIcon from 'vue-material-design-icons/Earth.vue'
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
            BellIcon,
            EarthIcon
        },
        computed: {},
        beforeCreate() {
            if (this.$route.path === '/') {
                this.$router.push('/profile')
            }
            const user = atob(document.querySelector('input[name="preload_state_user"]').value)
            this.$store.commit('saveUser', JSON.parse(user))
        },
        created() {
            console.log('MOUNT Settings.vue')
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