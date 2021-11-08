<template>
    <app-layout title="Upload File">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Upload File
            </h2>
        </template>

        <div>
            <div class="py-10 mx-auto max-w-7xl sm:px-6 lg:px-8">
                <upload-file-form :disks="disks" />
            </div>
        </div>
    </app-layout>
</template>

<script>
    import { defineComponent } from 'vue'
    import AppLayout from '@/Layouts/AppLayout.vue'
    import UploadFileForm from '@/Pages/Upload/Partials/UploadFileForm.vue'

    export default defineComponent({
        props: ['disks'],

        components: {
            AppLayout,
            UploadFileForm,
        },
    })
</script>