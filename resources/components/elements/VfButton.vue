<template>
    <button
        class="btn"
        :disabled="disabled"
        :class="[`btn-${type}`, onlyIcon ? 'icon' : '']">
        <slot name="icon" />
        <slot />
    </button>
</template>

<script>
    export default {
        name: 'VfButton',
        props: {
            type: {
                type: String,
                default: 'primary'
            },
            disabled: {
                type: Boolean,
                default: false
            },
            onlyIcon: {
                type: Boolean,
                default: false
            }
        }
    }
</script>

<style lang="scss" scoped>
.btn {
    display: inline-flex;
    align-items: center;
    cursor: pointer;
    padding: 8px 12px;
    background: var(--color-lighter);
    color: var(--color-text);
    border: var(--border-width) solid var(--color-light);
    border-radius: var(--border-radius);
    transition: var(--transition-duration-mini);

    &.centered {
        justify-content: center;
    }

    &.full {
        width: 100%
    }

    ::v-deep(.material-design-icon) {
        margin-right: 6px;
        position: relative;
        top: 2px;
    }

    &.icon {
        padding: 8px;
        width: 41px;
        height: 41px;
        align-items: center;
        justify-content: center;

        ::v-deep(.material-design-icon) {
            margin-right: 0;
        }
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

.btn-primary-dark {
    background: var(--color-primary-dark);
    color: var(--color-white);
    border-color: var(--color-primary-dark);

    &:hover {
        background-color: var(--color-primary-darker);
        border-color: var(--color-primary-darker)
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

.btn-gray {
    background: var(--color-background-light);
    color: var(--color-text-light);
    border-color: var(--color-background-light);

    &:hover {
        background-color: var(--color-background);
        border-color: var(--color-background)
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

.btn-transparent {
    background: none;
    border-color: transparent;
}
</style>
