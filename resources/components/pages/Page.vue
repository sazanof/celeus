<template>
    <div class="page">
        <div class="page-header">
            <Logo />
            <div class="page-helper">
                helper
            </div>
            <div class="user-info">
                user-info
            </div>
        </div>

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
    import Logo from '../chunks/Logo.vue'
    import PageMenu from '../chunks/PageMenu.vue'

    export default {
        name: 'Page',
        components: {
            Logo,
            PageMenu
        },
    }
</script>

<style lang="scss" scoped>
.page {
  padding: 0 var(--padding-box) 0 0;

  .page-header {
    height: var(--header-height);
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
  }

  .page-wrapper {
    display: flex;
    flex-wrap: wrap
  }

  .page-sidebar {
    width: var(--sidebar-width);
    height: calc(100vh - var(--header-height));
  }

  .page-content {
    width: calc(100% - var(--sidebar-width));
    background: var(--color-white);
    border-radius: var(--border-radius-big) var(--border-radius-big) 0 0;
    padding: var(--padding-box);
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.4);
  }
}
</style>