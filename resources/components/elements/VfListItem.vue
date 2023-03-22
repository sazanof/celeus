<template>
    <div class="list-item">
        <slot name="icon" />
        <div class="list-item-text">
            <slot />
        </div>
    </div>
</template>

<script>
    export default {
        name: 'VfListItem'
    }
</script>

<style lang="scss" scoped>
.list-item {
  display: flex;
  align-items: center;
  padding: 12px;
  cursor: pointer;
  border-radius: var(--border-radius);

  &:hover {
	background: rgba(0, 0, 0, 0.1);
  }

  ::v-deep(.material-design-icon) {
	margin: 0;
	display: flex;
	align-items: center;
  }

  .list-item-text {
	margin-left: 4px;
  }
}
</style>