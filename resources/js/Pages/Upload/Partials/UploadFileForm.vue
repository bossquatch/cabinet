<template>
    <jet-form-section @submitted="uploadFile">
        <template #title>
            File
        </template>

        <template #description>
            Select a file and a disk to upload the file to.
        </template>

        <template #form>
            <div v-if="!form.processing && display_success" class="col-span-6">
                <div class="max-w-xl p-4 text-sm text-green-600 bg-green-100 border-green-600 rounded-md border-1">
                    File successfully uploaded!
                </div>
            </div>

            <div class="col-span-6 sm:col-span-4">
                <jet-label for="disk" value="Disk" />
                <custom-select id="disk" v-model="form.disk_id" class="md:w-1/2">
                    <option value="">(Select a disk)</option>
                    <option v-for="disk in disks" :key="disk.id" :selected="disk.id == form.disk_id" :value="disk.id">{{ disk.name }}</option>
                </custom-select>
                <jet-input-error :message="form.errors.disk_id" />
            </div>

            <div class="col-span-6 sm:col-span-4">
                <jet-label for="file" value="File" />
                <custom-file id="file" name="file" class="block w-full mt-1" v-model="form.file" />
                <jet-input-error :message="form.errors.file" class="mt-2" />
            </div>
        </template>

        <template #actions>
            <jet-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Upload
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
    import CustomSelect from '@/BuildingBlocks/Select.vue'
    import CustomFile from '@/BuildingBlocks/FileInput.vue'

    export default defineComponent({
        props: ['disks'],

        components: {
            JetButton,
            JetFormSection,
            JetInput,
            JetInputError,
            JetLabel,
            CustomFile,
            CustomSelect,
        },

        data() {
            return {
                display_success: false,
                form: this.$inertia.form({
                    disk_id: null,
                    file: null,
                })
            }
        },

        methods: {
            uploadFile() {
                this.form.post(route('upload.file'), {
                    errorBag: 'uploadFile',
                    preserveScroll: true,
                    onSuccess: () => {
                        this.form.reset();
                        this.display_success = true;
                    },
                    onError: () => (this.display_success = false),
                });
            },
        },
    })
</script>
