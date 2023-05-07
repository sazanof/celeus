<template>
    <div
        @click="changeValue($event)"
        class="checkbox"
        :class="{checked: checked}">
        <input
            :type="type"
            :checked="checked">
        <label />
        <span
            v-if="label !== null"
            class="label">{{ label }}</span>
    </div>
</template>

<script>
    export default {
        name: 'VfRadioCheck',
        props: {
            type: {
                type: String,
                default: 'checkbox'
            },
            label: {
                type: String,
                default: null
            },
            checked: {
                type: Boolean
            }
        },
        emits: [ 'update:checked' ],
        methods: {
            changeValue(checked) {
                this.$emit('update:checked', checked)
            }
        }
    }
</script>

<style lang="scss" scoped>
.checkbox {
  cursor: pointer;
  display: flex;
  align-items: center;

  .label {
	margin-left: 1px;
  }
}

input[type="checkbox"]:checked,
input[type="checkbox"]:not(:checked),
input[type="radio"]:checked,
input[type="radio"]:not(:checked) {
  position: absolute;
  left: -9999px;
}

input[type="checkbox"]:checked + label,
input[type="checkbox"]:not(:checked) + label,
input[type="radio"]:checked + label,
input[type="radio"]:not(:checked) + label {
  display: inline-block;
  position: relative;
  padding-left: 28px;
  line-height: 20px;
  cursor: pointer;
}

input[type="checkbox"]:checked + label:before,
input[type="checkbox"]:not(:checked) + label:before,
input[type="radio"]:checked + label:before,
input[type="radio"]:not(:checked) + label:before {
  content: "";
  position: absolute;
  left: 0;
  top: -9px;
  width: 18px;
  height: 18px;
  border: 1px solid #dddddd;
  background-color: #ffffff;
}

input[type="checkbox"]:checked + label:before {
  background: var(--color-primary);
  border-color: var(--color-primary);
}


input[type="checkbox"]:checked + label:before,
input[type="checkbox"]:not(:checked) + label:before {
  border-radius: 2px;
}

input[type="radio"]:checked + label:before,
input[type="radio"]:not(:checked) + label:before {
  border-radius: 100%;
}

input[type="checkbox"]:checked + label:after,
input[type="checkbox"]:not(:checked) + label:after,
input[type="radio"]:checked + label:after,
input[type="radio"]:not(:checked) + label:after {
  content: "";
  position: absolute;
  -webkit-transition: all 0.2s ease;
  -moz-transition: all 0.2s ease;
  -o-transition: all 0.2s ease;
  transition: all 0.2s ease;
}

input[type="checkbox"]:checked + label:after,
input[type="checkbox"]:not(:checked) + label:after {
  left: 3px;
  top: -5px;
  width: 10px;
  height: 5px;
  border-radius: 1px;
  border-left: 4px solid var(--color-white);
  border-bottom: 4px solid var(--color-white);
  -webkit-transform: rotate(-45deg);
  -moz-transform: rotate(-45deg);
  -o-transform: rotate(-45deg);
  -ms-transform: rotate(-45deg);
  transform: rotate(-45deg);
}

input[type="radio"]:checked + label:after,
input[type="radio"]:not(:checked) + label:after {
  left: 5px;
  top: 5px;
  width: 10px;
  height: 10px;
  border-radius: 100%;
  background-color: var(--color-primary);
}

input[type="checkbox"]:not(:checked) + label:after,
input[type="radio"]:not(:checked) + label:after {
  opacity: 0;
}

input[type="checkbox"]:checked + label:after,
input[type="radio"]:checked + label:after {
  opacity: 1;
}
</style>