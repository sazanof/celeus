<template>
    <div id="settings">
        <div class="settings-wrapper">
            <VfSidebar>
                <VfSidebarMenuItem>
                    <template #button>
                        <AccountIcon :size="20" />
                    </template>
                    {{ $t('settings', 'My profile') }}
                </VfSidebarMenuItem>
                <VfSidebarMenuItem>
                    <template #button>
                        <BellIcon :size="20" />
                    </template>
                    {{ $t('settings', 'Notification settings') }}
                </VfSidebarMenuItem>
            </VfSidebar>
            <VfContent>
                {{ user }}
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
        computed: {
            user() {
                return this.$store.getters.getUser
            }
        },
        async mounted() {
            console.log(window.L10N)
            await Emitter.on('store.update.user', (user) => {
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