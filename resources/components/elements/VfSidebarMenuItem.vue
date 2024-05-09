<template>
    <router-link
        class="sidebar-menu-item"
        :to="to"
        v-if="to">
        <div class="smi-icon">
            <slot name="button" />
        </div>
        <div
            class="smi-text"
            v-if="to">
            <slot />
        </div>
        <div
            class="smi-text"
            v-else>
            <slot />
        </div>
    </router-link>
    <div
        class="sidebar-menu-item"
        v-else>
        <div class="smi-icon">
            <slot name="button" />
        </div>
        <div
            class="smi-text"
            v-if="to">
            <slot />
        </div>
        <div
            class="smi-text"
            v-else>
            <slot />
        </div>
    </div>
</template>

<script>
    export default {
        name: 'VfSidebarMenuItem',
        props: {
            to: {
                type: String,
                default: null
            }
        }
    }
</script>

<style lang="scss" scoped>
.sidebar-menu-item {
  border-radius: var(--border-radius);
  padding: 10px;
  transition: var(--transition-duration);
  display: flex;
  align-items: center;
  cursor: pointer;
  margin-bottom: 6px;

  &:last-child {
    margin-bottom: 0;
  }

  .smi-icon {
    margin-right: 6px;
  }

  &:hover, &.router-link-active {
    background: var(--color-lighter);
  }
}

a.sidebar-menu-item {
  color: var(--color-text);
  text-decoration: none;
}
</style>
