<template>
    <div class="flex items-center">
        <input :id="name" type="checkbox" class="w-4 h-4 text-indigo-600 border-gray-300 rounded shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" v-model="modelValue" @input="$emit('update:modelValue', $event.target.checked ? 1 : 0)" ref="input" true-value="1" false-value="0" />
        <label :for="name" class="block ml-3 text-sm font-medium text-gray-700">
            {{ name }}
        </label>
    </div>
</template>

<script>
    export default {
        props: [
            'modelValue',
            'name',
        ],

        emits: ['update:modelValue'],

        methods: {
            focus() {
                this.$refs.input.focus()
            }
        }
    }
</script>