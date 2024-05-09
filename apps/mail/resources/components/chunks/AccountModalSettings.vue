<template>
    <VfModal
        v-if="account"
        ref="accountModal"
        :title="$t('mail','Account settings')">
        {{ account }}
    </VfModal>
</template>

<script>
    import VfModal from '../../../../../resources/components/elements/VfModal.vue'

    export default {
        name: 'AccountModalSettings',
        components: {
            VfModal
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
            },
            close() {
                this.$refs.accountModal.close()
            }
        }
    }
</script>

<style scoped>

</style>
