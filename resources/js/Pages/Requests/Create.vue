<template>
    <app-layout title="Create Key Access Request">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Create Key Access Request
            </h2>
        </template>

        <div>
            <div class="py-10 mx-auto max-w-7xl sm:px-6 lg:px-8">
                <create-key-access-request-form />
            </div>
        </div>
    </app-layout>
</template>

<script>
    import { defineComponent } from 'vue'
    import AppLayout from '@/Layouts/AppLayout.vue'
    import CreateKeyAccessRequestForm from '@/Pages/Requests/Partials/CreateKeyAccessRequestForm.vue'

    export default defineComponent({
        components: {
            AppLayout,
            CreateKeyAccessRequestForm
        },
    })
</script>