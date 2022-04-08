<template>
    <app-layout title="Keys">
        <template #header>
            <div class="md:flex md:items-center md:justify-between">
                <div class="flex-1 min-w-0">
                    <h2 class="text-xl font-semibold leading-tight text-gray-800">
                        Keys
                    </h2>
                </div>
                <div class="flex mt-4 md:mt-0 md:ml-4">
                    <custom-nav-link :href="route('key.create')" classes="inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
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
                                                Description
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                                Value
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                                Public
                                            </th>
                                            <th scope="col" class="relative px-6 py-3">
                                                <span class="sr-only">Edit</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template v-if="keys.length || sharedKeys.length">
                                            <template v-if="keys.length">
                                                <tr v-for="(key, index) in keys" :key="key" :class="index % 2 === 0 ? 'bg-white' : 'bg-gray-50'">
                                                    <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">
                                                        {{ key.description }}
                                                    </td>
                                                    <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                                        {{ key.value }}
                                                        <button class="ml-4 text-blue-600" @click="copy(key.value)">Copy</button>
                                                    </td>
                                                    <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                                        {{ key.public ? "Yes" : "No" }}
                                                    </td>
                                                    <td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap">
                                                        <custom-nav-link v-if="myID == key.owner_id" :href="key.edit_url" class="text-indigo-600 hover:text-indigo-900">Edit</custom-nav-link>
                                                    </td>
                                                </tr>
                                            </template>
                                            <template v-if="sharedKeys.length">
                                                <tr v-for="(key, index) in sharedKeys" :key="key" :class="index % 2 === 0 ? 'bg-white' : 'bg-gray-50'">
                                                    <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">
                                                        {{ key.description }}
                                                    </td>
                                                    <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                                        {{ key.value }}
                                                        <button class="ml-4 text-blue-600" @click="copy(key.value)">Copy</button>
                                                    </td>
                                                    <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                                        {{ key.public ? "Yes" : "No" }}
                                                    </td>
                                                    <td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap">
                                                        <custom-nav-link v-if="myID == key.owner_id" :href="key.edit_url" class="text-indigo-600 hover:text-indigo-900">Edit</custom-nav-link>
                                                    </td>
                                                </tr>
                                            </template>
                                        </template>
                                        <template v-else>
                                            <tr class="bg-red-300">
                                                <td colspan="4" class="px-6 py-4 text-sm font-medium text-red-900 whitespace-nowrap">
                                                    You do not have access to any keys.
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>

                            <div v-if="$page.props.user.is_admin">
                                <jet-section-border/>
                                <h2 class="mb-4 text-xl font-semibold leading-tight text-gray-800">
                                    Admin Accessed Keys
                                </h2>
                                <div class="overflow-hidden border-b border-gray-200 shadow sm:rounded-lg">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                                    Description
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                                    Value
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                                    Public
                                                </th>
                                                <th scope="col" class="relative px-6 py-3">
                                                    <span class="sr-only">Edit</span>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <template v-if="adminAccessedKeys.length">
                                                <tr v-for="(key, index) in adminAccessedKeys" :key="key" :class="index % 2 === 0 ? 'bg-white' : 'bg-gray-50'">
                                                    <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">
                                                        {{ key.description }}
                                                    </td>
                                                    <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                                        {{ key.value }}
                                                        <button class="ml-4 text-blue-600" @click="copy(key.value)">Copy</button>
                                                    </td>
                                                    <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                                        {{ key.public ? "Yes" : "No" }}
                                                    </td>
                                                    <td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap">
                                                        <custom-nav-link :href="key.edit_url" class="text-indigo-600 hover:text-indigo-900">Edit</custom-nav-link>
                                                    </td>
                                                </tr>
                                            </template>
                                            <template v-else>
                                                <tr class="bg-red-300">
                                                    <td colspan="4" class="px-6 py-4 text-sm font-medium text-red-900 whitespace-nowrap">
                                                        You do not have access to any user's keys.
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
        </div>
    </app-layout>
</template>

<script>
    import { defineComponent } from 'vue'
    import AppLayout from '@/Layouts/AppLayout.vue'
    import JetSectionBorder from '@/Jetstream/SectionBorder'
    import CustomNavLink from '@/BuildingBlocks/NavLink'

    export default defineComponent({
        props: [
            'keys', 'sharedKeys', 'adminAccessedKeys', 'myID',
        ],

        components: {
            AppLayout,
            CustomNavLink,
            JetSectionBorder,
        },

        methods: {
            copy(text) {
                var input = document.createElement('textarea')
                document.body.appendChild(input)
                input.value = text
                input.focus()
                input.select()
                document.execCommand('Copy')
                input.remove()
            }
        }
    })
</script>
