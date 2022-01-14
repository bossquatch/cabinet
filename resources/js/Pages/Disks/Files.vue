<template>
    <app-layout :title="disk.name">
        <template #header>
            <div class="md:flex md:items-center md:justify-between">
                <div class="flex-1 min-w-0">
                    <h2 class="text-xl font-semibold leading-tight text-gray-800">
                        {{ disk.name }}
                    </h2>
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
                                                Filename
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                                Last Modified
                                            </th>
                                            <th scope="col" class="relative px-6 py-3">
                                                <span class="sr-only">Edit</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template v-if="files.length">
                                            <tr v-for="(file, index) in files" :key="file" :class="index % 2 === 0 ? 'bg-white' : 'bg-gray-50'">
                                                <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">
                                                    {{ file.name }}
                                                </td>
                                                <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">
                                                    {{ file.last_modified }}
                                                </td>
                                                <td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap">
                                                    <a :href="route('disk.file-download', {'disk' : disk.id, 'file' : file.name })" class="text-indigo-600 hover:text-indigo-900">Download</a>
                                                    <a v-if="$page.props.user.is_admin" :href="route('disk.file-delete', {'disk' : disk.id, 'file' : file.name })" class="ml-2 text-red-600 hover:text-red-900">Delete</a>
                                                </td>
                                            </tr>
                                        </template>
                                        <template v-else>
                                            <tr class="bg-red-300">
                                                <td colspan="4" class="px-6 py-4 text-sm font-medium text-red-900 whitespace-nowrap">
                                                    There are no files in this directory.
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

    export default defineComponent({
        props: [
            'disk',
            'files'
        ],

        components: {
            AppLayout,
            JetSectionBorder,
        },
    })
</script>
