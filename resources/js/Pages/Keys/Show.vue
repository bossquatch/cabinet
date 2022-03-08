<template>
    <app-layout title="Key Settings">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Key Settings
            </h2>
        </template>

        <div>
            <div class="py-10 mx-auto max-w-7xl sm:px-6 lg:px-8">
                Edit Key Form Goes Here
            </div>
        </div>
    </app-layout>
</template>

<script>
    import { defineComponent } from 'vue'
    import AppLayout from '@/Layouts/AppLayout.vue'
    import JetSectionBorder from '@/Jetstream/SectionBorder.vue'

    export default defineComponent({
        props: [
            'key',
        ],

        components: {
            AppLayout,
            JetSectionBorder,
        },
    })
</script>