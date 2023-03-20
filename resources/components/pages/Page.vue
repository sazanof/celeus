<template>
    <div class="page">
        <div
            class="menu"
            v-if="menu !==null">
            <div
                class="menu-item"
                v-for="item in menu"
                :key="item.name">
                {{ item.name }}
                <component :is="item?.icon" />
            </div>
        </div>
        page
    </div>
</template>

<script>
    export default {
        name: 'Page',
        components: {},
        computed: {
            menu() {
                return this.$store.state.menu
            },
        },
    }
</script>

<style lang="scss" scoped>
.page {
  background: var(--color-white);
  border-radius: var(--border-radius-big);
  padding: var(--padding-box);
}
</style>