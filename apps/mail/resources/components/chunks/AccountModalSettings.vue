<template>
    <VfModal
        v-if="account"
        ref="accountModal"
        :title="$t('mail','Account settings')">
        <VfInput
            :value="account.name"
            :label="$t('mail', 'Account name')" />
        <VfInput
            :value="account.email"
            :label="$t('mail', 'Email')" />
        {{ account }}
    </VfModal>
</template>

<script>
    import VfModal from '../../../../../resources/components/elements/VfModal.vue'
    import VfInput from '../../../../../resources/components/elements/VfInput.vue'

    export default {
        name: 'AccountModalSettings',
        components: {
            VfModal,
            VfInput
        },
        props: {
            account: {
                type: Object,
                required: true
            }
        },
        mounted() {

        },
        methods: {
            open() {
                this.$refs.accountModal.open()
                this.$store.dispatch('getAccountSettings', this.account.id)
            },
            close() {
                this.$refs.accountModal.close()
            }
        }
    }
</script>

<style scoped>

</style>
