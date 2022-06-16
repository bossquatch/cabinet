<template>
    <div v-if="$page.props.user.id == skey.user_id">
        <jet-section-border />
        <jet-action-section>
            <template #title>
                Update Category
            </template>

            <template #description>
                Change this key's category or remove it from its current category.
            </template>

            <template #content>
                <div class="space-y-6">
                    <div class="flex items-center justify-between">
                        <div class="w-max ml-4">
                            <jet-label for="name" value="New Category" />
                            <jet-input id="name" type="text" class="block w-full mt-1" v-model="form.name" autofocus />
                            <jet-input-error :message="form.errors.name" class="mt-2" />
                        </div>
                        <jet-button class="self-end" @click="newCategory(form.name)">
                            Change
                        </jet-button>
                    </div>
                    <div class="flex items-center justify-between" v-for="category in categories" :key="category">
                        <div class="flex items-center">
                            <div class="w-max ml-4">{{ category.name }}</div>
                        </div>
                        <div class="flex items-center">
                            <div v-if="currentCategory !== null">
                                <jet-danger-button v-if="currentCategory.name === category.name" @click="removeCategory">
                                    Remove
                                </jet-danger-button>
                                <jet-button v-else @click="changeCategory(category.name)">
                                    Change
                                </jet-button>
                            </div>
                            <jet-button v-else @click="changeCategory(category.name)">
                                Change
                            </jet-button>
                        </div>
                    </div>
                </div>
            </template>
        </jet-action-section>
    </div>
</template>

<script>
    import { defineComponent } from 'vue'
    import JetActionSection from '@/Jetstream/ActionSection.vue'
    import JetConfirmationModal from '@/Jetstream/ConfirmationModal.vue'
    import JetButton from '@/Jetstream/Button.vue'
    import JetSecondaryButton from '@/Jetstream/SecondaryButton.vue'
    import JetDangerButton from '@/Jetstream/DangerButton.vue'
    import JetSectionBorder from '@/Jetstream/SectionBorder.vue'
    import JetInput from '@/Jetstream/Input.vue'
    import JetInputError from '@/Jetstream/InputError.vue'
    import JetLabel from '@/Jetstream/Label.vue'

    export default defineComponent({
        components: {
            JetActionSection,
            JetConfirmationModal,
            JetButton,
            JetSecondaryButton,
            JetDangerButton,
            JetSectionBorder,
            JetInput,
            JetInputError,
            JetLabel
        },

        props: ['skey', 'categories', 'currentCategory'],

        data() {
            return {
                form: this.$inertia.form({
                    user_id: this.$page.props.user.id,
                    key_id: this.skey.id,
                    name: ''
                })
            }
        },

        methods: {
            changeCategory($catName) {
                this.form.name = $catName
                this.updateCategory()
            },

            newCategory($catName) {
                this.form.name = $catName
                this.createCategory()
            },

            removeCategory() {
                this.form.delete(route('key.removeCategory', this.skey), {
                    preserveScroll: true,
                    preserveState: true
                });
                this.form.name = ''
            },

            updateCategory() {
                this.form.post(route('key.updateCategory'), {
                    errorBag: 'updateCategory',
                    preserveScroll: true,
                    preserveState: true
                });
                this.form.name = ''
            },

            createCategory() {
                this.form.post(route('key.createCategory'), {
                    errorBag: 'createCategory',
                    preserveScroll: true,
                    preserveState: true
                });
                this.form.name = ''
            }
        },
    })
</script>