<template>
    <div class="header-notifications">
        <Popper
            :arrow="true"
            @open:popper="openNotificationsPanel">
            <VfButton
                :only-icon="true">
                <template #icon>
                    <Bell :size="20" />
                </template>
            </VfButton>
            <template #content>
                Notifications-content
            </template>
        </Popper>
    </div>
</template>

<script>
    import VfButton from '../elements/VfButton.vue'
    import Popper from 'vue3-popper'
    import Bell from 'vue-material-design-icons/Bell.vue'

    export default {
        name: 'PageNotifications',
        components: {
            VfButton,
            Bell,
            Popper
        },
        methods: {
            openNotificationsPanel() {
                console.log('Open notifications panel')
            }
        }
    }
</script>

<style lang="scss" scoped>
.header-notifications {
  margin-right: 10px;
}
</style>