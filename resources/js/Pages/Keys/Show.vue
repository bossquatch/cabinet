<template>
    <app-layout title="Key Settings">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Key Settings
            </h2>
        </template>

        <div>
            <div class="py-10 mx-auto max-w-7xl sm:px-6 lg:px-8">
                <update-key-form :skey="skey" />
            </div>
        </div>
    </app-layout>
</template>

<script>
    import { defineComponent } from 'vue'
    import AppLayout from '@/Layouts/AppLayout.vue'
    import JetSectionBorder from '@/Jetstream/SectionBorder.vue'
    import UpdateKeyForm from '@/Pages/Keys/Partials/UpdateKeyForm.vue'

    export default defineComponent({
        props: [
            'skey',
        ],

        components: {
            AppLayout,
            JetSectionBorder,
            UpdateKeyForm,
        },
    })
</script>