<template>
    <app-layout title="Drivers">
        <template #header>
            <div class="md:flex md:items-center md:justify-between">
                <div class="flex-1 min-w-0">
                    <h2 class="text-xl font-semibold leading-tight text-gray-800">
                        Drivers
                    </h2>
                </div>
                <div class="flex mt-4 md:mt-0 md:ml-4">
                    <custom-nav-link :href="route('driver.create')" classes="inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Create
                    </custom-nav-link>
                </div>
            </div>
        </template>

        <div>
            <div class="py-10 mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="flex flex-col">
                    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                            <div class="overflow-hidden border-b border-gray-200 shadow sm:rounded-lg">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                                ID
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                                Name
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                                Type
                                            </th>
                                            <th scope="col" class="relative px-6 py-3">
                                                <span class="sr-only">Edit</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template v-if="drivers.length">
                                            <tr v-for="(driver, index) in drivers" :key="driver" :class="index % 2 === 0 ? 'bg-white' : 'bg-gray-50'">
                                                <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">
                                                    {{ driver.id }}
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                                    {{ driver.display_name }}
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                                    {{ driver.name }}
                                                </td>
                                                <td class="flex px-6 py-4 text-sm font-medium text-right place-content-end whitespace-nowrap">
                                                    <custom-nav-link :href="driver.edit_url" class="text-indigo-600 hover:text-indigo-900">
                                                        <edit-icon/>
                                                    </custom-nav-link>
                                                </td>
                                            </tr>
                                        </template>
                                        <template v-else>
                                            <tr class="bg-red-300">
                                                <td colspan="4" class="px-6 py-4 text-sm font-medium text-red-900 whitespace-nowrap">
                                                    There are no drivers in the system.
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
    import { defineComponent } from 'vue'
    import AppLayout from '@/Layouts/AppLayout.vue'
    import JetSectionBorder from '@/Jetstream/SectionBorder'
    import CustomNavLink from '@/BuildingBlocks/NavLink'
    import EditIcon from '@/HeroIcons/Edit.vue'

    export default defineComponent({
        props: [
            'drivers',
        ],

        components: {
            AppLayout,
            CustomNavLink,
            JetSectionBorder,
            EditIcon
        },
    })
</script>
