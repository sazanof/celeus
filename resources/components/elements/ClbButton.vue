<template>
    <button
        class="btn"
        :class="`btn-${type}`">
        <slot />
    </button>
</template>

<script>
    export default {
        name: 'ClbButton',
        props: {
            type: {
                type: String,
                default: 'primary'
            }
        }
    }
</script>

<style lang="scss" scoped>
	.btn {
    display: flex;
    align-items: center;
		cursor: pointer;
		padding: 8px 12px;
		background: var(--color-lighter);
		color:var(--color-text);
		border:var(--border-width) solid var(--color-light);
		border-radius: var(--border-radius-big);
		transition: var(--transition-duration-mini);

    &.centered {
      justify-content: center;
    }

    ::v-deep(.material-design-icon) {
      margin-right: 6px;
      position: relative;
      top:2px;
    }

		&:disabled {
			opacity: 0.6;
			cursor: not-allowed;
		}
	}
  .btn-primary {
    background: var(--color-primary);
    color: var(--color-white);
    border-color: var(--color-primary);

    &:hover {
      background-color: var(--color-primary-dark);
      border-color: var(--color-primary-dark)
    }
  }

  .btn-secondary {
    background: var(--color-secondary);
    color: var(--color-white);
    border-color: var(--color-secondary);

    &:hover {
      background-color: var(--color-secondary-dark);
      border-color: var(--color-secondary-dark)
    }
  }

	.btn-green {
		background: var(--color-green);
		color: var(--color-white);
		border-color: var(--color-green);

		&:hover {
			background-color: var(--color-green-hover);
			border-color: var(--color-green-hover)
		}
	}
	
	.btn-orange {
		background: var(--color-orange);
		color: var(--color-white);
		border-color: var(--color-orange);

		&:hover {
			background-color: var(--color-orange-hover);
			border-color: var(--color-orange-hover)
		}
	}
	
	.btn-red {
		background: var(--color-red);
		color: var(--color-white);
		border-color: var(--color-red);

		&:hover {
			background-color: var(--color-red-hover);
			border-color: var(--color-red-hover)
		}
	}
</style>