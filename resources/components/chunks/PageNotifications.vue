<template>
    <div class="header-notifications">
        <VfButton
            :only-icon="true">
            <template #icon>
                <BellOutline :size="20" />
            </template>
        </VfButton>
    </div>
</template>

<script>
    import VfButton from '../elements/VfButton.vue'
    import BellOutline from 'vue-material-design-icons/BellOutline.vue'

    export default {
        name: 'PageNotifications',
        components: {
            VfButton,
            BellOutline,
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
