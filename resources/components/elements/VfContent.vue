<template>
    <div class="vf-content">
        <slot />
    </div>
</template>

<script>
    export default {
        name: 'VfContent'
    }
</script>

<style lang="scss" scoped>
.vf-content {
  width: calc(100% - var(--vf-sidebar-width));
}
</style>