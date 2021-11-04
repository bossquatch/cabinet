<template>
    <fieldset>
        <legend class="block text-sm font-medium text-gray-700">
            <span v-if="value">{{ value }}</span>
            <span v-else>ENTER FIELDSET NAME VALUE!</span>
        </legend>

        <div class="mt-1 -space-y-px bg-white rounded-md shadow-sm">
            <slot></slot>
        </div>
    </fieldset>
</template>

<script>
    export default {
        props: ['value']
    }
</script>