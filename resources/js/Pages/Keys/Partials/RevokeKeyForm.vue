<template>
    <jet-section-border />
        <jet-form-section @submitted="revokeKey">
            <template #title>
                Revoke Key Access
            </template>

            <template #description>
                Revoke a user's access to this key.
            </template>

            <template #form>
                <div class="col-span-6 sm:col-span-4">
                    <jet-label for="shared_email" value="Email" />
                    <jet-input id="shared_email" type="text" class="block w-full mt-1" v-model="form.shared_email" autofocus />
                    <jet-input-error :message="form.errors.shared_email" class="mt-2" />
                </div>
            </template>

            <template #actions>
                <jet-action-message :on="form.recentlySuccessful" class="mr-3">
                    Revoked.
                </jet-action-message>

                <jet-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Revoke Access
                </jet-button>
            </template>
        </jet-form-section>
</template>

<script>
    import { defineComponent } from 'vue'
    import JetActionMessage from '@/Jetstream/ActionMessage'
    import JetButton from '@/Jetstream/Button.vue'
    import JetFormSection from '@/Jetstream/FormSection.vue'
    import JetInput from '@/Jetstream/Input.vue'
    import JetInputError from '@/Jetstream/InputError.vue'
    import JetLabel from '@/Jetstream/Label.vue'
    import JetSectionBorder from '@/Jetstream/SectionBorder.vue'
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
            JetSectionBorder,
        },

        props: ['skey'],

        data() {
            return {
                form: this.$inertia.form({
                    shared_email: '',
                })
            }
        },

        methods: {
            revokeKey() {
                this.form.post(route('key.destroy', this.skey), {
                    errorBag: 'revokeKey',
                    preserveScroll: true
                });
            },
        },
    })
</script>