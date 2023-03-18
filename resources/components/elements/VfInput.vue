<template>
    <div
        class="input-block"
        :class="{'with-icon': icon}">
        <label
            class="label"
            v-if="label !== null">{{ label }}</label>
        <div class="input-inner">
            <slot
                name="icon"
                v-if="icon" />
            <input
                class="input"
                v-model="val"
                :type="type"
                :disabled="disabled"
                :placeholder="placeholder"
                @keyup="$emit('on-change', $event)">
        </div>
    </div>
</template>

<script>
    export default {
        name: 'VfInput',
        props: {
            icon: {
                type: Boolean,
                default: true
            },
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
            },
            placeholder: {
                type: String,
                default: ''
            }
        },
        data() {
            return {
                val: this.value
            }
        },
    }
</script>

<style lang="scss" scoped>
::v-deep(.material-design-icon) {
  position: absolute;
  top: 8px;
  left: 10px;

  svg {
    opacity: 0.7
  }
}

.input-block {
  display: block;


  .input-inner {
    position: relative;
    z-index: 2;
  }

  .input {
    padding: 8px 12px;
    border: var(--border-width) solid var(--border-color);
    border-radius: var(--border-radius-big);
    box-sizing: border-box;
    display: block;
    width: 100%;

    &:disabled {
      color: var(--color-light);
      cursor: not-allowed;
    }
  }

  &.with-icon {
    .input {
      padding-left: 32px;

    }
  }

  .label {
    display: block;
    font-weight: bold;
    margin-bottom: 6px;
  }
}
</style>