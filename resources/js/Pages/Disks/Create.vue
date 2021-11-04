<template>
    <app-layout title="Create Disk">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Create Disk
            </h2>
        </template>

        <div>
            <div class="py-10 mx-auto max-w-7xl sm:px-6 lg:px-8">
                <create-disk-form :drivers="drivers" />
            </div>
        </div>
    </app-layout>
</template>

<script>
    import { defineComponent } from 'vue'
    import AppLayout from '@/Layouts/AppLayout.vue'
    import CreateDiskForm from '@/Pages/Disks/Partials/CreateDiskForm.vue'

    export default defineComponent({
        props: ['drivers'],

        components: {
            AppLayout,
            CreateDiskForm,
        },
    })
</script>