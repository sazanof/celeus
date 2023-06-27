<template>
    <div class="new-message-header">
        <div class="toggle-sidebar">
            <VfButton
                type="transparent"
                :only-icon="true">
                <template #icon>
                    <MenuIcon :size="20" />
                </template>
            </VfButton>
        </div>
        <VfButton>
            <template #icon>
                <PlusIcon :size="20" />
                {{ $t('mail', 'New message') }}
            </template>
        </VfButton>
        <div class="config-button">
            <VfButton
                type="transparent"
                :only-icon="true">
                <template #icon>
                    <CogIcon :size="20" />
                </template>
            </VfButton>
        </div>
    </div>
</template>

<script>
    import PlusIcon from 'vue-material-design-icons/Plus.vue'
    import CogIcon from 'vue-material-design-icons/Cog.vue'
    import MenuIcon from 'vue-material-design-icons/Menu.vue'
    import VfButton from '../../../../../resources/components/elements/VfButton.vue'

    export default {
        name: 'NewMessageHeader',
        components: {
            VfButton,
            PlusIcon,
            CogIcon,
            MenuIcon
        }
    }
</script>

<style lang="scss" scoped>
.new-message-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: var(--padding-box);
  height: var(--new-message-height);
}
</style>
