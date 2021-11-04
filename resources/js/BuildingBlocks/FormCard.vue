<template>
     <form @submit.prevent="$emit('submitted')">
        <div class="flex items-center justify-start px-4 py-3 text-lg font-bold text-left shadow bg-gray-50 sm:px-6 sm:rounded-tl-md sm:rounded-tr-md" v-if="hasTitle">
            <slot name="title"></slot>
        </div>

        <div class="px-4 py-5 bg-white shadow sm:p-6"
            :class="formClasses">
            <div class="space-y-2">
                <slot name="form"></slot>
            </div>
        </div>

        <div class="flex items-center justify-end px-4 py-3 text-right shadow bg-gray-50 sm:px-6 sm:rounded-bl-md sm:rounded-br-md" v-if="hasActions">
            <slot name="actions"></slot>
        </div>
    </form>
</template>

<script>
    export default {
        emits: ['submitted'],
        
        computed: {
            hasActions() {
                return !! this.$slots.actions
            },

            hasTitle() {
                return !! this.$slots.title
            },
            
            formClasses() {
                let classes = ''

                if (!this.hasActions && !this.hasTitle) {
                    classes = 'sm:rounded-md'
                } else {
                    if (!this.hasTitle) {
                        classes += 'sm:rounded-tl-md sm:rounded-tr-md'
                    } else if (!this.hasActions) {
                        classes += 'sm:rounded-bl-md sm:rounded-br-md'
                    }
                }

                return classes
            }
        }
    }
</script>
