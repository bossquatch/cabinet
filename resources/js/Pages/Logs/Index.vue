<template>
    <app-layout title="Disk Logs">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Disk Logs
            </h2>
        </template>

        <div>
            <div class="py-10 mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200 sm:px-20">
                        <feed :logs="logs.data" />

                        <pagination class="mt-6" :links="logs.links" />
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
    import { defineComponent } from 'vue'
    import AppLayout from '@/Layouts/AppLayout.vue'
    import Feed from '@/Pages/Logs/Partials/Feed.vue'
    import Pagination from '@/Pages/Logs/Partials/Pagination.vue'

    export default defineComponent({
        props: ['logs'],

        components: {
            AppLayout,
            Feed,
            Pagination,
        },
    })
</script>