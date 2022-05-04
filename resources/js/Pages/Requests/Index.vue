<template>
    <app-layout title="Key Access Requests">
        <template #header>
            <div class="md:flex md:items-center md:justify-between">
                <div class="flex-1 min-w-0">
                    <h2 class="text-xl font-semibold leading-tight text-gray-800">
                        Key Access Requests
                    </h2>
                </div>
                <div class="flex mt-4 md:mt-0 md:ml-4">
                    <custom-nav-link :href="route('request.create')" classes="inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
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
                                                Admin
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                                User
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                                Purpose
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                                Status
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template v-if="requests.length">
                                            <tr v-for="(request, index) in requests" :key="request" :class="index % 2 === 0 ? 'bg-white' : 'bg-gray-50'">
                                                    <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">
                                                        {{ request.admin_name }}
                                                    </td>
                                                    <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                                        {{ request.user_name }}
                                                    </td>
                                                    <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                                        {{ request.purpose }}
                                                    </td>
                                                    <td class="flex justify-between px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                                        <div v-if="request.approved" class="text-green-600">
                                                            Approved
                                                        </div>
                                                        <div v-else>
                                                            Pending
                                                        </div>
                                                        <trash-icon v-if="$page.props.user.id == request.admin_id" @click="deleteConfirmation(request)" class="cursor-pointer text-red-600 hover:text-red-900"/>
                                                        <check-icon v-else-if="!request.approved" @click="approveConfirmation(request)" class="cursor-pointer w-6 text-green-600 hover:text-green-900"/>
                                                    </td>
                                                </tr>
                                        </template>
                                        <template v-else>
                                            <tr class="bg-red-300">
                                                <td colspan="4" class="px-6 py-4 text-sm font-medium text-red-900 whitespace-nowrap">
                                                    There are currently no key access requests.
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
            <!-- Approve Request Confirmation Modal -->
            <jet-dialog-modal :show="confirmingApproval" @close="closeApproveModal">
                <template #title>
                    Key Access Request Approval
                </template>

                <template #content>
                    Are you sure you want to approve this request? This admin will have access to this user's keys for 24 hours.
                </template>

                <template #footer>
                    <jet-secondary-button @click="closeApproveModal">
                        Cancel
                    </jet-secondary-button>

                    <jet-button class="ml-2 bg-green" @click="approveRequest" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Approve
                    </jet-button>
                </template>
            </jet-dialog-modal>

            <!-- Delete Request Confirmation Modal -->
            <jet-dialog-modal :show="confirmingDeletion" @close="closeDeleteModal">
                <template #title>
                    Delete Key Access Request
                </template>

                <template #content>
                    Are you sure you want to delete your request?
                </template>

                <template #footer>
                    <jet-secondary-button @click="closeDeleteModal">
                        Cancel
                    </jet-secondary-button>

                    <jet-danger-button class="ml-2" @click="deleteRequest" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Delete Request
                    </jet-danger-button>
                </template>
            </jet-dialog-modal>
        </div>
    </app-layout>
</template>

<script>
    import { defineComponent, useSSRContext } from 'vue'
    import AppLayout from '@/Layouts/AppLayout.vue'
    import JetSectionBorder from '@/Jetstream/SectionBorder'
    import CustomNavLink from '@/BuildingBlocks/NavLink'
    import JetButton from '@/Jetstream/Button.vue'
    import JetDialogModal from '@/Jetstream/DialogModal.vue'
    import JetSecondaryButton from '@/Jetstream/SecondaryButton.vue'
    import JetDangerButton from '@/Jetstream/DangerButton.vue'
    import TrashIcon from '@/HeroIcons/Trash.vue'
    import CheckIcon from '@/HeroIcons/Check.vue'

    export default defineComponent({
        props: ['requests'],

        components: {
            AppLayout,
            CustomNavLink,
            JetSectionBorder,
            JetButton,
            JetDialogModal,
            JetSecondaryButton,
            JetDangerButton,
            TrashIcon,
            CheckIcon
        },

        data() {
            return {
                form: this.$inertia.form({
                    approved: true,
                    approved_at: ''
                }),
                confirmingApproval: false,
                confirmingDeletion: false,
                currentRequest: ''
            }
        },

        methods: {
            approveConfirmation(request)
            {
                this.confirmingApproval = true
                this.currentRequest = request
            },

            deleteConfirmation(request) {
                this.confirmingDeletion = true
                this.currentRequest = request
            },

            approveRequest() {
                this.form.put(route('request.approveRequest', this.currentRequest), {
                    errorBag: 'approveRequest',
                    preserveScroll: true,
                    onSuccess: () => this.closeApproveModal()
                })
            },

            deleteRequest() {
                this.form.delete(route('request.delete', this.currentRequest), {
                    preserveScroll: true,
                    onSuccess: () => this.closeDeleteModal()
                })
            },

            closeApproveModal() {
                this.confirmingApproval = false
            },

            closeDeleteModal() {
                this.confirmingDeletion = false
            }
        }
    })
</script>
