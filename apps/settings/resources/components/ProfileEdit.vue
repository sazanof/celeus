<template>
    <div
        class="profile-edit"
        v-if="user !== null">
        <div class="profile-inner">
            <div class="photo">
                <img
                    class="user-pic"
                    :src="photoUrl !== null ? photoUrl : `/user/${user.username}/avatar?size=512`">
                <VfCropper
                    @on-apply="cropImage"
                    ref="cropperModal"
                    :file="file" />
                <VfFileUpload
                    accept="image/jpeg,image/png"
                    ref="fileUpload"
                    @input-file-changed="inputFileChanged">
                    <template
                        #trigger>
                        <VfButton
                            @click="this.$refs.fileUpload.triggerInput()"
                            type="gray"
                            class="button-upload">
                            <template #icon>
                                <UploadIcon :size="20" />
                            </template>
                            {{ $t('settings', 'Upload photo') }}
                        </VfButton>
                    </template>
                </VfFileUpload>
            </div>


            <div
                class="profile-main"
                data-simplebar>
                <VfSection
                    :title="$t('core', 'Profile')"
                    :description="$t('core','Basic information')">
                    <template #icon>
                        <AccountIcon :size="42" />
                    </template>
                    <div class="form-group form-group-50">
                        <VfInput
                            :value="user.firstname"
                            :label="$t('core','Firstname')"
                            @on-change="user.firstname = $event.target.value" />
                        <VfInput
                            :value="user.lastname"
                            :label="$t('core','Lastname')"
                            @on-change="user.lastname = $event.target.value" />
                    </div>
                    <div class="form-group">
                        <VfInput
                            type="email"
                            :value="user.email"
                            :label="$t('core','Email')"
                            @on-change="user.email = $event.target.value" />
                    </div>
                    <div class="form-group">
                        <VfInput
                            :value="user.organization"
                            :label="$t('core','Organization')"
                            @on-change="user.organization = $event.target.value" />
                    </div>
                    <div class="form-group">
                        <VfInput
                            :value="user.position"
                            :label="$t('core','Position')"
                            @on-change="user.position = $event.target.value" />
                    </div>
                    <div class="form-group">
                        <VfInput
                            :value="user.phone"
                            :label="$t('core','Phone')"
                            @on-change="user.phone = $event.target.value" />
                    </div>
                    <div class="form-group">
                        <VfTextarea
                            mode="richtext"
                            :value="user.about"
                            :label="$t('core','About')"
                            @update:text="user.about = $event" />
                    </div>
                    <div class="form-group">
                        <VfRadioCheck
                            :label="$t('core', 'Change password')"
                            :checked="changePassword"
                            @update:checked="changePassword = !changePassword" />
                    </div>
                    <div
                        class="form-group form-group-50"
                        v-if="changePassword">
                        <VfInput
                            :value="password"
                            :label="$t('core','Password')"
                            type="password"
                            @on-change="password = $event.target.value" />
                        <VfInput
                            :value="repeatPassword"
                            :label="$t('core','Password confirmation')"
                            type="password"
                            @on-change="repeatPassword = $event.target.value" />
                    </div>
                    <div class="form-group">
                        <VfButton
                            @click="saveProfile">
                            <ContentSave :size="20" />
                            {{ $t('settings', 'Save profile') }}
                        </VfButton>
                    </div>
                </VfSection>
                <VfSection
                    :title="$t('core', 'Groups')"
                    :description="$t('core', 'You are a member of the following groups')">
                    <template #icon>
                        <AccountMultipleIcon :size="42" />
                    </template>
                    <div
                        class="user-groups"
                        v-if="user.groups.length > 0">
                        <div
                            class="group"
                            v-for="group in user.groups"
                            :key="group.id">
                            {{ group.name }}
                        </div>
                    </div>
                </VfSection>
            </div>
        </div>
    </div>
</template>

<script>
    import UploadIcon from 'vue-material-design-icons/Upload.vue'
    import AccountIcon from 'vue-material-design-icons/Account.vue'
    import AccountMultipleIcon from 'vue-material-design-icons/AccountMultiple.vue'
    import VfSection from '../../../../resources/components/elements/VfSection.vue'
    import { useToast } from 'vue-toastification'
    import VfCropper from '../../../../resources/components/elements/VfCropper.vue'
    import VfRadioCheck from '../../../../resources/components/elements/VfRadioCheck.vue'
    import VfTextarea from '../../../../resources/components/elements/VfTextarea.vue'
    import VfInput from '../../../../resources/components/elements/VfInput.vue'
    import VfButton from '../../../../resources/components/elements/VfButton.vue'
    import VfAvatar from '../../../../resources/components/elements/VfAvatar.vue'
    import VfFileUpload from '../../../../resources/components/elements/VfFileUpload.vue'
    import ContentSave from 'vue-material-design-icons/ContentSave.vue'

    const toast = useToast()

    export default {
        name: 'ProfileEdit',
        components: {
            VfCropper,
            VfAvatar,
            VfTextarea,
            VfInput,
            VfButton,
            VfRadioCheck,
            VfSection,
            VfFileUpload,
            ContentSave,
            AccountIcon,
            AccountMultipleIcon,
            UploadIcon
        },
        data() {
            return {
                changePassword: false,
                password: '',
                repeatPassword: '',
                photoUrl: null,
                file: null,
                user: null
            }
        },
        watch: {
            changePassword() {
                if (!this.changePassword) {
                    this.password = ''
                    this.repeatPassword = ''
                }
            }
        },
        created() {
            this.user = this.$store.getters['getUser']
            Emitter.on('settings.user.saved', user => {
                this.user = user
            })
        },

        methods: {
            async saveProfile() {
                this.data = this.changePassword ? Object.assign(this.user, {
                    password: this.password,
                    repeatPassword: this.repeatPassword
                }) : this.user
                await this.$store.dispatch('saveUser', this.user).then(() => {
                    toast.success(this.$t('settings', 'Profile saved'))
                    Emitter.emit('profile.saved', this.user)
                }).catch(e => {
                    // TODO toast error component
                    console.log(e.response.data.message)
                    toast.error('Error')
                })
            },
            async cropImage({ coordinates, canvas }) {
                const res = await this.$store.dispatch('saveUserPhoto', {
                    file: this.file,
                    coordinates
                }).catch(e => {
                    console.log(e)
                    toast.error(this.$t('settings', 'Error saving profile photo'))
                    return false
                })
                this.photoUrl = canvas.toDataURL()
                this.user.photo = res.photo
                toast.success(this.$t('settings', 'Image saved successfully'))
                Emitter.emit('settings.avatar.change', canvas.toDataURL())
            },
            inputFileChanged(file) {
                console.log(file[0])
                this.file = file[0]
                this.$refs.cropperModal.open()
            },
        }
    }
</script>

<style lang="scss" scoped>
.profile-inner {
  display: flex;
  flex-wrap: wrap;


  img {
	width: 260px;
	height: 260px;
	border-radius: 50%;
  }

  .profile-main {
	width: calc(100% - 260px);
	height: calc(100vh - var(--header-height) - (var(--padding-box) * 2));
	padding: 0 20px 0 32px;

	& > ::v-deep(.simplebar-vertical) {
	  top: 20px
	}
  }

  .button-upload {
	margin: 10px auto;
  }
}


h1 {
  margin-top: 0
}
</style>