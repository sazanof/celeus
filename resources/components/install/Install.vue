<template>
    <div
        class="install">
        <div
            class="steps-progress"
            v-if="step > 0">
            <div
                class="step-progress-item"
                :class="{active:step ===1}">
                1
            </div>
            <div
                class="step-progress-item"
                :class="{active:step===2}">
                2
            </div>
            <div
                class="step-progress-item"
                :class="{active:step===3}">
                3
            </div>
            <div
                class="step-progress-item"
                :class="{active:step===4}">
                4
            </div>
            <div
                class="step-progress-item"
                :class="{active:step===5}">
                5
            </div>
        </div>

        <div
            class="install-inner"
            :class="{visible:visible}">
            <div class="logo">
                <img
                    src="/images/vorkfork3.svg"
                    alt="">
            </div>
            <div
                class="install-step"
                v-if="!start">
                <span class="description">
                    {{ $t('Your personal space for collaboration!') }}
                </span>
                <div class="switcher">
                    <LanguageSwitcher @on-locale-change="onLocaleChange" />
                    <VfButton
                        @click="toStep(1)"
                        class="centered">
                        <Send :size="18" />
                        {{ $t('Begin install') }}
                    </VfButton>
                </div>
            </div>
            <div
                v-else
                class="next-steps">
                <div class="next-steps-wrapper">
                    <div class="heading">
                        {{ heading }}
                    </div>
                    <!--STEP 1-->
                    <div
                        class="install-step step"
                        v-if="step === 1">
                        <div class="step-inner">
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
                        </div>

                        <div class="actions">
                            <VfButton
                                type="secondary"
                                @click="toStep(0)">
                                <ChevronLeft :size="18" />
                                {{ $t('Back') }}
                            </VfButton>
                            <VfButton
                                v-if="!disabled"
                                @click="toStep(step +1)">
                                <ChevronRight :size="18" />
                                {{ $t('Next') }}
                            </VfButton>
                            <VfButton
                                v-else
                                @click="checkExtensions">
                                <Refresh :size="18" />
                                {{ $t('Retry') }}
                            </VfButton>
                        </div>
                    </div>
                    <!--STEP 2-->
                    <div
                        class="install-step step"
                        v-if="step ===2">
                        <div class="step-inner">
                            <div class="form-group">
                                <VfInput
                                    type="text"
                                    :value="connection.driver"
                                    :label="$t('Database type')"
                                    :disabled="true" />
                            </div>

                            <div class="form-group">
                                <div class="host-port">
                                    <div class="host">
                                        <VfInput
                                            type="text"
                                            @on-change="e => connection.host = e.target.value"
                                            :value="connection.host"
                                            :label="$t('Database host')" />
                                    </div>
                                    <div class="port">
                                        <VfInput
                                            type="text"
                                            @on-change="e => connection.port = e.target.value"
                                            :value="connection.port"
                                            :label="$t('Port')" />
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <VfInput
                                    type="text"
                                    @on-change="e => connection.dbname = e.target.value"
                                    :value="connection.dbname"
                                    :label="$t('Database')" />
                            </div>

                            <div class="form-group">
                                <VfInput
                                    type="text"
                                    @on-change="e => connection.user = e.target.value"
                                    :value="connection.user"
                                    :label="$t('Username')" />
                            </div>
                            <div class="form-group">
                                <VfInput
                                    type="password"
                                    @on-change="e => connection.password = e.target.value"
                                    :value="connection.password"
                                    :label="$t('Password')" />
                            </div>
                        </div>

                        <div class="actions">
                            <VfButton
                                type="secondary"
                                @click="toStep(step - 1)">
                                <ChevronLeft :size="18" />
                                {{ $t('Back') }}
                            </VfButton>
                            <VfButton
                                v-if="connectionSuccess"
                                :disabled="disabled"
                                @click="toStep(step + 1)">
                                <ChevronRight :size="18" />
                                {{ $t('Next') }}
                            </VfButton>
                            <VfButton
                                v-else
                                :disabled="disabled"
                                @click="checkConnection">
                                <ChevronRight :size="18" />
                                {{ $t('Check') }}
                            </VfButton>
                        </div>
                    </div>
                    <!--STEP 3-->
                    <div
                        class="install-step step"
                        v-if="step ===3">
                        <div class="step-inner">
                            <div class="form-group">
                                <VfInput
                                    :label="$t('Username')"
                                    :value="admin.username"
                                    @on-change="e => admin.username = e.target.value" />
                            </div>
                            <div class="form-group">
                                <VfInput
                                    :label="$t('Email')"
                                    :value="admin.email"
                                    @on-change="e => admin.email = e.target.value" />
                            </div>
                            <div class="form-group">
                                <VfInput
                                    :label="$t('Firstname')"
                                    :value="admin.firstname"
                                    @on-change="e => admin.firstname = e.target.value" />
                            </div>
                            <div class="form-group">
                                <VfInput
                                    :label="$t('Lastname')"
                                    :value="admin.lastname"
                                    @on-change="e => admin.lastname = e.target.value" />
                            </div>
                            <div class="form-group">
                                <VfInput
                                    :label="$t('Password')"
                                    :value="admin.password"
                                    type="password"
                                    @on-change="e => admin.password = e.target.value" />
                            </div>
                            <div class="form-group">
                                <VfInput
                                    :label="$t('Password confirmation')"
                                    :value="admin.repeatPassword"
                                    type="password"
                                    @on-change="e => admin.repeatPassword = e.target.value" />
                            </div>
                        </div>

                        <div class="actions">
                            <VfButton
                                type="secondary"
                                @click="toStep(step - 1)">
                                <ChevronLeft :size="18" />
                                {{ $t('Back') }}
                            </VfButton>
                            <VfButton
                                :disabled="!adminDataCorrect"
                                @click="toStep(step + 1)">
                                <ChevronRight :size="18" />
                                {{ $t('Next') }}
                            </VfButton>
                        </div>
                    </div>
                    <!--STEP 4-->
                    <div
                        class="install-step step"
                        v-if="step === 4">
                        <div class="step-inner">
                            <div
                                class="install-info"
                                ref="installInfo">
                                {{
                                    $t('The system is ready for installation. To continue, click on the Install button.')
                                }}
                            </div>
                        </div>

                        <div class="actions">
                            <VfButton
                                type="secondary"
                                @click="toStep(step - 1)">
                                <ChevronLeft :size="18" />
                                {{ $t('Back') }}
                            </VfButton>
                            <VfButton
                                :disabled="disabled"
                                @click="install()">
                                <ChevronRight :size="18" />
                                {{ $t('Install') }}
                            </VfButton>
                        </div>
                    </div>
                    <!--STEP 5-->
                    <div
                        class="install-step step"
                        v-if="step === 5">
                        <div class="step-inner">
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
                        </div>

                        <div class="actions center">
                            <VfButton @click="endInstall">
                                {{ $t('Let\'s get started') }}
                            </VfButton>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { useToast } from 'vue-toastification'
    import axios from 'axios'
    import VfButton from '../elements/VfButton.vue'
    import VfInput from '../elements/VfInput.vue'
    import LanguageSwitcher from '../i18n/LanguageSwitcher.vue'
    import Check from 'vue-material-design-icons/Check.vue'
    import Close from 'vue-material-design-icons/Close.vue'
    import ChevronRight from 'vue-material-design-icons/ChevronRight.vue'
    import ChevronLeft from 'vue-material-design-icons/ChevronLeft.vue'
    import Refresh from 'vue-material-design-icons/Refresh.vue'
    import Send from 'vue-material-design-icons/Send.vue'

    const toast = useToast()

    export default {
        name: 'Install',
        components: {
            VfButton,
            VfInput,
            LanguageSwitcher,
            Check,
            Close,
            ChevronLeft,
            ChevronRight,
            Refresh,
            Send
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
                installed: false,
                visible: false
            }
        },
        computed: {
            disabled() {
                return this.errorExtensions.length > 0 || this.loading
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
                if (this.installed) {
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
            setTimeout(() => {
                this.visible = true
            })
            this.connection = {
                driver: 'mysqli',
                host: 'localhost',
                dbname: 'vorkfork',
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
                    this.loading = false
                    toast.error(this.$t(e.response.data.message))
                })
                this.loading = false
                this.installResult = res.data
                this.toStep(this.step + 1)
                this.installed = true
            },
            endInstall() {
                window.location.replace('/')
            }
        }
    }
</script>

<style lang="scss" scoped>

.steps-progress {
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
  width: 100%;
  max-width: 420px;
  padding-bottom: 26px;

  .step-progress-item {
    font-size: 0;
    width: calc(20% - 10px);
    height: 6px;
    border-radius: 6px;
    background: var(--color-white-opacity50);

    &.active {
      background: var(--color-white)
    }
  }
}

.install {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  z-index: 2;
  background-color: var(--color-primary);
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  flex-wrap: wrap;

  .install-inner {
    max-width: 460px;
    width: 100%;
    padding: var(--padding-box) 40px;
    background: var(--color-white);
    border-radius: var(--border-radius-big);
    opacity: 0;
    transition: 2s;

    &.visible {
      opacity: 1;
    }

    .logo {
      color: var(--color-text);
      padding: 20px 0;

      img {
        fill: var(--color-primary);
        display: block;
        margin: 0 auto;
      }
    }

    .description {
      font-size: 14px;
      display: block;
      text-align: center;
      margin-bottom: 30px;
      margin-top: 14px;
      color: var(--color-text)
    }

    .switcher {
      max-width: 260px;
      margin: 0 auto;

      .btn {
        margin-top: 20px;
        width: 100%;
      }
    }
  }

  .next-steps {
    position: relative;

    .next-steps-wrapper {
      background: var(--color-white-opacity90);
      padding: 26px 0;
      border-radius: var(--border-radius);
      height: 100%;
      position: relative;

      .step-inner {
        max-height: 500px;
        overflow-y: auto;
      }

      .heading {
        text-align: center;
        font-weight: bold;
        margin-bottom: 20px;
      }

      .install-info {
        color: var(--color-light)
      }

      .actions {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin: 20px -10px 0 -10px;

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
