<template>
    <jet-form-section @submitted="createDriver">
        <template #title>
            Driver Details
        </template>

        <template #description>
            Create a new driver to collaborate with others on projects.
        </template>

        <template #form>
            <div class="col-span-6 sm:col-span-4">
                <jet-label for="name" value="Driver Name" />
                <jet-input id="name" type="text" class="block w-full mt-1" v-model="form.name" autofocus />
                <jet-input-error :message="form.errors.name" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-4">
                <jet-label for="display_name" value="Display Name" />
                <jet-input id="display_name" type="text" class="block w-full mt-1" v-model="form.display_name" />
                <jet-input-error :message="form.errors.display_name" class="mt-2" />
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

    export default defineComponent({
        components: {
            JetButton,
            JetFormSection,
            JetInput,
            JetInputError,
            JetLabel,
        },

        data() {
            return {
                form: this.$inertia.form({
                    name: '',
                    display_name: ''
                })
            }
        },

        methods: {
            createDriver() {
                this.form.post(route('driver.store'), {
                    errorBag: 'createDriver',
                    preserveScroll: true
                });
            },
        },
    })
</script>
