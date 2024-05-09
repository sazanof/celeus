<template>
    <teleport
        to="body">
        <transition
            name="fade"
            enter-active-class="animate__animated animate__fadeIn"
            leave-active-class="animate__animated animate__fadeOut">
            <div
                v-if="opened"
                ref="modal"
                class="modal"
                tabindex="0"
                @keydown.esc="close">
                <div class="modal-icon">
                    <VfButton
                        :only-icon="true"
                        type="transparent"
                        @click="close">
                        <template #icon>
                            <CloseIcon :size="34" />
                        </template>
                    </VfButton>
                </div>
                <div
                    class="modal-content"
                    :class="size">
                    <div
                        v-if="title"
                        class="modal-title">
                        {{ title }}
                    </div>
                    <div class="modal-inner">
                        <slot />
                    </div>
                    <div
                        v-if="showActions"
                        class="actions">
                        <slot name="actions" />
                    </div>
                </div>
                <div
                    class="modal-backdrop"
                    @click="close" />
            </div>
        </transition>
    </teleport>
</template>


<script>
    import VfButton from './VfButton.vue'
    import CloseIcon from 'vue-material-design-icons/Close.vue'

    export default {
        name: 'VfModal',
        components: {
            VfButton,
            CloseIcon
        },
        props: {
            title: {
                type: String,
                default: null
            },
            showActions: {
                type: Boolean,
                default: true
            },
            size: {
                type: String,
                default: 'medium'
            }
        },
        emits: [ 'on-close', 'on-open' ],
        data() {
            return {
                opened: false
            }
        },
        methods: {
            close() {
                this.opened = false
                this.$emit('on-close', this.opened)
            },
            open() {
                this.opened = true
                this.$emit('on-open', this.opened)
                this.$nextTick(() => {
                    this.$refs.modal.focus()
                })
            }
        }
    }
</script>

<style lang="scss" scoped>
.modal {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 999;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: center;

    .modal-icon {
        position: absolute;
        right: 30px;
        top: 18px;
        opacity: 0.7;
        transition: opacity var(--transition-duration);
        z-index: 10;

        &::v-deep(.material-design-icon) {
            color: var(--color-white);
        }

        &:hover {
            opacity: 1;
        }
    }

    .modal-backdrop {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 2;
        background: rgba(0, 0, 0, 0.5);
    }

    .modal-content {
        overflow-y: auto;
        position: relative;
        background: var(--color-white);
        padding: var(--padding-box);
        width: 100%;
        z-index: 5;
        border-radius: var(--border-radius);
        min-height: 100px;
        max-height: 90vh;

        .modal-title {
            font-size: var(--font-size-title);
            font-weight: bold;
            margin-bottom: 16px;
        }

        .actions {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 16px;

            &::v-deep(.btn) {
                margin: 0 10px;
            }
        }

        &.small {
            max-width: 450px;
        }

        &.medium {
            max-width: 670px;
        }

        &.big {
            max-width: 900px;
        }
    }
}
</style>
