<template>
    <div
        class="menu"
        v-if="menu !==null">
        <div
            class="menu-item"
            v-for="item in menu"
            :key="item.name">
            <router-link :to="item.key">
                <component
                    :is="item?.icon"
                    :size="32" />
                <div class="menu-tooltip">
                    {{ $t(item.name) }}
                </div>
            </router-link>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'PageMenu',
        computed: {
            menu() {
                return this.$store.state.menu
            },
        },
    }
</script>

<style lang="scss" scoped>
.menu {
  display: flex;
  align-items: center;
  flex-direction: column;
  position: relative;
  margin-top: 30px;

  .menu-item {
    position: relative;

    a {
      box-sizing: border-box;
      padding: 18px 14px;
      width: var(--sidebar-width);
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--color-white);
      text-decoration: none;
      transition: var(--transition-duration);
      position: relative;

      .menu-tooltip {
        opacity: 0;
        z-index: 2;
        font-size: 12px;
        color: var(--color-white);
        text-align: center;
        position: absolute;
        bottom: 5px;
        left: 0;
        right: 0;
        transition: var(--transition-duration);
      }

      &:hover .menu-tooltip {
        z-index: 2;
        opacity: 1;
      }

      &:hover {
        background: var(--color-primary-dark);
      }

      &.router-link-active:before, &.router-link-exact-active:before {
        content: "";
        width: 10px;
        height: 10px;
        background: var(--color-white);
        position: absolute;
        left: 12px;
        bottom: calc(50% - 4px);
        border-radius: 50%;
      }
    }
  }
}
</style>