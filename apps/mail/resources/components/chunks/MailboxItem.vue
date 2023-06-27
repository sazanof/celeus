<template>
    <div class="mailbox">
        <div class="mailbox-label">
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
            <div class="name">
                {{ mailbox.name }}
                <div class="unseen">
                    {{ unseen }}
                </div>
            </div>
        </div>
        <div
            v-if="mailbox.children.length > 0"
            class="children">
            <MailboxItem
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
    import specialAttributes from '../../js/specialAttributes.js'

    export default {
        name: 'MailboxItem',
        components: {
            FolderIcon,
            ThumbDownIcon,
            ArchiveIcon,
            EmailFastIcon,
            TrashCanIcon,
            PencilIcon
        },
        props: {
            mailbox: {
                type: Object,
                required: true
            }
        },
        data() {
            return {}
        },
        computed: {
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
            }
        },
        methods: {
            isSpecial(key) {
                return Object.values(this.mailbox.attributes).indexOf(key) !== -1
            }
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
	  top: 1px;
	  color: var(--color-primary-dark);
	  opacity: 0.7;
	}

	.name {
	  margin-left: 6px;
	}
  }

  .children {
	margin-left: 16px;
  }
}
</style>
