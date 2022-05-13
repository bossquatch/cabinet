<template>
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
                                        Team
                                    </th>
                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">Edit</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <template v-if="disks.length">
                                    <tr v-for="(disk, index) in disks" :key="disk" :class="index % 2 === 0 ? 'bg-white' : 'bg-gray-50'">
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">
                                            {{ disk.id }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                            {{ disk.name }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                            {{ disk.team }}
                                        </td>
                                        <td class="flex px-6 py-4 text-sm font-medium text-right place-content-end whitespace-nowrap">
                                            <custom-nav-link :href="disk.edit_url" class="text-indigo-600 hover:text-indigo-900">
                                                <edit-icon/>
                                            </custom-nav-link>
                                        </td>
                                    </tr>
                                </template>
                                <template v-else>
                                    <tr class="bg-red-300">
                                        <td colspan="4" class="px-6 py-4 text-sm font-medium text-red-900 whitespace-nowrap">
                                            There are no disks registered to your team(s).
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
</template>

<script>
    import { defineComponent } from 'vue'
    import CustomNavLink from '@/BuildingBlocks/NavLink'
    import EditIcon from '@/HeroIcons/Edit.vue'

    export default defineComponent({
        props: [
            'disks',
        ],

        components: {
            CustomNavLink,
            EditIcon,
        },
    })
</script>
