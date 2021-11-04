<template>
    <jet-form-section @submitted="updateDriverName">
        <template #title>
            Driver Name
        </template>

        <template #description>
            The driver's true and display names.
        </template>

        <template #form>
            <!-- Driver Name -->
            <div class="col-span-6 sm:col-span-4">
                <jet-label for="name" value="Driver Name" />

                <jet-input id="name"
                            type="text"
                            class="block w-full mt-1"
                            v-model="form.name" />

                <jet-input-error :message="form.errors.name" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-4">
                <jet-label for="display_name" value="Display Name" />
                
                <jet-input id="display_name" 
                            type="text" 
                            class="block w-full mt-1" v-model="form.display_name" />
                            
                <jet-input-error :message="form.errors.display_name" class="mt-2" />
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

    export default defineComponent({
        components: {
            JetActionMessage,
            JetButton,
            JetFormSection,
            JetInput,
            JetInputError,
            JetLabel,
        },

        props: ['driver'],

        data() {
            return {
                form: this.$inertia.form({
                    name: this.driver.name,
                    display_name: this.driver.display_name,
                })
            }
        },

        methods: {
            updateDriverName() {
                this.form.put(route('driver.update', this.driver), {
                    errorBag: 'updateDriverName',
                    preserveScroll: true
                });
            },
        },
    })
</script>
