<template>
    <div
        class="input-block">
        <label
            class="label"
            v-if="label !== null">{{ label }}</label>
        <input
            class="input"
            v-model="val"
            :type="type"
            :disabled="disabled"
            @keyup="$emit('on-change', $event)">
    </div>
</template>

<script>
    export default {
        name: 'ClbInput',
        props: {
            value: {
                type: String,
                required: true,
            },
            label: {
                type: String,
                default: null
            },
            disabled: {
                type: Boolean,
                default: false
            },
            type: {
                type: String,
                default: 'text'
            }
        },
        data(){
            return {
                val: this.value
            }
        },
    }
</script>

<style lang="scss" scoped>
	.input-block {
		display:block;
		
		.input {
			padding: 8px 12px;
			border:var(--border-width) solid var(--border-color);
			border-radius: var(--border-radius-big);
			box-sizing: border-box;
			display: block;
			width: 100%;
			
			&:disabled {
				color: var(--color-light);
				cursor: not-allowed;
			}
		}
		
		.label {
			display: block;
			font-weight: bold;
			margin-bottom: 6px;
		}
	}
</style>