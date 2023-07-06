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
            <VfPopper>
                <VfButton
                    type="transparent"
                    :only-icon="true">
                    <template #icon>
                        <CogIcon :size="20" />
                    </template>
                </VfButton>
                <template #popper>
                    <VfList>
                        <VfListItem @click="$router.push({name:'add_account'})">
                            <template #icon>
                                <PlusIcon :size="20" />
                            </template>
                            {{ $t('mail', 'Add account') }}
                        </VfListItem>
                    </VfList>
                </template>
            </VfPopper>
        </div>
    </div>
</template>

<script>
    import VfList from '../../../../../resources/components/elements/VfList.vue'
    import VfListItem from '../../../../../resources/components/elements/VfListItem.vue'
    import VfPopper from '../../../../../resources/components/elements/VfPopper.vue'
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
            MenuIcon,
            VfPopper,
            VfList,
            VfListItem
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
