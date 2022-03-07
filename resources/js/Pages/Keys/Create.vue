<template>
    <app-layout title="Create Key">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Create Key
            </h2>
        </template>

        <div>
            <div class="py-10 mx-auto max-w-7xl sm:px-6 lg:px-8">
                Form Goes Here
            </div>
        </div>
    </app-layout>
</template>

<script>
    import { defineComponent } from 'vue'
    import AppLayout from '@/Layouts/AppLayout.vue'

    export default defineComponent({
        components: {
            AppLayout
        },
    })
</script>