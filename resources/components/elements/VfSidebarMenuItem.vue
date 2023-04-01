<template>
    <div class="sidebar-menu-item">
        <div class="smi-icon">
            <slot name="button" />
        </div>
        <div
            class="smi-text"
            v-if="to">
            <router-link :to="to">
                <slot />
            </router-link>
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
  border-radius: var(--border-radius-big);
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

  &:hover {
	background: var(--color-lighter);
  }
}
</style>