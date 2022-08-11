<template>
    <app-layout title="Disk Logs">
        <template #header>
            <h2 class="inline text-xl font-semibold leading-tight text-gray-800">
                Disk Log Analytics
            </h2>

            <custom-nav-link :href="route('log.index')" class="ml-3 text-indigo-500 hover:text-indigo-900">(Back to Logs)</custom-nav-link>
        </template>

        <div>
            <div class="py-10 mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200 sm:px-20">
                        <LineChart ref="recentChart" :chartData="recentData" :options="recentOptions" class="p-2 max-h-56" />
                        <div class="grid p-2 sm:grid-cols-2">
                            <DoughnutChart ref="dowChart" :chartData="dowData" :options="dowOptions" class="col-span-1 max-h-56" />
                            <DoughnutChart ref="typeChart" :chartData="typeData" :options="typeOptions" class="col-span-1 max-h-56" />
                        </div>
                        <BarChart ref="diskChart" :chartData="diskData" :options="diskOptions" class="p-2 max-h-56" />
                        <BarChart ref="userChart" :chartData="userData" :options="userOptions" class="p-2 max-h-56" />
                        <BarChart ref="tokenChart" :chartData="tokenData" :options="tokenOptions" class="p-2 max-h-56" />
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
    import { DoughnutChart, BarChart, LineChart } from 'vue-chart-3';
    import { computed, defineComponent, ref } from 'vue';
    import { Chart, registerables } from "chart.js";
    import AppLayout from '@/Layouts/AppLayout.vue'
    import CustomNavLink from '@/BuildingBlocks/NavLink'

    Chart.register(...registerables);

    export default defineComponent({
        props: [
            'recent_activity',
            'dow_activity',
            'activity_type',
            'disk_activity',
            'user_activity',
            'token_activity'
        ],

        components: {
            AppLayout,
            BarChart,
            CustomNavLink,
            DoughnutChart,
            LineChart,
        },

        computed: {
            recentData() {
                return {
                    labels: this.recent_activity.labels,
                    datasets: [
                        {
                            fill: true,
                            backgroundColor: "rgb(99, 102, 241)",
                            data: this.recent_activity.data
                        }
                    ]
                }
            },
            recentOptions() {
                return {
                    plugins: {
                        title: {
                            display: true,
                            text: "Last Thirty Days of Activity"
                        },
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        x: {
                            display: true,
                            title: {
                                display: true,
                                text: 'Date'
                            }
                        },
                        y: {
                            display: true,
                            title: {
                                display: true,
                                text: 'Amount of Activity'
                            }
                        }
                    }
                }
            },

            dowData() {
                return {
                    labels: this.dow_activity.labels,
                    datasets: [
                        {
                            data: this.dow_activity.data,
                            backgroundColor: this.dow_activity.colors
                        }
                    ]
                }
            },
            dowOptions() {
                return {
                    plugins: {
                        title: {
                            display: true,
                            text: "Activity per Day of the Week"
                        }
                    },
                }
            },

            typeData() {
                return {
                    labels: this.activity_type.labels,
                    datasets: [
                        {
                            data: this.activity_type.data,
                            backgroundColor: this.activity_type.colors
                        }
                    ]
                }
            },
            typeOptions() {
                return {
                    plugins: {
                        title: {
                            display: true,
                            text: "Amount per Activity Type"
                        }
                    },
                }
            },

            diskData() {
                return {
                    labels: this.disk_activity.labels,
                    datasets: [
                        {
                            backgroundColor: "rgb(99, 102, 241)",
                            data: this.disk_activity.data
                        }
                    ]
                }
            },
            diskOptions() {
                return {
                    plugins: {
                        title: {
                            display: true,
                            text: "Disk Activity"
                        },
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        x: {
                            display: true,
                            title: {
                                display: true,
                                text: 'Disk'
                            }
                        },
                        y: {
                            display: true,
                            title: {
                                display: true,
                                text: 'Amount of Activity'
                            }
                        }
                    }
                }
            },

            userData() {
                return {
                    labels: this.user_activity.labels,
                    datasets: [
                        {
                            backgroundColor: "rgb(99, 102, 241)",
                            data: this.user_activity.data
                        }
                    ]
                }
            },
            userOptions() {
                return {
                    plugins: {
                        title: {
                            display: true,
                            text: "User Activity"
                        },
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        x: {
                            display: true,
                            title: {
                                display: true,
                                text: 'User'
                            }
                        },
                        y: {
                            display: true,
                            title: {
                                display: true,
                                text: 'Amount of Activity'
                            }
                        }
                    }
                }
            },

            tokenData() {
                return {
                    labels: this.token_activity.labels,
                    datasets: [
                        {
                            backgroundColor: "rgb(99, 102, 241)",
                            data: this.token_activity.data
                        }
                    ]
                }
            },
            tokenOptions() {
                return {
                    plugins: {
                        title: {
                            display: true,
                            text: "API Activity"
                        },
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        x: {
                            display: true,
                            title: {
                                display: true,
                                text: 'Token'
                            }
                        },
                        y: {
                            display: true,
                            title: {
                                display: true,
                                text: 'Amount of Activity'
                            }
                        }
                    }
                }
            }
        },

        setup() {
            const recentChart = ref();
            const dowChart = ref();
            const typeChart = ref();
            const diskChart = ref();
            const userChart = ref();
            const tokenChart = ref();

            return { 
                recentChart,
                dowChart,
                typeChart,
                diskChart,
                userChart,
                tokenChart 
            }
        }
    })
</script>