<template>
    <div
        class="message"
        :class="{'has-preview': preview}">
        <div class="pic">
            <VfAvatar
                :size="64"
                :fullname="from.name" />
        </div>
        <div class="data">
            <div class="name">
                {{ from.name ? from.name : from.address }}
            </div>
            <div class="subject">
                {{ subject }}
            </div>
            <div
                class="preview"
                v-if="preview">
                {{ preview }}
            </div>
        </div>
    </div>
</template>

<script>
    import VfAvatar from '../../../../resources/components/elements/VfAvatar.vue'

    export default {
        name: 'Message',
        components: {
            VfAvatar
        },
        props: {
            message: {
                type: Object,
                required: true
            }
        },
        computed: {
            from() {
                return this.message.from
            },
            subject() {
                return this.message.subject
            },
            preview() {
                return this.message.preview
            }
        }
    }
</script>

<style scoped lang="scss">
.message {
  overflow: hidden;
  padding: 16px;
  display: flex;
  align-items: center;
  flex-wrap: wrap;
  transition: background-color var(--transition-duration);
  cursor: pointer;

  &.has-preview {
	align-items: flex-start;
  }

  &:hover {
	background: var(--color-primary-opacity10);
  }

  .pic {
	width: 64px;
  }

  .data {
	margin-left: 10px;
	width: calc(100% - 74px);

	.name {
	  font-weight: bold;
	}

	.subject {
	  margin: 6px 0;
	  overflow: hidden;
	  white-space: nowrap;
	  text-overflow: ellipsis;
	}

	.preview {
	  font-size: var(--font-size-small);
	  color: var(--color-text-light);
	  overflow: hidden;
	  white-space: nowrap;
	  text-overflow: ellipsis;
	}
  }
}
</style>
