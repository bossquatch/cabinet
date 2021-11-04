<template>
    <div>
        <div>
            <jet-section-border />

            <!-- Update Disk Field -->
            <jet-form-section @submitted="updateDiskField">
                <template #title>
                    Update Disk Field
                </template>

                <template #description>
                    Update required fields in regards to the driver.
                </template>

                <template #form>
                    <div class="col-span-6">
                        <div class="max-w-xl text-sm text-gray-600">
                            Fill in required driver fields.
                        </div>
                    </div>

                    <div class="col-span-6 sm:col-span-4" v-for="field in fields" :key="field.id">
                        <div class="max-w-xl text-sm text-gray-600">
                            <template v-if="field.is_file === '1' && field.is_filled === '1'">
                                <hero-icon-check class="absolute w-6 h-6 text-green-500" />
                                <jet-label :for="'driver_field_' + field.id" :value="field.name" class="pl-6" />
                            </template>
                            <jet-label v-else :for="'driver_field_' + field.id" :value="field.name" />
                            

                            <!--<custom-file v-if="field.is_file === '1'" :id="'driver_field_' + field.id" class="block w-full mt-1" v-model="updateDiskFieldForm['field_' + field.id]" @change="onFilePicked" />-->
                            <custom-file v-if="field.is_file === '1'" :id="'driver_field_' + field.id" class="block w-full mt-1" v-model="updateDiskFieldForm['field_' + field.id]" />
                            <!--<jet-input v-else-if="field.encrypt === '1'" :id="'driver_field_' + field.id" type="password" class="block w-full mt-1" v-model="updateDiskFieldForm[field.id]" />-->
                            <jet-input v-else :id="'driver_field_' + field.id" type="text" class="block w-full mt-1" v-model="updateDiskFieldForm['field_' + field.id]" autocomplete="off" />
                            
                            <jet-input-error :message="updateDiskFieldForm.errors['field_' + field.id]" class="mt-2" />
                        </div>
                    </div>
                </template>

                <template #actions>
                    <jet-action-message :on="updateDiskFieldForm.recentlySuccessful" class="mr-3">
                        Saved.
                    </jet-action-message>

                    <jet-button :class="{ 'opacity-25': updateDiskFieldForm.processing }" :disabled="updateDiskFieldForm.processing">
                        Save
                    </jet-button>
                </template>
            </jet-form-section>
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
    import CustomFile from '@/BuildingBlocks/FileInput.vue'
    import HeroIconCheck from '@/HeroIcons/Check'

    export default defineComponent({
        components: {
            CustomCheckbox,
            CustomFile,
            HeroIconCheck,
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
            'disk',
            'fields',
        ],

        data() {
            let formFields = {};
            this.fields.forEach((value, index) => {
                formFields["field_" + value.id] = value.value;
                if (value.is_file == "1") {
                    formFields["field_" + value.id] = null;
                }
            });

            return {
                updateDiskFieldForm: this.$inertia.form(formFields),

                updateRoleForm: this.$inertia.form({
                    role: null,
                }),

                currentlyManagingRole: false,
                managingRoleFor: null,
            }
        },

        methods: {
            onFilePicked (event) {
                console.log(event);
                const files = event.target.files;
                let filename = files[0].name;
                const fileReader = new FileReader()
                fileReader.addEventListener('load', () => {
                    this.updateDiskFieldForm = fileReader.result
                });
                fileReader.readAsDataURL(files[0]);
                this.updateDiskFieldForm['file-'] = files[0];
            },
            updateDiskField() {
                this.updateDiskFieldForm.post(route('disk-fields.store', this.disk), {
                    errorBag: 'updateDiskField',
                    preserveScroll: true,
                    forceFormData: true,
                    onSuccess: () => console.log(this.updateDiskFieldForm.errors),
                    onError: () => console.log(this.updateDiskFieldForm.errors),
                });
            },
        },
    })
</script>
