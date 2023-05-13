<template>
    <VfModal ref="modal">
        <Cropper
            @ready="readyCropper"
            class="cropper"
            :src="blobSrc"
            :stencil-props="{
                aspectRatio: 1
            }"
            @change="changeCropper" />
        <template #actions>
            <VfButton
                :disabled="disabled"
                @click="apply">
                <CheckIcon :size="22" />
                {{ $t('settings', 'Save') }}
            </VfButton>
            <VfButton
                @click="cancelCropper"
                type="gray"
                :disabled="disabled">
                <CloseIcon :size="22" />
                {{ $t('settings', 'Cancel') }}
            </VfButton>
        </template>
    </VfModal>
</template>

<script>
    import CheckIcon from 'vue-material-design-icons/Check.vue'
    import CloseIcon from 'vue-material-design-icons/Close.vue'
    import VfButton from './VfButton.vue'
    import VfModal from './VfModal.vue'
    import { Cropper } from 'vue-advanced-cropper'

    export default {
        name: 'VfCropper',
        components: {
            Cropper,
            VfModal,
            VfButton,
            CheckIcon,
            CloseIcon
        },
        props: {
            file: {
                type: Blob,
                default: undefined
            }
        },
        data() {
            return {
                disabled: true,
                coordinates: null,
                canvas: null
            }
        },
        computed: {
            blobSrc() {
                return URL.createObjectURL(this.file)
            },
        },
        methods: {
            open() {
                this.$refs.modal.open()
                this.$emit('on-open')
            },
            close() {
                this.$refs.modal.close()
                this.$emit('on-close')
            },
            apply() {
                this.$emit('on-apply', { coordinates: this.coordinates, canvas: this.canvas })
                this.$refs.modal.close()
            },
            cancelCropper() {
                this.$refs.modal.close()
                this.$emit('on-cancel')
            },
            changeCropper({ coordinates, canvas }) {
                this.canvas = canvas
                this.coordinates = coordinates
            },
            readyCropper() {
                this.disabled = false
            }
        }
    }
</script>

<style scoped>

</style>