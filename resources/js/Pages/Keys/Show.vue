<template>
    <app-layout title="Key Settings">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Key Settings
            </h2>
        </template>

        <div>
            <div class="py-10 mx-auto max-w-7xl sm:px-6 lg:px-8">
                <update-key-form :skey="skey" :hasAdminAccess="hasAdminAccess" />

                <update-category-form v-if="$page.props.user.id == skey.owner_id || isSharedKey || skey.public" :skey="skey" :categories="categories" :currentCategory="currentCategory" />
                
                <share-key-form v-if="skey.public == false && ($page.props.user.id == skey.owner_id || hasAdminAccess)" :skey="skey" />

                <revoke-key-form v-if="skey.public == false && ($page.props.user.id == skey.owner_id || hasAdminAccess)" :skey="skey" :shared_users="shared_users" />

                <delete-key-form v-if="$page.props.user.id == skey.owner_id || hasAdminAccess" :skey="skey" />
            </div>
        </div>
    </app-layout>
</template>

<script>
    import { defineComponent } from 'vue'
    import AppLayout from '@/Layouts/AppLayout.vue'
    import JetSectionBorder from '@/Jetstream/SectionBorder.vue'
    import UpdateKeyForm from '@/Pages/Keys/Partials/UpdateKeyForm.vue'
    import UpdateCategoryForm from '@/Pages/Keys/Partials/UpdateCategoryForm.vue'
    import ShareKeyForm from '@/Pages/Keys/Partials/ShareKeyForm.vue'
    import RevokeKeyForm from '@/Pages/Keys/Partials/RevokeKeyForm.vue'
    import DeleteKeyForm from '@/Pages/Keys/Partials/DeleteKeyForm.vue'

    export default defineComponent({
        props: ['skey', 'shared_users', 'categories', 'currentCategory', 'hasAdminAccess', 'isSharedKey'],

        components: {
            AppLayout,
            JetSectionBorder,
            UpdateKeyForm,
            UpdateCategoryForm,
            ShareKeyForm,
            RevokeKeyForm,
            DeleteKeyForm,
        },
    })
</script>