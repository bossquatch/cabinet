<template>
    <app-layout title="Driver Settings">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Driver Settings
            </h2>
        </template>

        <div>
            <div class="py-10 mx-auto max-w-7xl sm:px-6 lg:px-8">
                <update-driver-name-form :driver="driver" />

                <driver-field-manager class="mt-10 sm:mt-0" :driver="driver" />
            </div>
        </div>
    </app-layout>
</template>

<script>
    import { defineComponent } from 'vue'
    import AppLayout from '@/Layouts/AppLayout.vue'
    import JetSectionBorder from '@/Jetstream/SectionBorder.vue'
    import DriverFieldManager from '@/Pages/Drivers/Partials/DriverFieldManager.vue'
    import UpdateDriverNameForm from '@/Pages/Drivers/Partials/UpdateDriverNameForm.vue'

    export default defineComponent({
        props: [
            'driver',
        ],

        components: {
            AppLayout,
            JetSectionBorder,
            DriverFieldManager,
            UpdateDriverNameForm,
        },
    })
</script>
