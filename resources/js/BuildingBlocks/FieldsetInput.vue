<template>
    <input class="relative block w-full bg-transparent border-gray-300 rounded-none focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 focus:z-10 sm:text-sm" :value="modelValue" @input="$emit('update:modelValue', $event.target.value)" ref="input">
</template>

<script>
    export default {
        props: [
            'modelValue',
        ],

        emits: ['update:modelValue'],

        methods: {
            focus() {
                this.$refs.input.focus()
            }
        }
    }
</script>

