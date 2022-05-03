<template>
    <jet-form-section @submitted="updateDiskName">
        <template #title>
            Disk Name
        </template>

        <template #description>
            The disk's primary settings.
        </template>

        <template #form>
            <!-- Disk Name -->
            <div class="col-span-6 sm:col-span-4">
                <jet-label for="name" value="Disk Name" />

                <jet-input id="name"
                            type="text"
                            class="block w-full mt-1"
                            v-model="form.name" />

                <jet-input-error :message="form.errors.name" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-4">
                <jet-label for="driver" value="Driver" />
                <custom-select id="driver" class="bg-gray-200 cursor-not-allowed md:w-1/2" disabled>
                    <option>{{ disk.driver.display_name }}</option>
                </custom-select>
                <jet-input-error :message="form.errors.driver_id" />
            </div>

            <div class="col-span-6 sm:col-span-4">
                <jet-label for="template" value="Template" />
                <custom-select id="template" v-model="form.template_id" class="md:w-1/2">
                    <option value="">No template</option>
                    <option v-for="template in templates" :key="template.id" :selected="template.id == form.template_id" :value="template.id">{{ template.name }}</option>
                </custom-select>
                <jet-input-error :message="form.errors.template_id" />
            </div>

            <div class="col-span-6 sm:col-span-4">
                <jet-label for="backup_disk" value="Backup Disk" />
                <custom-select id="backup_disk" v-model="form.backup_id" class="md:w-1/2">
                    <option value="">No backup disk</option>
                    <option v-for="backup_disk in backup_disks" :key="backup_disk.id" :selected="backup_disk.id == form.backup_id" :value="backup_disk.id">{{ backup_disk.name }}</option>
                </custom-select>
                <jet-input-error :message="form.errors.backup_id" />
            </div>

            <div class="col-span-6 sm:col-span-4">
                <custom-checkbox :name="'Is Template'" v-model="form.is_template" />
                <custom-checkbox :name="'Private*'" v-model="form.private" />
                <custom-checkbox :name="'Encode Files'" v-model="form.encode_files" />
            </div>

            <div class="col-span-6 lg:col-span-4">
                <div class="max-w-xl text-xs text-gray-600">
                    * Private disks can only be uploaded to by your team.  Public disks can have files sent to them, but only your team can download from them.
                </div>
            </div>
        </template>

        <template #actions>
            <jet-action-message :on="form.recentlySuccessful" class="mr-3">
                Saved.
            </jet-action-message>

            <jet-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Save
            </jet-button>
        </template>
    </jet-form-section>
</template>

<script>
    import { defineComponent } from 'vue'
    import JetActionMessage from '@/Jetstream/ActionMessage'
    import JetButton from '@/Jetstream/Button'
    import JetFormSection from '@/Jetstream/FormSection'
    import JetInput from '@/Jetstream/Input'
    import JetInputError from '@/Jetstream/InputError'
    import JetLabel from '@/Jetstream/Label'
    import CustomCheckbox from '@/BuildingBlocks/Checkbox.vue'
    import CustomSelect from '@/BuildingBlocks/Select.vue'

    export default defineComponent({
        components: {
            JetActionMessage,
            JetButton,
            JetFormSection,
            JetInput,
            JetInputError,
            JetLabel,
            CustomCheckbox,
            CustomSelect,
        },

        props: ['disk', 'backup_disks', 'templates'],

        data() {
            return {
                form: this.$inertia.form({
                    name: this.disk.name,
                    private: this.disk.private,
                    is_template: this.disk.is_template,
                    encode_files: this.disk.encode_files,
                    backup_id: this.disk.backup_id,
                    template_id: this.disk.template_id,
                })
            }
        },

        methods: {
            updateDiskName() {
                this.form.put(route('disk.update', this.disk), {
                    errorBag: 'updateDiskName',
                    preserveScroll: true,
                    onSuccess: () => {
                        this.form.clearErrors()
                        this.$inertia.reload()
                    },
                });
            },
        },
    })
</script>
