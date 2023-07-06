<template>
    <div
        class="account"
        :class="{active: active}">
        <div class="account-label">
            <div
                class="dot"
                :style="`background-color: ${color}`" />
            <div class="name">
                {{ account.email }}
            </div>
            <div class="actions">
                <div class="cog">
                    <RefreshIcon :size="20" />
                </div>
                <Dropdown>
                    <div class="more">
                        <DotsHorizontalIcon :size="20" />
                    </div>
                    <template #popper>
                        <VfList>
                            <VfListItem :header="true">
                                {{ this.account.email }}
                            </VfListItem>
                            <VfListItem>
                                {{ $t('mail', 'Account settings') }}
                                <template #icon>
                                    <CogIcon :size="20" />
                                </template>
                            </VfListItem>
                        </VfList>
                    </template>
                </Dropdown>
            </div>
        </div>
        <Mailboxes :account="account" />
    </div>
</template>

<script>
    import { Dropdown } from 'floating-vue'
    import VfList from '../../../../../resources/components/elements/VfList.vue'
    import VfListItem from '../../../../../resources/components/elements/VfListItem.vue'
    import Mailboxes from './Mailboxes.vue'
    import CogIcon from 'vue-material-design-icons/Cog.vue'
    import RefreshIcon from 'vue-material-design-icons/Refresh.vue'
    import DotsHorizontalIcon from 'vue-material-design-icons/DotsHorizontal.vue'

    export default {
        name: 'AccountItem',
        components: {
            RefreshIcon,
            DotsHorizontalIcon,
            Mailboxes,
            VfListItem,
            VfList,
            Dropdown,
            CogIcon
        },
        props: {
            account: {
                type: Object,
                required: true
            },
            active: {
                type: Boolean,
                default: false
            }
        },
        computed: {
            color() {
                const rgb = [
                    Math.floor(Math.random() * 255),
                    Math.floor(Math.random() * 255),
                    Math.floor(Math.random() * 255)
                ]
                return `rgb(${rgb.join(', ')})`
            },
        }
    }
</script>

<style lang="scss" scoped>
.account {
  padding-right: 12px;

  .account-label {
	display: flex;
	align-items: center;
	justify-content: flex-start;
	padding: 12px 24px;
	background: var(--color-primary-opacity20);
	position: relative;
	margin-right: -12px;

	.dot {
	  width: 12px;
	  height: 12px;
	  border-radius: 50%;
	  display: inline-block;
	  background: var(--color-background-light);
	  margin-right: 10px;
	}

	.actions {
	  display: flex;
	  position: absolute;
	  right: 14px;
	  top: 12px;
	  transition: var(--transition-duration);

	  & > div {
		margin-left: 4px;
		cursor: pointer;
		transition: var(--transition-duration);

		&:hover {
		}
	  }
	}
  }
}
</style>
