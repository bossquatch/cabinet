<template>
    <app-layout title="Disk Settings">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Disk Settings
            </h2>
        </template>

        <div>
            <div class="py-10 mx-auto max-w-7xl sm:px-6 lg:px-8">
                <update-disk-name-form :disk="disk" :backup_disks="backup_disks" />

                <disk-field-manager class="mt-10 sm:mt-0" :disk="disk" :fields="driverFields" />
            </div>
        </div>
    </app-layout>
</template>

<script>
    import { defineComponent } from 'vue'
    import AppLayout from '@/Layouts/AppLayout.vue'
    import JetSectionBorder from '@/Jetstream/SectionBorder.vue'
    import DiskFieldManager from '@/Pages/Disks/Partials/DiskFieldManager.vue'
    import UpdateDiskNameForm from '@/Pages/Disks/Partials/UpdateDiskNameForm.vue'

    export default defineComponent({
        props: [
            'disk',
            'driverFields',
            'backup_disks',
        ],

        components: {
            AppLayout,
            JetSectionBorder,
            DiskFieldManager,
            UpdateDiskNameForm,
        },
    })
</script>
