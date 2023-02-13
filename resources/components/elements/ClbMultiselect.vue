<template>
    <Multiselect />
</template>

<script>
import Multiselect from '@vueform/multiselect'
export default {
	name: 'ClbMultiselect',
	components: {
		Multiselect
	}
}
</script>

<style lang="scss" scoped>
	.multiselect {
		border-color: var(--color-green-hover)
	}
</style>