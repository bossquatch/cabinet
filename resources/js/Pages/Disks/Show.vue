<template>
    <app-layout title="Disk Settings">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Disk Settings
            </h2>
        </template>

        <div>
            <div class="py-10 mx-auto max-w-7xl sm:px-6 lg:px-8">
                <update-disk-name-form :disk="disk" :backup_disks="backup_disks" :templates="templates" />

                <disk-field-manager class="mt-10 sm:mt-0" :disk="disk" :fields="fields" />
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
            'templates',
        ],

        components: {
            AppLayout,
            JetSectionBorder,
            DiskFieldManager,
            UpdateDiskNameForm,
        },

        computed: {
            fields() {
                let templatedIds = []
                let fields = []
                if (this.disk.template !== null) {
                    if (this.disk.template.disk_driver_fields !== null) {
                        this.disk.template.disk_driver_fields.forEach((value) => {
                            templatedIds.push(value.driver_field_id)
                        })
                    }
                }

                this.driverFields.forEach((value, index) => {
                    if (!templatedIds.includes(value.id)) {
                        fields.push(value)
                    }
                });

                return fields
            }
        }
    })
</script>
