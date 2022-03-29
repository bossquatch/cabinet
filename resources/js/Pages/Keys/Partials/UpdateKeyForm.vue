<template>
    <jet-form-section @submitted="checkPublic">
        <template #title>
            Key Description
        </template>

        <template #description>
            The key's primary settings.
        </template>

        <template #form>
            <div class="col-span-6 sm:col-span-4">
                <jet-label for="description" value="Key Description" />

                <jet-input id="description"
                            type="text"
                            class="block w-full mt-1"
                            v-model="form.description" />

                <jet-input-error :message="form.errors.description" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-4">
                <jet-label for="value" value="Value" />

                <jet-input id="value"
                            type="text"
                            class="block w-full mt-1"
                            v-model="form.value" />

                <jet-input-error :message="form.errors.value" />
            </div>

            <div class="col-span-6 sm:col-span-4">
                <custom-checkbox :name="'Public'" v-model="form.public" />
            </div>
        </template>

        <template #actions>
            <jet-action-message :on="form.recentlySuccessful" class="mr-3">
                Saved.
            </jet-action-message>

            <jet-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Save
            </jet-button>

            <!-- Public Key Confirmation Modal -->
            <jet-dialog-modal :show="confirmingKeyUpdate" @close="closeModal">
                <template #title>
                    Public Key
                </template>

                <template #content>
                    Are you sure you want to make your key public? Public keys are accessible by all users.
                </template>

                <template #footer>
                    <jet-secondary-button @click="closeModal">
                        Cancel
                    </jet-secondary-button>

                    <jet-button class="ml-2" @click="updateKey" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Confirm
                    </jet-button>
                </template>
            </jet-dialog-modal>
        </template>
    </jet-form-section>
</template>

<script>
    import { defineComponent } from 'vue'
    import JetActionMessage from '@/Jetstream/ActionMessage'
    import JetDialogModal from '@/Jetstream/DialogModal.vue'
    import JetButton from '@/Jetstream/Button'
    import JetSecondaryButton from '@/Jetstream/SecondaryButton.vue'
    import JetFormSection from '@/Jetstream/FormSection'
    import JetInput from '@/Jetstream/Input'
    import JetInputError from '@/Jetstream/InputError'
    import JetLabel from '@/Jetstream/Label'
    import CustomCheckbox from '@/BuildingBlocks/Checkbox.vue'
    import CustomSelect from '@/BuildingBlocks/Select.vue'

    export default defineComponent({
        components: {
            JetActionMessage,
            JetDialogModal,
            JetButton,
            JetSecondaryButton,
            JetFormSection,
            JetInput,
            JetInputError,
            JetLabel,
            CustomCheckbox,
            CustomSelect,
        },

        props: ['skey'],

        data() {
            return {
                form: this.$inertia.form({
                    description: this.skey.description,
                    value: this.skey.value,
                    public: this.skey.public,
                }),
                confirmingKeyUpdate: false
            }
        },

        methods: {
            checkPublic() {
                if (this.form.public)
                {
                    this.confirmingKeyUpdate = true
                }
                else
                {
                    this.updateKey()
                }
            },

            updateKey() {
                this.form.put(route('key.update', this.skey), {
                    errorBag: 'updateKey',
                    preserveScroll: true
                });
                this.closeModal()
            },

            closeModal() {
                this.confirmingKeyUpdate = false
            },
        },
    })
</script>