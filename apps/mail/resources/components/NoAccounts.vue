<template>
    <div class="no-accounts">
        <div class="info">
            <div class="icon">
                <EmailSearchIcon :size="160" />
            </div>
            {{ $t('mail', 'Add your first email account') }}
            <div class="add-account">
                <VfButton @click="$router.push(url.account_add)">
                    <template #icon>
                        <PlusIcon :size="20" />
                        {{ $t('mail', 'Add account') }}
                    </template>
                </VfButton>
            </div>
        </div>
    </div>
</template>

<script>
    import { ACCOUNT_ADD } from '../js/router/consts'
    import EmailSearchIcon from 'vue-material-design-icons/EmailSearch.vue'
    import VfButton from '../../../../resources/components/elements/VfButton.vue'
    import PlusIcon from 'vue-material-design-icons/Plus.vue'

    export default {
        name: 'NoAccounts',
        components: {
            VfButton,
            EmailSearchIcon,
            PlusIcon
        },
        data() {
            return {
                url: {
                    account_add: ACCOUNT_ADD
                }
            }
        },
    }
</script>

<style lang="scss" scoped>
.no-accounts {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  text-align: center;
  color: var(--color-light);

  .add-account {
	margin-top: 20px;
	display: flex;
	align-items: center;
	justify-content: center;
  }

  .icon {
	margin: 0 0 20px 0;
	color: var(--color-lighter)
  }
}
</style>