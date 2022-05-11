<template>
    <jet-section-border />
        <jet-form-section @submitted="shareUserKey">
            <template #title>
                Share Key
            </template>

            <template #description>
                Share a key with another user.
            </template>

            <template #form>
                <div class="col-span-6 sm:col-span-4">
                    <jet-label for="shared_email" value="Email" />
                    <jet-input id="shared_email" type="text" class="block w-full mt-1" v-model="userForm.shared_email" autofocus />
                    <jet-input-error :message="userForm.errors.shared_email" class="mt-2" />
                </div>
            </template>

            <template #actions>
                <jet-action-message :on="userForm.recentlySuccessful" class="mr-3">
                    Shared.
                </jet-action-message>

                <jet-button :class="{ 'opacity-25': userForm.processing }" :disabled="userForm.processing">
                    Share
                </jet-button>
            </template>
        </jet-form-section>

        <jet-form-section class="mt-6" @submitted="shareTeamKey">
            <template #description>
                Share a key with your current team.
            </template>

            <template #form>
                <div class="col-span-6 sm:col-span-4">
                    <jet-label for="shared_team" value="Team" />
                    <jet-input id="shared_team" type="text" class="block w-full mt-1 bg-gray-200 cursor-not-allowed" v-model="$page.props.user.current_team.name" autofocus readonly />
                </div>
            </template>

            <template #actions>
                <jet-action-message :on="teamForm.recentlySuccessful" class="mr-3">
                    Shared.
                </jet-action-message>

                <jet-button :class="{ 'opacity-25': teamForm.processing }" :disabled="teamForm.processing">
                    Share
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

    export default defineComponent({
        components: {
            JetActionMessage,
            JetButton,
            JetFormSection,
            JetInput,
            JetInputError,
            JetLabel,
            JetSectionBorder,
        },

        props: ['skey'],

        data() {
            return {
                userForm: this.$inertia.form({
                    key_id: this.skey.id,
                    shared_email: '',
                }),
                teamForm: this.$inertia.form({
                    key_id: this.skey.id,
                    shared_email: '',
                })
            }
        },

        methods: {
            shareUserKey() {
                this.userForm.post(route('key.userShare'), {
                    errorBag: 'shareUserKey',
                    preserveScroll: true
                });
            },

            shareTeamKey() {
                this.teamForm.post(route('key.teamShare', this.$page.props.user.current_team), {
                    errorBag: 'shareTeamKey',
                    preserveScroll: true
                });
            }
        },
    })
</script>