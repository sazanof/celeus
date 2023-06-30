<template>
    <div
        class="mailbox"
        :class="{active: activeMailbox?.id === id}"
        ref="mailbox">
        <div
            @mouseenter="showToggleArrows(true)"
            @mouseleave="showToggleArrows(false)"
            @click="$router.push(`/mbox/${this.mailbox.id}`)"
            class="mailbox-label"
            :style="`padding-left:${paddingLeft}px`">
            <div
                class="icon"
                @click.stop="toggleChildren"
                v-if="showArrows">
                <MenuUpIcon
                    :size="20"
                    v-if="showChildren" />
                <MenuDownIcon
                    :size="20"
                    v-else />
            </div>
            <div
                @click="toggleChildren"
                class="icon"
                v-else>
                <FolderIcon
                    v-if="!isSpecialUse"
                    :size="20" />
                <ArchiveIcon
                    v-else-if="isArchive"
                    :size="20" />
                <EmailFastIcon
                    v-else-if="isSent"
                    :size="20" />
                <TrashCanIcon
                    v-else-if="isTrash"
                    :size="20" />
                <ThumbDownIcon
                    v-else-if="isJunk"
                    :size="20" />
                <PencilIcon
                    v-else-if="isDrafts"
                    :size="20" />
            </div>
            <div class="name">
                {{ mailbox.name }}
                <div
                    class="unseen"
                    v-if="unseen > 0">
                    {{ unseen }}
                </div>
            </div>
        </div>
        <div
            v-if="Object.keys(mailbox.children).length > 0 && showChildren"
            class="children">
            <MailboxItem
                :level="level + 1"
                v-for="mbox in mailbox.children"
                :key="mbox.id"
                :mailbox="mbox" />
        </div>
    </div>
</template>

<script>
    import PencilIcon from 'vue-material-design-icons/Pencil.vue'
    import FolderIcon from 'vue-material-design-icons/Folder.vue'
    import ThumbDownIcon from 'vue-material-design-icons/ThumbDown.vue'
    import ArchiveIcon from 'vue-material-design-icons/Archive.vue'
    import EmailFastIcon from 'vue-material-design-icons/EmailFast.vue'
    import TrashCanIcon from 'vue-material-design-icons/TrashCan.vue'
    import MenuUpIcon from 'vue-material-design-icons/MenuUp.vue'
    import MenuDownIcon from 'vue-material-design-icons/MenuDown.vue'
    import specialAttributes from '../../js/specialAttributes.js'

    export default {
        name: 'MailboxItem',
        components: {
            FolderIcon,
            ThumbDownIcon,
            ArchiveIcon,
            EmailFastIcon,
            TrashCanIcon,
            PencilIcon,
            MenuUpIcon,
            MenuDownIcon
        },
        props: {
            mailbox: {
                type: Object,
                required: true
            },
            level: {
                type: Number,
                default: 1
            }
        },
        data() {
            return {
                showChildren: false,
                showArrows: false,

            }
        },
        computed: {
            id() {
                return this.mailbox.id
            },
            paddingLeft() {
                return this.level * 20
            },
            unseen() {
                return this.mailbox.unseen
            },
            hasChildren() {
                return this.mailbox.children.length > 0
            },
            isSpecialUse() {
                return this.isTrash || this.isJunk || this.isArchive || this.isSent || this.isDrafts
            },
            isTrash() {
                return this.isSpecial(specialAttributes.trash)
            },
            isDrafts() {
                return this.isSpecial(specialAttributes.drafts)
            },
            isInbox() {
                return this.isSpecial(specialAttributes.inbox) || this.mailbox.name === 'INBOX'
            },
            isArchive() {
                return this.isSpecial(specialAttributes.archive)
            },
            isJunk() {
                return this.isSpecial(specialAttributes.junk)
            },
            isSent() {
                return this.isSpecial(specialAttributes.sent)
            },
            openedMenuItems() {
                return localStorage.getItem('openedMenuItems').split(',') ?? []
            },
            isOpened() {
                return this.openedMenuItems.find(id => id === this.id.toString())
            },
            activeMailbox() {
                return this.$store.getters['getActiveMailbox']
            }
        },
        created() {
            this.showChildren = this.isOpened
        },
        methods: {
            isSpecial(key) {
                return Object.values(this.mailbox.attributes).indexOf(key) !== -1
            },
            showToggleArrows(val) {
                if (this.hasChildren) {
                    this.showArrows = val
                }
            },
            toggleChildren(e) {
                if (this.hasChildren) {
                    this.showChildren = !this.showChildren
                    if (this.showChildren) {
                        this.addOpenMenuItem()
                    } else {
                        this.removeOpenMenuItem()
                    }
                }
            },
            addOpenMenuItem() {
                const updated = this.openedMenuItems
                const _id = this.id.toString()
                if (updated.indexOf(_id) === -1) {
                    updated.push(_id)
                }
                localStorage.setItem('openedMenuItems', updated.join(','))
            },
            removeOpenMenuItem() {
                const updated = this.openedMenuItems.filter(id => {
                    return id !== this.id.toString()
                })
                localStorage.setItem('openedMenuItems', updated.join(','))
            },
        }
    }
</script>

<style lang="scss" scoped>
.mailbox {

  .mailbox-label {
	position: relative;
	display: flex;
	align-items: center;
	padding: 8px var(--padding-box);
	cursor: pointer;
	transition: var(--transition-duration);
	margin-right: -12px;

	&:hover {
	  background-color: var(--color-primary-opacity10);
	}

	.unseen {
	  font-size: var(--font-size-small);
	  padding: 4px 6px;
	  border-radius: var(--border-radius);
	  position: absolute;
	  top: 8px;
	  right: 14px;
	  font-weight: bold;
	  color: var(--color-primary-dark-opacity70);
	  background: var(--color-primary-opacity20);
	}

	.material-design-icon {
	  position: relative;
	  top: 2px;
	  color: var(--color-primary-dark);
	  opacity: 0.7;
	}

	.name {
	  margin-left: 6px;
	  text-overflow: ellipsis;
	  overflow: hidden;
	  white-space: nowrap;
	  padding-right: 20px;
	}
  }

  &.active {
	& > .mailbox-label {
	  background-color: var(--color-primary-opacity10);

	  &:hover {
		background-color: var(--color-primary-opacity20);
	  }
	}
  }
}
</style>
