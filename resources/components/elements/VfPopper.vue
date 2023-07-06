<template>
    <Dropdown>
        <slot />
        <template #popper>
            <slot name="popper">
                Popper content
            </slot>
        </template>
    </Dropdown>
</template>

<script>
    import { Dropdown } from 'floating-vue'

    export default {
        name: 'VfPopper',
        components: {
            Dropdown
        }
    }
</script>

<style scoped>

</style>
