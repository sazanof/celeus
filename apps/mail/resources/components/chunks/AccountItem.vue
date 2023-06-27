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
                    <CogIcon :size="20" />
                </div>
                <div class="more">
                    <DotsHorizontalIcon :size="20" />
                </div>
            </div>
        </div>
        <Mailboxes :account="account" />
    </div>
</template>

<script>
    import Mailboxes from './Mailboxes.vue'
    import CogIcon from 'vue-material-design-icons/Cog.vue'
    import DotsHorizontalIcon from 'vue-material-design-icons/DotsHorizontal.vue'

    export default {
        name: 'AccountItem',
        components: {
            CogIcon,
            DotsHorizontalIcon,
            Mailboxes
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
            }
        }
    }
</script>

<style lang="scss" scoped>
.account {
  .account-label {
	display: flex;
	align-items: center;
	justify-content: flex-start;
	padding: 12px 16px;
	background: var(--color-primary-opacity20);
	position: relative;

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
	  right: 6px;
	  top: 12px;

	  & > div {
		margin-left: 4px;
		cursor: pointer;
		opacity: 0.5;
		transition: var(--transition-duration);

		&:hover {
		  opacity: 1;
		}
	  }
	}
  }
}
</style>
