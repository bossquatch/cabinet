<template>
    <div v-if="shared_users.length > 0">
        <jet-section-border />
            <jet-action-section>
                <template #title>
                    Revoke Key Access
                </template>

                <template #description>
                    Revoke a user's access to this key.
                </template>

                <template #content>
                    <div class="space-y-6">
                        <div class="flex items-center justify-between" v-for="user in shared_users" :key="user.id">
                            <div class="flex items-center">
                                <img class="w-8 h-8 rounded-full" :src="user.profile_photo_url" :alt="user.name">
                                <div class="w-max ml-4">{{ user.name }}</div>
                            </div>
                            <div class="flex items-center">
                                <button class="cursor-pointer ml-6 text-sm text-red-500" @click="userBeingRemoved = user">
                                    Revoke
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </jet-action-section>

        <!-- Revoke Key Access Confirmation Modal -->
        <jet-confirmation-modal :show="userBeingRemoved" @close="userBeingRemoved = null">
            <template #title>
                Revoke User's Key Access
            </template>

            <template #content>
                Are you sure you would like to revoke this user's key access?
            </template>

            <template #footer>
                <jet-secondary-button @click="userBeingRemoved = null">
                    Cancel
                </jet-secondary-button>

                <jet-danger-button class="ml-2" @click="revokeKey" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Revoke
                </jet-danger-button>
            </template>
        </jet-confirmation-modal>
    </div>
</template>

<script>
    import { defineComponent } from 'vue'
    import JetActionMessage from '@/Jetstream/ActionMessage'
    import JetActionSection from '@/Jetstream/ActionSection.vue'
    import JetConfirmationModal from '@/Jetstream/ConfirmationModal.vue'
    import JetButton from '@/Jetstream/Button.vue'
    import JetSecondaryButton from '@/Jetstream/SecondaryButton.vue'
    import JetDangerButton from '@/Jetstream/DangerButton.vue'
    import JetFormSection from '@/Jetstream/FormSection.vue'
    import JetInput from '@/Jetstream/Input.vue'
    import JetInputError from '@/Jetstream/InputError.vue'
    import JetLabel from '@/Jetstream/Label.vue'
    import JetSectionBorder from '@/Jetstream/SectionBorder.vue'
    import CustomCheckbox from '@/BuildingBlocks/Checkbox.vue'
    import CustomSelect from '@/BuildingBlocks/Select.vue'

    export default defineComponent({
        components: {
            JetActionMessage,
            JetActionSection,
            JetConfirmationModal,
            JetButton,
            JetSecondaryButton,
            JetDangerButton,
            JetFormSection,
            JetInput,
            JetInputError,
            JetLabel,
            CustomCheckbox,
            CustomSelect,
            JetSectionBorder,
        },

        props: ['skey', 'shared_users'],

        data() {
            return {
                form: this.$inertia.form(),
                userBeingRemoved: null,
            }
        },

        methods: {
            revokeKey(user) {
                this.form.delete(route('key.revoke', [this.skey, this.userBeingRemoved]), {
                    errorBag: 'revokeKey',
                    preserveScroll: true,
                    preserveState: true,
                    onSuccess: () => this.userBeingRemoved = null,
                });
            },
        },
    })
</script>