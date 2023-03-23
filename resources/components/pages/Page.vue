<template>
    <div class="page">
        <div class="page-wrapper">
            <div class="page-sidebar">
                <PageMenu />
            </div>
            <div class="page-content">
                <router-view />
            </div>
        </div>
    </div>
</template>

<script>
    import PageUserInfo from '../chunks/PageUserInfo.vue'
    import PageCenterHelper from '../chunks/PageCenterHelper.vue'
    import Logo from '../chunks/Logo.vue'
    import PageMenu from '../chunks/PageMenu.vue'

    export default {
        name: 'Page',
        components: {
            PageMenu,
        },
    }
</script>

<style lang="scss" scoped>

</style>