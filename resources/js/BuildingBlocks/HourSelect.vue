<template>
    <select class="block w-full py-2 pl-3 pr-10 mt-1 text-base border-gray-300 rounded-md focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 focus:z-10 sm:text-sm" @input="$emit('update:modelValue', $event.target.value)">
        <option v-for="hour in hours" :key="hour.trueValue" :selected="hour.trueValue == modelValue" :value="hour.trueValue">{{ hour.ampm }}</option>
    </select>
</template>

<script>
export default {
    data() {
        return {
            hours: [
                { trueValue: 0, ampm: '12am' },
                { trueValue: 1, ampm: '1am' },
                { trueValue: 2, ampm: '2am' },
                { trueValue: 3, ampm: '3am' },
                { trueValue: 4, ampm: '4am' },
                { trueValue: 5, ampm: '5am' },
                { trueValue: 6, ampm: '6am' },
                { trueValue: 7, ampm: '7am' },
                { trueValue: 8, ampm: '8am' },
                { trueValue: 9, ampm: '9am' },
                { trueValue: 10, ampm: '10am' },
                { trueValue: 11, ampm: '11am' },
                { trueValue: 12, ampm: '12pm' },
                { trueValue: 13, ampm: '1pm' },
                { trueValue: 14, ampm: '2pm' },
                { trueValue: 15, ampm: '3pm' },
                { trueValue: 16, ampm: '4pm' },
                { trueValue: 17, ampm: '5pm' },
                { trueValue: 18, ampm: '6pm' },
                { trueValue: 19, ampm: '7pm' },
                { trueValue: 20, ampm: '8pm' },
                { trueValue: 21, ampm: '9pm' },
                { trueValue: 22, ampm: '10pm' },
                { trueValue: 23, ampm: '11pm' },
            ],
        }
    },

    props: [
        'modelValue',
    ],

    emits: ['update:modelValue'],

    methods: {
        focus() {
            this.$refs.input.focus()
        }
    }
}
</script>

