<template>
    <div>
        <div class="p-6 bg-white border-b border-gray-200 sm:px-20">
            <div class="text-3xl font-bold">
                Welcome to the Polk County Cabinet!
            </div>

            <div class="mt-6 text-gray-500">
                The Polk County Cabinet provides an elegant, versatile way to securley transfer files to other teams within the organization.
            </div>
        </div>
    </div>
</template>

<script>
    import { defineComponent } from 'vue'
    import JetApplicationLogo from '@/Jetstream/ApplicationLogo.vue'

    export default defineComponent({
        components: {
            JetApplicationLogo,
        },
    })
</script>
