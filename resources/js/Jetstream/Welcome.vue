<template>
    <div>
        <div class="p-6 bg-white border-b border-gray-200 sm:px-20">
            <div class="text-3xl font-bold">
                Welcome to the Polk County Cabinet!
            </div>

            <div class="mt-6 text-gray-500">
                The Polk County Cabinet provides an elegant, versatile way to securley transfer files to other teams within the organization.
            </div>

            <div class="mt-4">
                <custom-nav-link :href="route('upload.form')" class="text-lg text-indigo-600 hover:text-indigo-900">Upload a File</custom-nav-link>
            </div>
        </div>
    </div>
</template>

<script>
    import { defineComponent } from 'vue'
    import JetApplicationLogo from '@/Jetstream/ApplicationLogo.vue'
    import CustomNavLink from '@/BuildingBlocks/NavLink'

    export default defineComponent({
        components: {
            CustomNavLink,
            JetApplicationLogo,
        },
    })
</script>
