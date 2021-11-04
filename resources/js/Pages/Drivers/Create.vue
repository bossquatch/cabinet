<template>
    <app-layout title="Create Driver">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Create Driver
            </h2>
        </template>

        <div>
            <div class="py-10 mx-auto max-w-7xl sm:px-6 lg:px-8">
                <create-driver-form />
            </div>
        </div>
    </app-layout>
</template>

<script>
    import { defineComponent } from 'vue'
    import AppLayout from '@/Layouts/AppLayout.vue'
    import CreateDriverForm from '@/Pages/Drivers/Partials/CreateDriverForm.vue'

    export default defineComponent({
        components: {
            AppLayout,
            CreateDriverForm,
        },
    })
</script>