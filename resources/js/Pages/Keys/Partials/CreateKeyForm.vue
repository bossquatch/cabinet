<template>
    <jet-form-section @submitted="checkPublic">
        <template #title>
            Key Details
        </template>

        <template #description>
            Create a new key.
        </template>

        <template #form>
            <div class="col-span-6 sm:col-span-4">
                <jet-label for="description" value="Key Description" />
                <jet-input id="description" type="text" class="block w-full mt-1" v-model="form.description" autofocus />
                <jet-input-error :message="form.errors.description" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-4">
                <jet-label for="value" value="Value" />
                <div class="flex">
                    <jet-input id="value" type="text" class="block w-full mt-1" v-model="form.value" autofocus/>
                    <jet-button type="button" @click="generatePassword" class="ml-2">Generate Password</jet-button>
                </div>
                <jet-input-error :message="form.errors.value" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-4">
                <custom-checkbox :name="'Public'" v-model="form.public" />
            </div>
        </template>

        <template #actions>
            <jet-action-message :on="form.recentlySuccessful" class="mr-3">
                Created.
            </jet-action-message>

            <jet-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Create
            </jet-button>

            <!-- Public Key Confirmation Modal -->
            <jet-dialog-modal :show="confirmingKeyCreation" @close="closeModal">
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

                    <jet-button class="ml-2" @click="createKey" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
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
    import JetButton from '@/Jetstream/Button.vue'
    import JetSecondaryButton from '@/Jetstream/SecondaryButton.vue'
    import JetFormSection from '@/Jetstream/FormSection.vue'
    import JetInput from '@/Jetstream/Input.vue'
    import JetInputError from '@/Jetstream/InputError.vue'
    import JetLabel from '@/Jetstream/Label.vue'
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

        data() {
            return {
                form: this.$inertia.form({
                    user_id: this.$page.props.user.id,
                    owner_id: this.$page.props.user.id,
                    description: '',
                    value: '',
                    public: 0,
                }),
                confirmingKeyCreation: false
            }
        },

        methods: {
            generatePassword() {
                let password = ""
                let characters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!\"#$%&'()*+,-./:;<=>?@[\\]^_`{|}~ "
                let passwordLength = Math.floor(Math.random() * 100)

                for (let i = 0; i < passwordLength; i++)
                {
                    password += characters.charAt(Math.floor(Math.random() * characters.length))
                }

                this.form.value = password
            },

            checkPublic() {
                if (this.form.public)
                {
                    this.confirmingKeyCreation = true
                }
                else
                {
                    this.createKey()
                }
            },

            createKey() {
                this.form.post(route('key.store'), {
                    errorBag: 'createKey',
                    preserveScroll: true
                });
            },

            closeModal() {
                this.confirmingKeyCreation = false
            },
        },
    })
</script>