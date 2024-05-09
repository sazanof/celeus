<template>
    <VfPopper>
        <div
            class="page-helper"
            @click="opened = !opened">
            <div class="helper-text">
                {{ timestamp }}
            </div>
            <div class="helper-icon">
                <ChevronUp
                    v-if="opened"
                    :size="20" />
                <ChevronDown
                    v-else
                    :size="20" />
            </div>
        </div>
        <template #popper>
            123 erfe rfwe fw efwer fwe rf
        </template>
    </VfPopper>
</template>

<script>
    import VfPopper from '../elements/VfPopper.vue'
    import ChevronDown from 'vue-material-design-icons/ChevronDown.vue'
    import ChevronUp from 'vue-material-design-icons/ChevronUp.vue'

    export default {
        name: 'PageCenterHelper',
        components: {
            ChevronDown,
            ChevronUp,
            VfPopper
        },
        data() {
            return {
                opened: false,
                timestamp: null
            }
        },
        created() {
            this.getNow()
            setInterval(() => {
                this.getNow()
            }, 1000)
        },
        methods: {
            getNow: function () {

                this.timestamp = this.$moment().locale(this.$locale).format('ddd, DD MMMM HH:mm')
            }
        }
    }
</script>

<style lang="scss" scoped>
.page-helper {
    --animate-duration: var(--transition-duration-mini);
    padding: 6px 10px 6px 16px;
    border-radius: var(--border-radius);
    color: var(--color-white);
    background: var(--color-white-opacity10);
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: relative;
    cursor: pointer;

    .helper-icon {
        margin-left: 6px;
        margin-top: 2px;
    }

    .helper-content {
        min-height: 200px;
        padding: var(--padding-box);
        border-radius: var(--border-radius-big);
        width: 400px;
        position: absolute;
        top: 50px;
        margin-left: -200px;
        left: 50%;
        color: var(--color-text);
        background: var(--color-white);
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);

        &:before {
            content: '';
            border: 10px solid transparent;
            border-bottom: 10px solid var(--color-white);
            display: block;
            width: 0;
            height: 0;
            position: absolute;
            top: -20px;
            left: 190px
        }
    }
}
</style>
