<template>
    <div class="upload">
        <input
            :accept="accept"
            class="hidden"
            ref="input"
            type="file"
            @change="inputFileChange"
            :multiple="multiple">
        <slot
            name="trigger">
            <button @click="triggerInput">
                Click me
            </button>
        </slot>
    </div>
</template>

<script>
    export default {
        name: 'VfFileUpload',
        props: {
            accept: {
                type: String,
                default: '*'
            },
            multiple: {
                type: Boolean,
                default: false
            }
        },
        data() {
            return {
                files: null,
            }
        },
        methods: {
            triggerInput() {
                this.$refs.input.click()
            },
            inputFileChange() {
                this.files = this.$refs.input.files
                this.$emit('input-file-changed', this.files)
            }
        }
    }
</script>

<style lang="scss" scoped>
.hidden {
  display: none
}
</style>