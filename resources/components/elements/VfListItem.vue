<template>
    <div
        class="list-item"
        :class="{header:header}">
        <slot name="icon" />
        <div class="list-item-text">
            <slot />
        </div>
    </div>
</template>

<script>
    export default {
        name: 'VfListItem',
        props: {
            header: {
                type: Boolean,
                default: false
            }
        }
    }
</script>

<style lang="scss" scoped>
.list-item {
  display: flex;
  align-items: center;
  padding: 12px;
  cursor: pointer;

  &:hover {
	background: rgba(0, 0, 0, 0.1);
  }

  &.header {
	justify-content: center;
	font-weight: bold;
	background: var(--color-background-light);
	text-align: center;
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
