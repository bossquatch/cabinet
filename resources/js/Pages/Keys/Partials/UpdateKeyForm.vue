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
                <jet-input v-if="$page.props.user.id == skey.owner_id || hasAdminAccess" id="description" type="text" class="block w-full mt-1" v-model="form.description" autofocus />
                <jet-input v-else id="description" type="text" class="block w-full mt-1" v-model="form.description" autofocus readonly />
                <jet-input-error :message="form.errors.description" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-4">
                <jet-label for="value" value="Value" />
                <jet-input v-if="$page.props.user.id == skey.owner_id || hasAdminAccess" id="value" type="text" class="block w-full mt-1" v-model="form.value" autofocus />
                <jet-input v-else id="value" type="text" class="block w-full mt-1" v-model="form.value" autofocus readonly />
                <jet-input-error :message="form.errors.value" />
            </div>

            <div class="col-span-6 sm:col-span-4">
                <custom-checkbox :name="'Public'" v-model="form.public" />
            </div>
        </template>

        <template #actions v-if="$page.props.user.id == skey.owner_id || hasAdminAccess">
            <jet-action-message :on="form.recentlySuccessful" class="mr-3">
                Saved.
            </jet-action-message>

            <jet-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Save
            </jet-button>

            <!-- Public Key Confirmation Modal -->
            <jet-confirmation-modal :show="confirmingKeyUpdate" @close="confirmingKeyUpdate = false">
                <template #title>
                    Public Key
                </template>

                <template #content>
                    Are you sure you want to make your key public? Public keys are accessible by all users.
                </template>

                <template #footer>
                    <jet-secondary-button @click="confirmingKeyUpdate = false">
                        Cancel
                    </jet-secondary-button>

                    <jet-button class="ml-2" @click="updateKey" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Confirm
                    </jet-button>
                </template>
            </jet-confirmation-modal>
        </template>
    </jet-form-section>
</template>

<script>
    import { defineComponent } from 'vue'
    import JetActionMessage from '@/Jetstream/ActionMessage.vue'
    import JetConfirmationModal from '@/Jetstream/ConfirmationModal.vue'
    import JetButton from '@/Jetstream/Button.vue'
    import JetSecondaryButton from '@/Jetstream/SecondaryButton.vue'
    import JetFormSection from '@/Jetstream/FormSection.vue'
    import JetInput from '@/Jetstream/Input.vue'
    import JetInputError from '@/Jetstream/InputError.vue'
    import JetLabel from '@/Jetstream/Label.vue'
    import CustomCheckbox from '@/BuildingBlocks/Checkbox.vue'

    export default defineComponent({
        components: {
            JetActionMessage,
            JetConfirmationModal,
            JetButton,
            JetSecondaryButton,
            JetFormSection,
            JetInput,
            JetInputError,
            JetLabel,
            CustomCheckbox,
        },

        props: ['skey', 'hasAdminAccess'],

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
                    errorBag: "updateKey",
                    preserveScroll: true,
                });
                this.confirmingKeyUpdate = false
            },
        },
    })
</script>