<template>
    <jet-form-section @submitted="createRequest">
        <template #title>
            Request Details
        </template>

        <template #description>
            Request access to a user's keys.
        </template>

        <template #form>
            <div class="col-span-6 sm:col-span-4">
                <jet-label for="user_email" value="User Email" />
                <jet-input id="user_email" type="text" class="block w-full mt-1" v-model="form.user_email" autofocus />
                <jet-input-error :message="form.errors.user_email" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-4">
                <jet-label for="purpose" value="Purpose" />
                <jet-input id="purpose" type="text" class="block w-full mt-1" v-model="form.purpose" autofocus />
                <jet-input-error :message="form.errors.purpose" class="mt-2" />
            </div>
        </template>

        <template #actions>
            <jet-action-message :on="form.recentlySuccessful" class="mr-3">
                Created.
            </jet-action-message>

            <jet-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Create
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

        data() {
            return {
                form: this.$inertia.form({
                    admin_id: this.$page.props.user.id,
                    user_email: '',
                    purpose: ''
                })
            }
        },

        methods: {
            createRequest() {
                this.form.post(route('request.store'), {
                    errorBag: 'createRequest',
                    preserveScroll: true
                });
            }
        }
    })
</script>