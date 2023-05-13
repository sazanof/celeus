<template>
    <div class="user-info">
        <Popper :arrow="true">
            <img
                ref="userAvatar"
                class="user-avatar-header"
                :src="`/user/${user.username}/avatar?size=48`">
            <template #content>
                <VfList>
                    <VfListItem @click="openSettings">
                        <div class="fullname">
                            {{ fullName }}
                        </div>
                        <div class="small">
                            {{ $t('core', 'Enter profile') }}
                        </div>
                    </VfListItem>
                    <VfListItem @click="logOut">
                        <template #icon>
                            <LogoutIcon :size="20" />
                        </template>
                        {{ $t('core', 'Logout') }}
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

    export default {
        name: 'PageUserInfo',
        components: {
            LogoutIcon,
            VfList,
            VfListItem,
            Popper,
        },
        computed: {
            user() {
                return this.$store.getters.getUser
            },
            fullName() {
                return this.user !== null ? `${this.user.firstname} ${this.user.lastname}` : 'N/A'
            }
        },
        mounted() {
            Emitter.on('settings.avatar.change', blob => {
                this.$refs.userAvatar.src = blob
            })
        },
        methods: {
            openSettings() {
                document.location.replace('/app/settings')
            },
            async logOut() {
                await this.$store.dispatch('logOut')
                window.location.replace('/login')
            }
        }
    }
</script>

<style lang="scss" scoped>
.user-info {
  .user-avatar-header {
	cursor: pointer;
	border: 2px solid var(--color-white);
	border-radius: 50%;
	transition: var(--transition-duration);
	width: 48px;
	height: 48px;

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