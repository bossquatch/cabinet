<template>
    <div>
        <div>
            <jet-section-border />

            <!-- Add Driver Field -->
            <jet-form-section @submitted="addDriverField">
                <template #title>
                    Add Driver Field
                </template>

                <template #description>
                    Add a new driver field to your driver, allowing them to collaborate with you.
                </template>

                <template #form>
                    <div class="col-span-6">
                        <div class="max-w-xl text-sm text-gray-600">
                            Please provide a rule with requirements that you would like to add to this driver.
                        </div>
                    </div>

                    <!-- Field Name -->
                    <div class="col-span-6 sm:col-span-4">
                        <jet-label for="field_name" value="Field Name" />
                        <jet-input id="field_name" type="text" class="block w-full mt-1" v-model="addDriverFieldForm.name" />
                        <jet-input-error :message="addDriverFieldForm.errors.name" class="mt-2" />
                    </div>

                    <!-- Field Display Name -->
                    <div class="col-span-6 sm:col-span-4">
                        <jet-label for="field_display_name" value="Display Name" />
                        <jet-input id="field_display_name" type="text" class="block w-full mt-1" v-model="addDriverFieldForm.display_name" />
                        <jet-input-error :message="addDriverFieldForm.errors.display_name" class="mt-2" />
                    </div>

                    <!-- Field Settings -->
                    <div class="col-span-6 lg:col-span-4">
                        <custom-checkbox :name="'Required'" v-model="addDriverFieldForm.required" />
                        <custom-checkbox :name="'Encrypt Entry*'" v-model="addDriverFieldForm.encrypt" />
                        <custom-checkbox :name="'Is File*'" v-model="addDriverFieldForm.is_file" />
                    </div>

                    <div class="col-span-6 lg:col-span-4">
                        <div class="max-w-xl text-xs text-gray-600">
                            * Encrypt option only works on non-file inputs.  If both options are marked, it will save as an unencrypted file.
                        </div>
                    </div>
                </template>

                <template #actions>
                    <jet-action-message :on="addDriverFieldForm.recentlySuccessful" class="mr-3">
                        Added.
                    </jet-action-message>

                    <jet-button :class="{ 'opacity-25': addDriverFieldForm.processing }" :disabled="addDriverFieldForm.processing">
                        Add
                    </jet-button>
                </template>
            </jet-form-section>
        </div>

        <div v-if="driver.driver_fields.length > 0">
            <jet-section-border />

            <jet-action-section class="mt-10 sm:mt-0">
                <template #title>
                    Driver Fields
                </template>

                <template #description>
                    These are the fields that need to be inserted to create a disk using this driver.
                </template>

                <template #content>
                    <div class="space-y-6">
                        <div class="flex items-center justify-between" v-for="field in driver.driver_fields" :key="field.id">
                            <div class="text-gray-600">{{ field.display_name }} <small>({{ field.name }})</small></div>

                            <div class="flex flex-shrink-0 ml-2">
                                <p v-if="field.required" class="inline-flex px-2 ml-2 text-xs font-semibold leading-5 text-green-800 bg-green-100 rounded-full">
                                    Required
                                </p>
                                <p v-if="field.encrypt && !field.is_file" class="inline-flex px-2 ml-2 text-xs font-semibold leading-5 text-yellow-800 bg-yellow-100 rounded-full">
                                    Encrypt
                                </p>
                                <p v-if="field.is_file" class="inline-flex px-2 ml-2 text-xs font-semibold leading-5 text-blue-800 bg-blue-100 rounded-full">
                                    File Input
                                </p>
                            </div>


                            <!--<div class="flex items-center">
                                <button class="ml-6 text-sm text-red-500 cursor-pointer focus:outline-none"
                                                    @click="cancelDriverInvitation(field)"
                                                    v-if="userPermissions.canRemoveDriverFields">
                                    Cancel
                                </button>
                            </div>-->
                        </div>
                    </div>
                </template>
            </jet-action-section>
        </div>
    </div>
</template>

<script>
    import { defineComponent } from 'vue'
    import JetActionMessage from '@/Jetstream/ActionMessage.vue'
    import JetActionSection from '@/Jetstream/ActionSection.vue'
    import JetButton from '@/Jetstream/Button.vue'
    import JetConfirmationModal from '@/Jetstream/ConfirmationModal.vue'
    import JetDangerButton from '@/Jetstream/DangerButton.vue'
    import JetDialogModal from '@/Jetstream/DialogModal.vue'
    import JetFormSection from '@/Jetstream/FormSection.vue'
    import JetInput from '@/Jetstream/Input.vue'
    import JetInputError from '@/Jetstream/InputError.vue'
    import JetLabel from '@/Jetstream/Label.vue'
    import JetSecondaryButton from '@/Jetstream/SecondaryButton.vue'
    import JetSectionBorder from '@/Jetstream/SectionBorder.vue'
    import CustomCheckbox from '@/BuildingBlocks/Checkbox.vue'

    export default defineComponent({
        components: {
            CustomCheckbox,
            JetActionMessage,
            JetActionSection,
            JetButton,
            JetConfirmationModal,
            JetDangerButton,
            JetDialogModal,
            JetFormSection,
            JetInput,
            JetInputError,
            JetLabel,
            JetSecondaryButton,
            JetSectionBorder,
        },

        props: [
            'driver',
        ],

        data() {
            return {
                addDriverFieldForm: this.$inertia.form({
                    name: '',
                    display_name: '',
                    encrypt: 0,
                    is_file: 0,
                    required: 1,
                }),

                updateRoleForm: this.$inertia.form({
                    role: null,
                }),

                currentlyManagingRole: false,
                managingRoleFor: null,
            }
        },

        methods: {
            addDriverField() {
                this.addDriverFieldForm.post(route('driver-fields.store', this.driver), {
                    errorBag: 'addDriverField',
                    preserveScroll: true,
                    onSuccess: () => this.addDriverFieldForm.reset(),
                });
            },

            cancelDriverInvitation(invitation) {
                this.$inertia.delete(route('driver-invitations.destroy', invitation), {
                    preserveScroll: true
                });
            },

            manageRole(driverField) {
                this.managingRoleFor = driverField
                this.updateRoleForm.role = driverField.fieldship.role
                this.currentlyManagingRole = true
            },

            updateRole() {
                this.updateRoleForm.put(route('driver-fields.update', [this.driver, this.managingRoleFor]), {
                    preserveScroll: true,
                    onSuccess: () => (this.currentlyManagingRole = false),
                })
            },
        },
    })
</script>
