<template>
    <div class="vf-sidebar">
        <slot />
    </div>
</template>

<script>
    export default {
        name: 'VfSidebar',
    }
</script>

<style lang="scss" scoped>
.vf-sidebar {
  width: var(--vf-sidebar-width);
}
</style>