<template>
    <div
        class="message"
        :class="{'has-preview': preview, 'is-new': !seen}">
        <div
            class="unread"
            v-if="!seen">
            <CircleIcon
                :size="12" />
        </div>

        <div class="pic">
            <VfAvatar
                :size="48"
                :fullname="from.name ? from.name : from.address" />
        </div>
        <div class="data">
            <div class="actions">
                <div
                    class="important"
                    :class="{'is-important': important}">
                    <FlagIcon
                        :size="20" />
                </div>
            </div>
            <div class="date">
                {{ date }}
            </div>
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
    import FlagIcon from 'vue-material-design-icons/Flag.vue'
    import CircleIcon from 'vue-material-design-icons/Circle.vue'
    import VfAvatar from '../../../../resources/components/elements/VfAvatar.vue'

    export default {
        name: 'Message',
        components: {
            VfAvatar,
            FlagIcon,
            CircleIcon
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
            },
            date() {
                return this.$moment(this.message.sentAt.date).locale(this.$locale).fromNow()
            },
            seen() {
                return this.message.seen
            },
            important() {
                return this.message.important
            },
        }
    }
</script>

<style scoped lang="scss">
.message {
  overflow: hidden;
  padding: 8px 8px 8px 22px;
  display: flex;
  align-items: center;
  flex-wrap: wrap;
  transition: background-color var(--transition-duration);
  cursor: pointer;
  position: relative;
  width: calc(100% - 12px);

  &.has-preview {
	align-items: flex-start;
  }

  .unread {
	position: absolute;
	left: 5px;
	top: 24px;
	z-index: 2;
	transition: var(--transition-duration);
	color: var(--color-primary);
  }

  .pic {
	width: 58px;
  }

  .actions {
	top: 32px;
	right: 9px;
	position: absolute;
	z-index: 2;

	.important {
	  opacity: 0;
	  color: var(--color-primary-opacity20);

	  &.is-important {
		opacity: 1;
		color: var(--color-red)
	  }
	}
  }

  .data {
	padding-left: 6px;
	width: calc(100% - 58px);

	.date {
	  position: absolute;
	  right: 10px;
	  top: 10px;
	  font-size: var(--font-size-small);
	  color: var(--color-text-light)
	}

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

  &.is-new {
	.subject {
	}
  }

  &:hover {
	background: var(--color-primary-opacity10);

	.important {
	  opacity: 1;
	}

	.subject {
	  width: calc(100% - 64px);
	}
  }
}
</style>
