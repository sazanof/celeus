<template>
    <div class="page-header">
        <Logo />
        <PageCenterHelper />
        <div class="user-info">
            <PageNotifications />
            <PageUserInfo />
        </div>
    </div>
</template>

<script>
    import PageNotifications from './PageNotifications.vue'
    import Logo from './Logo.vue'
    import PageCenterHelper from './PageCenterHelper.vue'
    import PageUserInfo from './PageUserInfo.vue'

    export default {
        name: 'PageHeader',
        components: {
            Logo,
            PageUserInfo,
            PageCenterHelper,
            PageNotifications
        },
        computed: {
            authenticated() {
                return this.$store.getters.isAuthenticated
            }
        },
        created() {
            setTimeout(() => {
                document.querySelector('.page').classList.add('loaded')
            }, 400)
        },
        async beforeMount() {
            this.$store.state.currentLocale = this.$i18n.locale
            await this.$store.dispatch('checkUserIsAuthenticated')
            setTimeout(() => {
                this.visible = true
            }, 200)
        }
    }
</script>

<style lang="scss" scoped>
.page-header {
  height: var(--header-height);
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 10px 0;

  .user-info {
	display: flex;
	align-items: center;
  }
}

</style>