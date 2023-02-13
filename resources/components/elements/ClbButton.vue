<template>
    <button class="btn">
        <slot />
    </button>
</template>

<script>
export default {
	name: 'ClbButton'
}
</script>

<style lang="scss" scoped>
	.btn {
		cursor: pointer;
		padding: 8px 12px;
		background: var(--color-lighter);
		color:var(--color-text);
		border:1px solid var(--color-light);
		border-radius: var(--border-radius);
		transition: var(--transition-duration-mini);
		
		&:disabled {
			opacity: 0.6;
			cursor: not-allowed;
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