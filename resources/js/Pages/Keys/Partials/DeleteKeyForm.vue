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
                    <jet-danger-button @click="confirmKeyDeletion">
                        Delete Key
                    </jet-danger-button>
                </div>

                <!-- Delete Key Confirmation Modal -->
                <jet-dialog-modal :show="confirmingKeyDeletion" @close="closeModal">
                    <template #title>
                        Delete Key
                    </template>

                    <template #content>
                        Are you sure you want to delete your key? Once your key is deleted, access to this key for all users will be removed.
                    </template>

                    <template #footer>
                        <jet-secondary-button @click="closeModal">
                            Cancel
                        </jet-secondary-button>

                        <jet-danger-button class="ml-2" @click="deleteKey" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            Delete Key
                        </jet-danger-button>
                    </template>
                </jet-dialog-modal>
            </template>
        </jet-action-section>
</template>

<script>
    import { defineComponent } from 'vue'
    import JetActionSection from '@/Jetstream/ActionSection.vue'
    import JetDialogModal from '@/Jetstream/DialogModal.vue'
    import JetDangerButton from '@/Jetstream/DangerButton.vue'
    import JetInput from '@/Jetstream/Input.vue'
    import JetInputError from '@/Jetstream/InputError.vue'
    import JetSecondaryButton from '@/Jetstream/SecondaryButton.vue'
    import JetSectionBorder from '@/Jetstream/SectionBorder.vue'

    export default defineComponent({
        components: {
            JetActionSection,
            JetDangerButton,
            JetDialogModal,
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
            confirmKeyDeletion() {
                this.confirmingKeyDeletion = true
            },

            deleteKey() {
                this.form.delete(route('key.delete', this.skey), {
                    preserveScroll: true,
                    onSuccess: () => this.closeModal(),
                })
            },

            closeModal() {
                this.confirmingKeyDeletion = false
            },
        },
    })
</script>