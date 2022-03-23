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
                
                <share-key-form v-if="skey.public == false" :skey="skey" />

                <revoke-key-form v-if="skey.public == false" :skey="skey" :shared_users="shared_users" />
            </div>
        </div>
    </app-layout>
</template>

<script>
    import { defineComponent } from 'vue'
    import AppLayout from '@/Layouts/AppLayout.vue'
    import JetSectionBorder from '@/Jetstream/SectionBorder.vue'
    import UpdateKeyForm from '@/Pages/Keys/Partials/UpdateKeyForm.vue'
    import ShareKeyForm from '@/Pages/Keys/Partials/ShareKeyForm.vue'
    import RevokeKeyForm from '@/Pages/Keys/Partials/RevokeKeyForm.vue'

    export default defineComponent({
        props: ['skey', 'shared_users'],

        components: {
            AppLayout,
            JetSectionBorder,
            UpdateKeyForm,
            ShareKeyForm,
            RevokeKeyForm,
        },
    })
</script>