<template>
    <div class="list">
        <slot />
    </div>
</template>


<script>
    export default {
        name: 'VfList'
    }
</script>

<style lang="scss" scoped>
.list {
  max-width: 400px;
  border-radius: var(--border-radius);
}
</style>
