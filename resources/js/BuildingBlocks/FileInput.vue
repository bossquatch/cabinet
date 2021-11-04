<template>
    <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-md shadow-sm cursor-pointer focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" @input="$emit('update:modelValue', $event.target.files[0])" ref="input" type="file">
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

<style lang="postcss" scoped>
input[type=file]::-webkit-file-upload-button,
input[type=file]::file-selector-button {
	@apply text-white bg-gray-700 hover:bg-gray-600 font-medium text-sm cursor-pointer border-0 py-2.5 pl-8 pr-4;
	margin-inline-start: -1rem;
	margin-inline-end: 1rem;
}
</style>
