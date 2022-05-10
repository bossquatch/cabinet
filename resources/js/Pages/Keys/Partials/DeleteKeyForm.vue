<template>
    <jet-section-border />
        <jet-action-section>
            <template #title>
                Delete Key
            </template>

            <template #description>
                Permanently delete your key.
            </template>

            <template #content>
                <div class="max-w-xl text-sm text-gray-600">
                    Once your key is deleted, access to this key for all users will be removed.
                </div>

                <div class="mt-5">
                    <jet-danger-button @click="confirmingKeyDeletion = true">
                        Delete Key
                    </jet-danger-button>
                </div>

                <!-- Delete Key Confirmation Modal -->
                <jet-confirmation-modal :show="confirmingKeyDeletion" @close="confirmingKeyDeletion = false">
                    <template #title>
                        Delete Key
                    </template>

                    <template #content>
                        Are you sure you want to delete your key? Once your key is deleted, access to this key for all users will be removed.
                    </template>

                    <template #footer>
                        <jet-secondary-button @click="confirmingKeyDeletion = false">
                            Cancel
                        </jet-secondary-button>

                        <jet-danger-button class="ml-2" @click="deleteKey" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            Delete
                        </jet-danger-button>
                    </template>
                </jet-confirmation-modal>
            </template>
        </jet-action-section>
</template>

<script>
    import { defineComponent } from 'vue'
    import JetActionSection from '@/Jetstream/ActionSection.vue'
    import JetConfirmationModal from '@/Jetstream/ConfirmationModal.vue'
    import JetDangerButton from '@/Jetstream/DangerButton.vue'
    import JetInput from '@/Jetstream/Input.vue'
    import JetInputError from '@/Jetstream/InputError.vue'
    import JetSecondaryButton from '@/Jetstream/SecondaryButton.vue'
    import JetSectionBorder from '@/Jetstream/SectionBorder.vue'

    export default defineComponent({
        components: {
            JetActionSection,
            JetDangerButton,
            JetConfirmationModal,
            JetInput,
            JetInputError,
            JetSecondaryButton,
            JetSectionBorder,
        },

        props: ['skey'],

        data() {
            return {
                form: this.$inertia.form(),
                confirmingKeyDeletion: false
            }
        },

        methods: {
            deleteKey() {
                this.form.delete(route('key.delete', this.skey), {
                    preserveScroll: true,
                    onSuccess: () => confirmingKeyDeletion = false,
                })
            },
        },
    })
</script>