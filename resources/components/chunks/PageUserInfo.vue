<template>
    <div class="user-info">
        <Popper :arrow="true">
            <VfAvatar
                :fullname="fullName"
                size="48" />
            <template #content>
                <VfList>
                    <VfListItem @click="openSettings">
                        <div class="fullname">
                            {{ fullName }}
                        </div>
                        <div class="small">
                            {{ $t('Enter profile') }}
                        </div>
                    </VfListItem>
                    <VfListItem>
                        <template #icon>
                            <LogoutIcon :size="20" />
                        </template>
                        {{ $t('Logout') }}
                    </VfListItem>
                </VfList>
            </template>
        </Popper>
    </div>
</template>

<script>
    import LogoutIcon from 'vue-material-design-icons/Logout.vue'
    import VfList from '../elements/VfList.vue'
    import VfListItem from '../elements/VfListItem.vue'
    import Popper from 'vue3-popper'
    import VfAvatar from '../elements/VfAvatar.vue'

    export default {
        name: 'PageUserInfo',
        components: {
            LogoutIcon,
            VfList,
            VfListItem,
            Popper,
            VfAvatar
        },
        computed: {
            user() {
                return this.$store.getters.getUser
            },
            fullName() {
                return this.user !== null ? `${this.user.firstname} ${this.user.lastname}` : 'N/A'
            }
        },
        methods: {
            openSettings() {
                document.location.replace('/apps/settings')
            }
        }
    }
</script>

<style lang="scss" scoped>
.user-info {
  ::v-deep(.avatar) {
	cursor: pointer;
	border: 2px solid var(--color-white);
	transition: var(--transition-duration);

	&:hover {
	  box-shadow: var(--box-shadow);
	}
  }

  .fullname {
	font-weight: bold;
  }

  .small {
	margin-top: 4px;
	font-size: 12px;
  }
}
</style>