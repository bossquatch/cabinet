<template>
    <jet-form-section @submitted="createKey">
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
                <jet-input id="value" type="text" class="block w-full mt-1" v-model="form.value" autofocus/>
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
                    user_id: this.$page.props.user.id,
                    owner_id: this.$page.props.user.id,
                    description: '',
                    value: '',
                    public: 0,
                })
            }
        },

        methods: {
            createKey() {
                this.form.post(route('key.store'), {
                    errorBag: 'createKey',
                    preserveScroll: true
                });
            },
        },
    })
</script>