<template>
    <app-layout title="Disks">
        <template #header>
            <div class="md:flex md:items-center md:justify-between">
                <div class="flex-1 min-w-0">
                    <h2 class="text-xl font-semibold leading-tight text-gray-800">
                        Disks
                    </h2>
                </div>
                <div class="flex mt-4 md:mt-0 md:ml-4" v-if="$page.props.user.is_admin" >
                    <custom-nav-link :href="route('disk.create')" classes="inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Create
                    </custom-nav-link>
                </div>
            </div>
        </template>

        <div>
            <disk-list :disks="disks" />

            <jet-section-border class="mx-auto mt-5 max-w-7xl sm:px-6 lg:px-8" />

            <div class="mx-auto md:flex md:items-center md:justify-between max-w-7xl sm:px-6 lg:px-8">
                <div class="flex-1 min-w-0">
                    <h2 class="text-xl font-semibold leading-tight text-gray-800">
                        Templates
                    </h2>
                </div>
            </div>

            <disk-list :disks="templates" />
        </div>
    </app-layout>
</template>

<script>
    import { defineComponent } from 'vue'
    import AppLayout from '@/Layouts/AppLayout.vue'
    import DiskList from '@/Pages/Disks/Partials/DiskList.vue'
    import JetSectionBorder from '@/Jetstream/SectionBorder'
    import CustomNavLink from '@/BuildingBlocks/NavLink'

    export default defineComponent({
        props: [
            'disks',
            'templates',
        ],

        components: {
            AppLayout,
            CustomNavLink,
            DiskList,
            JetSectionBorder
        },
    })
</script>
