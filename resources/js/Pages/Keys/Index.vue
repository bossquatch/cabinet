<template>
    <app-layout title="Keys">
        <template #header>
            <div class="md:flex md:items-center md:justify-between">
                <div class="flex-1 min-w-0">
                    <h2 class="text-xl font-semibold leading-tight text-gray-800">
                        Keys
                    </h2>
                </div>
                <div class="flex relative items-center mt-4 md:mt-0 md:ml-4">
                    <search-icon class="absolute pl-1" />
                    <input-box v-model="keySearchQuery" placeholder="Search Keys" class="pl-6 px-4 py-2" />
                    <custom-nav-link :href="route('key.create')" classes="inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Create
                    </custom-nav-link>
                </div>
            </div>
        </template>

        <div>
            <key-list :categories="categories" :categoryKeys="categoryKeys" :keys="keys" :searchQuery="keySearchQuery" />

            <div v-if="$page.props.user.is_admin">
                <jet-section-border class="mx-auto mt-5 max-w-7xl sm:px-6 lg:px-8" />

                <div class="mx-auto md:flex md:items-center md:justify-between max-w-7xl sm:px-6 lg:px-8">
                    <div class="flex-1 min-w-0">
                        <h2 class="text-xl font-semibold leading-tight text-gray-800">
                            Admin Accessed Keys
                        </h2>
                    </div>
                    <div class="flex relative items-center mt-4 md:mt-0 md:ml-4">
                        <search-icon class="absolute pl-1" />
                        <input-box v-model="adminKeySearchQuery" placeholder="Search Keys" class="pl-6 px-4 py-2" />
                    </div>
                </div>

                <key-list :categories="''" :categoryKeys="''" :keys="adminAccessedKeys" :searchQuery="adminKeySearchQuery" />
            </div>
        </div>
    </app-layout>
</template>

<script>
    import { defineComponent } from 'vue'
    import AppLayout from '@/Layouts/AppLayout.vue'
    import KeyList from '@/Pages/Keys/Partials/KeyList.vue'
    import JetSectionBorder from '@/Jetstream/SectionBorder'
    import CustomNavLink from '@/BuildingBlocks/NavLink'
    import InputBox from '@/Jetstream/Input'
    import SearchIcon from '@/HeroIcons/Search.vue'

    export default defineComponent({
        props: [ 'categories', 'categoryKeys', 'keys', 'adminAccessedKeys'],

        components: {
            AppLayout,
            KeyList,
            CustomNavLink,
            JetSectionBorder,
            InputBox,
            SearchIcon,
        },

        data () {
            return {
                keySearchQuery: null,
                adminKeySearchQuery: null
            }
        }
    })
</script>
