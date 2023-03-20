<template>
    <component :is="comp" />
</template>

<script>
//Vue 3 is having a special function to define these async functions
    import { defineAsyncComponent } from 'vue'

    export default {
        name: 'DynamicMenuIcon',
        //I am passing the name of the Component as a prop
        props: {
            name: {
                type: String,
                required: true
            }
        },
        computed: {
            comp() {
                console.log(this.path)
                return defineAsyncComponent(() => import(`vue-material-design-icons/${this.name}.vue`))
            }
        }
    }
</script>