<template>
    <div class="install">
        <div class="install-inner">
            <div
                class="install-step"
                v-if="!start">
                <div class="logo">
                    CELEUS
                </div>
                <span class="description">
                    {{ $t('Your personal space for celeusoration') }}
                </span>
                <div class="switcher">
                    <LanguageSwitcher @on-locale-change="onLocaleChange" />
                    <ClbButton
                        @click="toStep(1)"
                        class="btn-green">
                        {{ $t('Begin install') }}
                    </ClbButton>
                </div>
            </div>
            <div
                v-else
                class="next-steps">
                <div class="logo">
                    COLLAB
                </div>
                <div class="progress-inner">
                    <ClbProgressbar :width="percents" />
                </div>
                <div class="next-steps-wrapper">
                    <div class="heading">
                        {{ heading }}
                    </div>
                    <div
                        class="install-step step"
                        v-if="step === 1">
                        <div class="extensions">
                            <div
                                class="extension"
                                v-for="extension in extensions"
                                :key="extension.extension">
                                <div class="name">
                                    {{ extension.extension }}
                                </div>
                                <div class="icon">
                                    <Check
                                        v-if="extension.loaded"
                                        fill-color="#2a9a68"
                                        :size="18" />
                                    <Close
                                        fill-color="#963417"
                                        v-else
                                        :size="18" />
                                </div>
                            </div>
                        </div>
                        <div class="actions">
                            <ClbButton @click="toStep(0)">
                                {{ $t('Back') }}
                            </ClbButton>
                            <ClbButton
                                class="btn-green"
                                v-if="!disabled"
                                @click="toStep(step +1)">
                                {{ $t('Next') }}
                            </ClbButton>
                            <ClbButton
                                class="btn-green"
                                v-else
                                @click="checkExtensions">
                                {{ $t('Retry') }}
                            </ClbButton>
                        </div>
                    </div>
                    <div
                        class="install-step step"
                        v-if="step ===2">
                        <div class="form-group">
                            <ClbInput
                                type="text"
                                :value="connection.driver"
                                :label="$t('Database type')"
                                :disabled="true" />
                        </div>
						
                        <div class="form-group">
                            <div class="host-port">
                                <div class="host">
                                    <ClbInput
                                        type="text"
                                        @on-change="e => connection.host = e.target.value"
                                        :value="connection.host"
                                        :label="$t('Database host')" />
                                </div>
                                <div class="port">
                                    <ClbInput
                                        type="text"
                                        @on-change="e => connection.port = e.target.value"
                                        :value="connection.port"
                                        :label="$t('Port')" />
                                </div>
                            </div>
                        </div>
						
                        <div class="form-group">
                            <ClbInput
                                type="text"
                                @on-change="e => connection.dbname = e.target.value"
                                :value="connection.dbname"
                                :label="$t('Database')" />
                        </div>
						
                        <div class="form-group">
                            <ClbInput
                                type="text"
                                @on-change="e => connection.user = e.target.value"
                                :value="connection.user"
                                :label="$t('Username')" />
                        </div>
                        <div class="form-group">
                            <ClbInput
                                type="password"
                                @on-change="e => connection.password = e.target.value"
                                :value="connection.password"
                                :label="$t('Password')" />
                        </div>
						
                        <div class="actions">
                            <ClbButton @click="toStep(step - 1)">
                                {{ $t('Back') }}
                            </ClbButton>
                            <ClbButton
                                v-if="connectionSuccess"
                                class="btn-green"
                                :disabled="disabled"
                                @click="toStep(step + 1)">
                                {{ $t('Next') }}
                            </ClbButton>
                            <ClbButton
                                v-else
                                class="btn-green"
                                :disabled="disabled"
                                @click="checkConnection">
                                {{ $t('Check') }}
                            </ClbButton>
                        </div>
                    </div>
					
                    <div
                        class="install-step step"
                        v-if="step ===3">
                        <div class="form-group">
                            <ClbInput
                                :label="$t('Username')"
                                :value="admin.username"
                                @on-change="e => admin.username = e.target.value" />
                        </div>
                        <div class="form-group">
                            <ClbInput
                                :label="$t('Email')"
                                :value="admin.email"
                                @on-change="e => admin.email = e.target.value" />
                        </div>
                        <div class="form-group">
                            <ClbInput
                                :label="$t('Firstname')"
                                :value="admin.firstname"
                                @on-change="e => admin.firstname = e.target.value" />
                        </div>
                        <div class="form-group">
                            <ClbInput
                                :label="$t('Lastname')"
                                :value="admin.lastname"
                                @on-change="e => admin.lastname = e.target.value" />
                        </div>
                        <div class="form-group">
                            <ClbInput
                                :label="$t('Password')"
                                :value="admin.password"
                                type="password"
                                @on-change="e => admin.password = e.target.value" />
                        </div>
                        <div class="form-group">
                            <ClbInput
                                :label="$t('Password confirmation')"
                                :value="admin.repeatPassword"
                                type="password"
                                @on-change="e => admin.repeatPassword = e.target.value" />
                        </div>
                        <div class="actions">
                            <ClbButton @click="toStep(step - 1)">
                                {{ $t('Back') }}
                            </ClbButton>
                            <ClbButton
                                class="btn-green"
                                :disabled="!adminDataCorrect"
                                @click="toStep(step + 1)">
                                {{ $t('Next') }}
                            </ClbButton>
                        </div>
                    </div>
                    <div
                        class="install-step step"
                        v-if="step === 4">
                        <div
                            class="install-info"
                            ref="installInfo">
                            {{ $t('The system is ready for installation. To continue, click on the Install button.') }}
                        </div>
                        <div class="actions">
                            <ClbButton @click="toStep(step - 1)">
                                {{ $t('Back') }}
                            </ClbButton>
                            <ClbButton
                                class="btn-green"
                                :disabled="disabled"
                                @click="install()">
                                {{ $t('Install') }}
                            </ClbButton>
                        </div>
                    </div>
                    <div
                        class="install-step step"
                        v-if="step === 5">
                        <div class="installResult">
                            <div
                                class="res"
                                v-for="(val, index) in installResult"
                                :key="index">
                                {{ $t(`Creating ${index}`) }}
                                <Check
                                    v-if="val"
                                    size="18" />
                                <Close
                                    v-else
                                    size="18" />
                            </div>
                        </div>
                        <div class="actions center">
                            <ClbButton
                                class="btn-green">
                                {{ $t('Let\'s get started') }}
                            </ClbButton>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import ClbProgressbar from '../elements/ClbProgressbar.vue'
import ClbButton from '../elements/ClbButton.vue'
import ClbInput from '../elements/ClbInput.vue'
import LanguageSwitcher from '../i18n/LanguageSwitcher.vue'
import Check from 'vue-material-design-icons/Check.vue'
import Close from 'vue-material-design-icons/Close.vue'
import { useToast } from 'vue-toastification'
import axios from 'axios'

const toast = useToast()

export default {
	name: 'Install',
	components: {
		ClbButton,
		ClbInput,
		ClbProgressbar,
		LanguageSwitcher,
		Check,
		Close
	},
	data() {
		return {
			heading: '',
			start: false,
			connectionSuccess: false,
			step: 0,
			percents: 0,
			totalSteps: 5,
			extensions: [],
			connection: {},
			admin: {},
			loading: false,
			installResult: [],
			installed: false
		}
	},
	computed: {
		disabled() {
			return this.errorExtensions.length > 0
		},
		errorExtensions() {
			return this.extensions.filter(e => {
				return !e.loaded
			})
		},
		adminDataCorrect() {
			const emptyValues = Object.values(this.admin).filter(value => value === '')
			return this.admin.password === this.admin.repeatPassword && emptyValues.length === 0
		},
		dataToServer() {
			let data = {}
			if (this.connectionSuccess) {
				data.connection = this.connection
			} else {
				delete data.connection
			}
			if (this.adminDataCorrect) {
				data.admin = this.admin
			} else {
				delete data.admin
			}
			data.locale = this.$i18n.locale
			return data
		}
	},
	
	watch: {
		step() {
			if(this.installed){
				this.toStep(5)
			}
			switch (this.step) {
				case 1:
					this.heading = this.$t('Checking PHP extensions')
					this.checkExtensions()
					break
				case 2:
					this.heading = this.$t('Database connection')
					break
				case 3:
					this.heading = this.$t('Creating an administrator')
					break
				case 4:
					this.heading = this.$t('Installation')
					break
				case 5:
					this.heading = this.$t('Installation complete')
					break
			}
			this.percents = Math.ceil(100 * (this.step - 1) / (this.totalSteps - 1))
		},
		percents() {
			if (this.percents < 0) {
				this.percents = 0
			} else if (this.percents > 100) {
				return 100
			}
		},
		connection: {
			handler() {
				this.connectionSuccess = false
			},
			deep: true,
		},
		admin: {
			handler(obj) {
				if (obj.repeatPassword !== !obj.password) {
				
				}
			},
			deep: true,
		}
	},
	created() {
		this.connection = {
			driver: 'mysqli',
			host: 'localhost',
			dbname: 'celeus',
			port: '3306',
			user: '',
			password: '',
			prefix: 'clb_'
		}
		this.admin = {
			username: 'admin',
			firstname: '',
			lastname: '',
			email: '',
			password: '',
			repeatPassword: ''
		}
	},
	
	methods: {
		onLocaleChange(locale) {
			console.log(`Locale changed to ${locale}`)
		},
		toStep(step) {
			if (step <= this.totalSteps && step > 0) {
				this.step = step
				this.start = true
			} else if (step === 0) {
				this.step = step
				this.start = false
			}
		},
		async checkExtensions() {
			const extensions = await axios.get(`/install/${this.step}`)
			this.extensions = extensions.data
		},
		async checkConnection() {
			const result = await axios.post(`/install/${this.step}`, this.connection, {
				headers: { 'Content-Type': 'multipart/form-data' },
			}).catch(e => {
					toast.error(e.response.data.message)
				})
			if (result !== undefined) {
				toast.success(this.$t('Connection established'))
				this.connectionSuccess = true
				this.step++
			}
		},
		async install() {
			this.loading = true
			const res = await axios.post(`install/${this.step}`, this.dataToServer, {
				headers: { 'Content-Type': 'multipart/form-data' },
			}).catch(e => {
				toast.error(e.response.data.message)
			})
			this.loading = false
			this.installResult = res.data
			this.toStep(this.step + 1)
			this.installed = true
		}
	}
}
</script>

<style lang="scss" scoped>
::v-deep(.multiselect-dropdown) {
	border-left: 1px solid var(--color-green);
	border-bottom: 1px solid var(--color-green);
	border-right: 1px solid var(--color-green);
}

.install {
	position: fixed;
	top: 0;
	left: 0;
	right: 0;
	bottom: 0;
	z-index: 2;
	background-color: var(--color-green-light);
	display: flex;
	flex-direction: column;
	align-items: center;
	justify-content: center;
	flex-wrap: wrap;
	
	.install-inner {
		width: 430px;
		
		.logo {
			color: var(--color-white);
			font-size: 54px;
			font-weight: bolder;
			text-align: center;
			margin-bottom: 16px;
		}
		
		.description {
			font-size: 14px;
			display: block;
			text-align: center;
			margin-bottom: 30px;
			margin-top: 14px;
			color: var(--color-white)
		}
		
		.switcher {
			max-width: 260px;
			margin: 0 auto;
			
			.btn {
				margin-top: 10px;
				width: 100%;
			}
		}
	}
	
	.next-steps {
		height: 90vh;
		position: relative;
		
		.progress-inner {
			position: relative;
			top: 14px;
			padding: 4px 6px;
			z-index: 3;
		}
		
		.next-steps-wrapper {
			background: var(--color-white-opacity90);
			padding: 26px;
			border-radius: var(--border-radius);
			max-height: 660px;
			height: 100%;
			position: relative;
			
			.heading {
				text-align: center;
				font-weight: bold;
				margin-bottom: 20px;
			}
			
			.install-info {
				color: var(--color-light)
			}
			
			.actions {
				position: absolute;
				left: 8px;
				right: 8px;
				bottom: 8px;
				display: flex;
				align-items: center;
				justify-content: space-between;
				
				&.center {
					justify-content: center;
				}
			}
		}
	}
}

.extensions {
	.extension {
		display: flex;
		justify-content: space-between;
		align-items: center;
		border-bottom: 1px solid var(--color-lighter);
		padding: 6px 0;
	}
}

input {
	width: 100%
}

.host-port {
	display: flex;
	flex-wrap: wrap;
	justify-content: space-between;
	
	.host {
		width: calc(100% - 130px);
	}
	
	.port {
		width: 120px;
	}
}

.res {
	display: flex;
	justify-content: space-between;
	padding: 4px 0;
}
</style>