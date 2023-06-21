<template>
    <div
        class="add-account"
        v-if="!success">
        <div
            class="add-account-form">
            <div class="imap-settings">
                <VfSection :title="$t('mail', 'IMAP settings')">
                    <template #icon>
                        <ConnectionIcon :size="42" />
                    </template>
                    <div class="form-group">
                        <VfInput
                            :value="imap.host"
                            :label="$t('mail','IMAP host')"
                            @on-change="imap.host = $event.target.value" />
                    </div>
                    <div class="form-group">
                        <label class="checkboxes-label">{{ $t('mail', 'Encryption') }}</label>
                        <div class="checkboxes">
                            <VfRadioCheck
                                type="radio"
                                :label="$t('mail', 'None')"
                                @click="setEncryption('imap', 'none')"
                                :checked="imap.encryption === 'none'" />
                            <VfRadioCheck
                                type="radio"
                                :label="$t('mail', 'SSL')"
                                @click="setEncryption('imap', 'ssl')"
                                :checked="imap.encryption === 'ssl'" />
                            <VfRadioCheck
                                type="radio"
                                :label="$t('mail', 'TLS')"
                                @click="setEncryption('imap', 'tls')"
                                :checked="imap.encryption === 'tls'" />
                        </div>
                    </div>
                    <div class="form-group">
                        <VfInput
                            :value="imap.port"
                            :label="$t('mail','IMAP port')"
                            @on-change="imap.port = $event.target.value" />
                    </div>
                    <div class="form-group">
                        <VfInput
                            :value="imap.user"
                            :label="$t('mail','IMAP user')"
                            @on-change="imap.user = $event.target.value" />
                    </div>
                    <div class="form-group">
                        <VfInput
                            type="password"
                            :value="imap.password"
                            :label="$t('mail','IMAP password')"
                            @on-change="imap.password = $event.target.value" />
                    </div>
                </VfSection>
            </div>
            <div class="smtp-settings">
                <VfSection :title="$t('mail', 'SMTP settings')">
                    <template #icon>
                        <EmailArrowRightIcon :size="42" />
                    </template>
                    <div class="form-group">
                        <VfInput
                            :value="smtp.host"
                            :label="$t('mail','SMTP host')"
                            @on-change="smtp.host = $event.target.value" />
                    </div>
                    <div class="form-group">
                        <label class="checkboxes-label">{{ $t('mail', 'Encryption') }}</label>
                        <div class="checkboxes">
                            <VfRadioCheck
                                type="radio"
                                :label="$t('mail', 'None')"
                                @click="setEncryption('smtp', 'none')"
                                :checked="smtp.encryption === 'none'" />
                            <VfRadioCheck
                                type="radio"
                                :label="$t('mail', 'SSL')"
                                @click="setEncryption('smtp', 'ssl')"
                                :checked="smtp.encryption === 'ssl'" />
                            <VfRadioCheck
                                type="radio"
                                :label="$t('mail', 'TLS')"
                                @click="setEncryption('smtp', 'tls')"
                                :checked="smtp.encryption === 'tls'" />
                        </div>
                    </div>
                    <div class="form-group">
                        <VfInput
                            :value="smtp.port"
                            :label="$t('mail','SMTP port')"
                            @on-change="smtp.port = $event.target.value" />
                    </div>
                    <div class="form-group">
                        <VfInput
                            :value="smtp.user"
                            :label="$t('mail','SMTP user')"
                            @on-change="smtp.user = $event.target.value" />
                    </div>
                    <div class="form-group">
                        <VfInput
                            type="password"
                            :value="smtp.password"
                            :label="$t('mail','SMTP password')"
                            @on-change="smtp.password = $event.target.value" />
                    </div>
                </VfSection>
            </div>
        </div>
        <VfButton
            :disabled="disabled || loading"
            @click="addAccount">
            <template #icon>
                <VfLoader v-if="loading" />
                <CheckIcon
                    :size="20"
                    v-else />
                {{ $t('mail', 'Connect') }}
            </template>
        </VfButton>
    </div>
    <div
        class="account-success"
        v-else>
        <div class="inner">
            <div class="title">
                <div class="icon">
                    <AccountCircleIcon :size="160" />
                </div>
                {{ $t('mail', 'Account successfully created') }}
            </div>
            <div class="form-group">
                <VfInput
                    :value="`${accountResponseData.name}`"
                    @on-change="accountResponseData.name = $event.target.value" />
            </div>
            <VfButton
                @click="saveAccount"
                :disabled="loading">
                <template #icon>
                    <VfLoader v-if="loading" />
                    <CheckIcon
                        :size="20"
                        v-else />
                </template>
                {{ $t('mail', 'Continue') }}
            </VfButton>
        </div>
    </div>
</template>

<script>
    import AccountCircleIcon from 'vue-material-design-icons/AccountCircle.vue'
    import { useToast } from 'vue-toastification'
    import CheckIcon from 'vue-material-design-icons/Check.vue'
    import ConnectionIcon from 'vue-material-design-icons/Connection.vue'
    import EmailArrowRightIcon from 'vue-material-design-icons/EmailArrowRight.vue'
    import VfInput from '../../../../resources/components/elements/VfInput.vue'
    import VfButton from '../../../../resources/components/elements/VfButton.vue'
    import VfSection from '../../../../resources/components/elements/VfSection.vue'
    import VfRadioCheck from '../../../../resources/components/elements/VfRadioCheck.vue'
    import VfLoader from '../../../../resources/components/elements/VfLoader.vue'

    const toast = useToast()

    export default {
        name: 'AddAccount',
        components: {
            ConnectionIcon,
            CheckIcon,
            EmailArrowRightIcon,
            AccountCircleIcon,
            VfButton,
            VfInput,
            VfSection,
            VfRadioCheck,
            VfLoader
        },
        data() {
            return {
                imap: {
                    host: '',
                    port: '143',
                    encryption: 'none',
                    user: '',
                    password: ''
                },
                smtp: {
                    host: '',
                    port: '25',
                    encryption: 'none',
                    user: '',
                    password: ''
                },
                loading: false,
                success: false,
                accountResponseData: {
                    id: 4,
                    user: 'admin',
                    email: 'sazanof.ru@yandex.ru',
                    name: 'Михаил Сазанов',
                }
            }
        },
        computed: {
            disabled() {
                return Object.values(this.imap).indexOf('') !== -1 || Object.values(this.smtp).indexOf('') !== -1
            }
        },
        methods: {
            setEncryption(type, val) {
                switch (type) {
                case 'imap':
                    switch (val) {
                    case 'none':
                        this.imap.port = '143'
                        this.imap.encryption = 'none'
                        break
                    case 'tls':
                        this.imap.port = '143'
                        this.imap.encryption = 'tls'
                        break
                    case 'ssl':
                        this.imap.port = '993'
                        this.imap.encryption = 'ssl'
                        break
                    }
                    break
                case 'smtp':
                    switch (val) {
                    case 'none':
                        this.smtp.port = '25'
                        this.smtp.encryption = 'none'
                        break
                    case 'tls':
                        this.smtp.port = '587'
                        this.smtp.encryption = 'tls'
                        break
                    case 'ssl':
                        this.smtp.port = '465'
                        this.smtp.encryption = 'ssl'
                        break
                    }
                    break
                }

            },
            async addAccount() {
                this.loading = true
                const res = await this.$store.dispatch('addAccount', {
                    imap: this.imap,
                    smtp: this.smtp
                }).catch(e => {
                    toast.error(this.$t('mail', 'Connection error'))
                })
                this.loading = false
                if (res.success && res.account) {
                    toast.success(this.$t('mail', 'Connection established'))
                    this.accountResponseData = res.account
                    this.success = true
                }
            },
            async saveAccount() {
                this.loading = true
                const res = await this.$store.dispatch('saveAccount', this.accountResponseData).catch(e => {
                    toast.error(this.$t('mail', 'Error saving account'))
                })
                if (res) {
                    toast.success(this.$t('mail', 'Account saved'))
                }
                this.loading = false
                this.$router.push('/')
            }
        }
    }
</script>

<style lang="scss" scoped>

.add-account {
  display: flex;
  flex-direction: column;
  align-items: center;

  .add-account-form {
	display: flex;
	flex-direction: column;
	justify-content: center;
	margin-bottom: 20px;

	& > div {
	  padding: var(--padding-box);

	  .title {
		font-weight: bold;
		min-width: 300px;
		padding: var(--padding-box);
		text-align: center;
	  }
	}
  }

  .checkboxes-label {
	font-weight: bold;
	margin: 6px 0 4px 0;
  }

  .checkboxes {
	display: flex;
	align-items: center;
	justify-content: space-between;
  }
}

.account-success {
  position: relative;
  height: calc(100vh - var(--header-height) - (var(--padding-box) / 2));
  display: flex;
  align-items: center;
  justify-content: center;

  .inner {
	width: 300px;
	height: 250px;
	text-align: center;

	.title {
	  font-weight: bold;
	  font-size: 20px;
	}

	.icon {
	  margin: 0 0 20px 0;
	  color: var(--color-lighter)
	}

	.form-group {
	  margin-top: 16px;

	  ::v-deep(input) {
		text-align: center;
	  }
	}
  }
}

</style>