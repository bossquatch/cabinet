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
                                <template v-if="keys.length && keyQuery.length">
                                    <tr v-for="(key, index) in keyQuery" :key="key" :class="index % 2 === 0 ? 'bg-white' : 'bg-gray-50'">
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">
                                            {{ key.description }}
                                        </td>
                                        <td class="flex px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                            <div :class="hiddenKeys[index]===false ? 'max-w-2xl truncate' : 'blur-sm max-w-2xl truncate'">
                                                {{ key.value }}
                                            </div>
                                            <div v-if="hiddenKeys[index]===false" class="cursor-pointer ml-4 text-indigo-600 hover:text-indigo-900" @click="hiddenKeys[index]=true">
                                                <show-icon></show-icon>
                                            </div>
                                            <div v-else class="cursor-pointer ml-4 text-indigo-600 hover:text-indigo-900" @click="hiddenKeys[index]=false">
                                                <hide-icon></hide-icon>
                                            </div>
                                            <clipboard-copy-icon class="cursor-pointer ml-4 text-indigo-600 hover:text-indigo-900" @click="copy(key.value)"/>
                                            <jet-action-message :on="currentClipboard == key.value" class="ml-2">
                                                Copied.
                                            </jet-action-message>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                            {{ key.public ? "Yes" : "No" }}
                                        </td>
                                        <td class="flex px-6 py-4 text-sm font-medium text-right place-content-end whitespace-nowrap">
                                            <custom-nav-link v-if="$page.props.user.id == key.owner_id || hasAdminAccess" :href="key.edit_url" class="text-indigo-600 hover:text-indigo-900">
                                                <edit-icon />
                                            </custom-nav-link>
                                        </td>
                                    </tr>
                                </template>
                                <template v-else-if="keys.length && !keyQuery.length">
                                    <tr class="bg-red-300">
                                        <td colspan="4" class="px-6 py-4 text-sm font-medium text-red-900 whitespace-nowrap">
                                            No keys found.
                                        </td>
                                    </tr>
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
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { defineComponent } from 'vue'
    import JetSectionBorder from '@/Jetstream/SectionBorder'
    import CustomNavLink from '@/BuildingBlocks/NavLink'
    import JetActionMessage from '@/Jetstream/ActionMessage'
    import ClipboardCopyIcon from '@/HeroIcons/ClipboardCopy.vue'
    import EditIcon from '@/HeroIcons/Edit.vue'
    import ShowIcon from '@/HeroIcons/Show.vue'
    import HideIcon from '@/HeroIcons/Hide.vue'

    export default defineComponent({
        props: [
            'keys', 'searchQuery', 'hasAdminAccess'
        ],

        components: {
            CustomNavLink,
            JetSectionBorder,
            JetActionMessage,
            ClipboardCopyIcon,
            EditIcon,
            ShowIcon,
            HideIcon,
        },

        data() {
            return {
                currentClipboard: "",
                hiddenKeys: [].fill(false)
            }
        },

        computed: {
            keyQuery() {
                if (this.searchQuery)
                {
                    this.hiddenKeys.forEach(function(part, index, arr){
                        arr[index] = true
                    })
                    return this.keys.filter(item => {
                        return this.searchQuery
                            .toLowerCase()
                            .split(" ")
                            .every(v => item.description.toLowerCase().includes(v))
                    })
                }
                else
                {
                    return this.keys
                }
            }
        },

        methods: {
            copy(text) {
                var input = document.createElement('textarea')
                document.body.appendChild(input)
                input.value = text
                input.select()
                document.execCommand('Copy')
                input.remove()

                this.currentClipboard = text
                setTimeout(() => {
                    this.currentClipboard = ""
                }, 500)
            }
        }
    })
</script>