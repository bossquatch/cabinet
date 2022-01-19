<template>
    <div class="flow-root">
        <ul role="list" class="-mb-8">
            <li v-for="(log, logIdx) in logs" :key="log.id">
                <div class="relative pb-8">
                    <span v-if="(logIdx !== logs.length - 1)" class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true" />
                    <div class="relative flex space-x-3">
                        <div>
                            <span :class="[('bg-' + log.color + '-200'), ('text-' + log.color + '-700'), 'h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white']">
                                <component :is="log.icon" class="w-5 h-5" aria-hidden="true" />
                            </span>
                        </div>
                        <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                            <div>
                                <p class="text-sm text-gray-500">
                                    <span class="font-semibold text-gray-800" v-text="log.type.toUpperCase()" /> 
                                    &nbsp;
                                    <span class="text-green-500" v-text="log.file" />
                                    &nbsp;
                                    on
                                    &nbsp;
                                    <span class="text-indigo-500" v-text="log.disk" />
                                    &nbsp;
                                    by
                                    &nbsp;
                                    <span class="text-indigo-500" v-text="log.user" />
                                </p>
                            </div>
                            <div class="text-sm text-right text-gray-500 whitespace-nowrap">
                                <time :datetime="log.datetime">{{ log.datetime }}</time>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</template>

<script>
    import { defineComponent } from 'vue'
    import Download from '@/HeroIcons/Download.vue'
    import Duplicate from '@/HeroIcons/Duplicate.vue'
    import Trash from '@/HeroIcons/Trash.vue'
    import Upload from '@/HeroIcons/Upload.vue'
    import X from '@/HeroIcons/X.vue'

    export default defineComponent({
        props: ['logs'],

        components: {
            Download,
            Duplicate,
            Trash,
            Upload,
            X,
        },
    })
</script>