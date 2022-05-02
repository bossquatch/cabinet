<template>
    <jet-form-section @submitted="createDisk">
        <template #title>
            Disk Details
        </template>

        <template #description>
            Create a new disk to store and download files.
        </template>

        <template #form>
            <div class="col-span-6 sm:col-span-4">
                <jet-label for="name" value="Disk Name" />
                <jet-input id="name" type="text" class="block w-full mt-1" v-model="form.name" autofocus />
                <jet-input-error :message="form.errors.name" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-4">
                <jet-label for="driver" value="Driver" />
                <custom-select id="driver" v-model="form.driver_id" class="md:w-1/2">
                    <option value="">(Select a driver)</option>
                    <option v-for="driver in drivers" :key="driver.id" :selected="driver.id == form.driver_id" :value="driver.id">{{ driver.display_name }}</option>
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
            <jet-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Create
            </jet-button>
        </template>
    </jet-form-section>
</template>

<script>
    import { defineComponent } from 'vue'
    import JetButton from '@/Jetstream/Button.vue'
    import JetFormSection from '@/Jetstream/FormSection.vue'
    import JetInput from '@/Jetstream/Input.vue'
    import JetInputError from '@/Jetstream/InputError.vue'
    import JetLabel from '@/Jetstream/Label.vue'
    import CustomCheckbox from '@/BuildingBlocks/Checkbox.vue'
    import CustomSelect from '@/BuildingBlocks/Select.vue'

    export default defineComponent({
        props: ['drivers', 'backup_disks', 'templates'],

        components: {
            JetButton,
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
                    name: '',
                    driver_id: null,
                    backup_id: null,
                    template_id: null,
                    private: 0,
                    is_template: 0,
                    team_id: this.$page.props.user.current_team_id,
                    encode_files: 1,
                })
            }
        },

        methods: {
            createDisk() {
                this.form.post(route('disk.store'), {
                    errorBag: 'createDisk',
                    preserveScroll: true
                });
            },
        },
    })
</script>
