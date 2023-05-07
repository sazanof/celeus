<template>
    <div
        class="textarea"
        v-if="mode === 'text'">
        <label v-if="label">{{ label }}</label>
        <textarea
            @input="$emit('update:text', text)"
            v-model="text" />
    </div>
    <div
        v-else-if="mode === 'richtext'"
        class="textarea ck-textarea">
        <ckeditor
            :editor="editor"
            @input="$emit('update:text', text)"
            v-model="text" />
    </div>
</template>

<script>
    import CKEditor from '@ckeditor/ckeditor5-vue'
    import ClassicEditor from '@ckeditor/ckeditor5-build-classic'
    import '../../css/ckeditor-variables.scss'

    export default {
        name: 'VfTextarea',
        components: {
            ckeditor: CKEditor.component
        },
        props: {
            mode: {
                type: String,
                default: 'text'
            },
            label: {
                type: String,
                default: null
            },
            value: {
                type: String,
                default: ''
            },
        },
        data() {
            return {
                text: this.value,
                editor: ClassicEditor,
            }
        }
    }
</script>

<style lang="scss">

.textarea {
  border: var(--border-width) solid var(--border-color);
  border-radius: 22px;

  &.ck-textarea {
	.ck.ck-editor__editable {
	  min-height: 200px;
	}
  }

  label {
	display: block;
	font-weight: bold;
	margin-bottom: 6px;
  }

  textarea {
	width: 100%;
	box-sizing: border-box;
	border-width: var(--border-width);
	border-color: var(--border-color);
	resize: vertical;
	border-radius: 22px;
	min-height: 100px;
  }
}
</style>