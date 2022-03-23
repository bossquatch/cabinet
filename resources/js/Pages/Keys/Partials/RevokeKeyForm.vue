<template>
    <div v-if="shared_users.length > 0">
        <jet-section-border />
            <jet-form-section @submitted="revokeKey">
                <template #title>
                    Revoke Key Access
                </template>

                <template #description>
                    Revoke a user's access to this key.
                </template>

                <template #form>
                    <div class="space-y-6">
                        <div class="flex items-center justify-between" v-for="user in shared_users" :key="user.id">
                            <div class="flex items-center">
                                <jet-button @click="userBeingRemoved = user" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                    Revoke
                                </jet-button>
                            </div>

                            <div class="flex items-center">
                                <div class="w-max ml-4">
                                    {{ user.name }}
                                </div>
                            </div>

                            <div class="flex items-center">
                                <div class="w-max ml-4">
                                    {{ user.email }}
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </jet-form-section>
    </div>
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

        props: ['skey', 'shared_users'],

        data() {
            return {
                form: this.$inertia.form(),
                userBeingRemoved: null,
            }
        },

        methods: {
            revokeKey(user) {
                this.form.delete(route('key.destroy', [this.skey, this.userBeingRemoved]), {
                    errorBag: 'revokeKey',
                    preserveScroll: true,
                    preserveState: true,
                    onSuccess: () => (this.userBeingRemoved = null),
                });
            },
        },
    })
</script>