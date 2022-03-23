<template>
    <jet-form-section @submitted="updateKey">
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

        props: ['skey'],

        data() {
            return {
                form: this.$inertia.form({
                    description: this.skey.description,
                    value: this.skey.value,
                    public: this.skey.public,
                })
            }
        },

        methods: {
            updateKey() {
                this.form.put(route('key.update', this.skey), {
                    errorBag: 'updateKey',
                    preserveScroll: true
                });
            },
        },
    })
</script>