<template>
    <select class="block w-full py-2 pl-3 pr-10 mt-1 text-base border-gray-300 rounded-md focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 focus:z-10 sm:text-sm" @input="$emit('update:modelValue', $event.target.value)">
        <slot />
    </select>
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

