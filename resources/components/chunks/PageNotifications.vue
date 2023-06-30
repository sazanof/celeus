<template>
    <div class="header-notifications">
        <Popper
            :arrow="true"
            @open:popper="openNotificationsPanel">
            <VfButton
                :only-icon="true">
                <template #icon>
                    <BellOutline :size="20" />
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
    import BellOutline from 'vue-material-design-icons/BellOutline.vue'

    export default {
        name: 'PageNotifications',
        components: {
            VfButton,
            BellOutline,
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
