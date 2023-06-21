<template>
    <div class="loading-icon">
        <LoadingIcon
            :size="size" />
    </div>
</template>

<script>
    import LoadingIcon from 'vue-material-design-icons/Loading.vue'

    export default {
        name: 'VfLoader',
        components: {
            LoadingIcon
        },
        props: {
            size: {
                type: Number,
                default: 20
            }
        }
    }
</script>

<style scoped>
.loading-icon {
	display: inline;
	animation-name: spin;
	animation-duration: 1.5s;
	animation-iteration-count: infinite;
	animation-timing-function: linear;

}

@keyframes spin {
	from {
		transform: rotate(0deg);
	}
	to {
		transform: rotate(360deg);
	}
}
</style>