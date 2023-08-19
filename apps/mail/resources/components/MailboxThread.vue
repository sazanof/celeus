<template>
    <SimpleBar
        class="mailbox-thread"
        ref="scroll">
        <div
            class="inner"
            ref="inner">
            <Message
                :data-index="index"
                ref="messages"
                v-for="(message, index) in messages"
                :key="message.id"
                :message="message" />
            <div
                v-if="scrollFreeze"
                class="loading"
                ref="loading">
                <VfLoader :size="34" />
            </div>
        </div>
    </SimpleBar>
</template>

<script>
    import VfLoader from '../../../../resources/components/elements/VfLoader.vue'
    import SimpleBar from 'simplebar-vue'
    import Message from './Message.vue'

    export default {
        name: 'MailboxThread',
        components: {
            Message,
            SimpleBar,
            VfLoader
        },
        data() {
            return {
                page: 1,
                loadingMessages: false,
                loadingSync: false
            }
        },
        computed: {
            id() {
                return parseInt(this.$route.params.id)
            },
            limit() {
                return this.$store.state.limit
            },
            activeMailbox() {
                return this.$store.getters['getActiveMailbox']
            },
            mailbox() {
                return this.$store.getters.getMailbox(this.id)
            },
            messages() {
                return this.$store.getters['getMessages']
            },
            scrollFreeze() {
                return this.loadingSync || this.loadingMessages
            }
        },

        watch: {
            async id() {
                this.$store.commit('setActiveMailbox', this.mailbox)
                console.log(`trigger sync mailbox ${this.mailbox.id}`)
                this.page = 1
                //TODO вызывать сначала сообщения из бд, а потом синхронизацию и после син-ии добавлять в списк сообщения
                this.$store.commit('clearMessages')
                await this.syncMessages()
                await this.getMessages()
                this.page++
            }
        },
        async mounted() {
            //TODO trigger account sync
            await this.syncMessages()
            this.$store.commit('setActiveMailbox', this.mailbox)
            this.$refs.scroll.scrollElement.addEventListener('scroll', async () => {
                if (this.scrollFreeze) return false
                if (this.$refs.scroll.scrollElement.querySelector('.message:last-child').getBoundingClientRect().bottom === window.innerHeight) {
                    this.page++
                    //TODO вызывать сначала сообщения из бд, а потом синхронизацию и после син-ии добавлять в списк сообщения
                    await this.syncMessages()
                    await this.getMessages()
                }
            }, { passive: true })
        //TODO ставить задачу по синхронизации и передавать msgUIDS
        },
        methods: {
            async getMessages() {
                if (this.scrollFreeze) return false
                this.loadingMessages = true
                await this.$store.dispatch('getMessages', {
                    id: this.id,
                    page: this.page,
                    limit: this.limit
                }).catch(() => {
                    this.page--
                })
                if (this.messages.length < this.page * this.limit) {
                    this.page--
                }
                this.loadingMessages = false
            },
            async syncMessages() {
                if (this.scrollFreeze) return false
                this.loadingSync = true
                await this.$store.dispatch('syncMessages', {
                    id: this.id,
                    page: this.page,
                    limit: this.limit
                }).finally(() => {
                    this.loadingSync = false
                })
            }
        }
    }
</script>

<style scoped lang="scss">
.mailbox-thread {
  height: calc(100vh - var(--search-height) - var(--header-height));

  .inner {
	height: inherit;
  }

  .loading {
	padding: 10px;
	background: var(--color-primary-opacity10);
	margin-top: 6px;
	color: var(--color-primary-opacity20);
	text-align: center;
	width: 100%;
  }
}
</style>
