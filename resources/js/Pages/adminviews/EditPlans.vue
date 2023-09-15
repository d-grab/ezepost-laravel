<template>
    <div>
        <Head title="Pricing" />

        <!-- Personal Plans Section -->
        <section class="container mx-auto text-center py-8">
            <h2 class="text-3xl font-semibold mb-6 text-black">Personal Plans</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div v-for="plan in personalPlans" :key="plan.id" class="plan-card">
                    <h3 class="text-2xl font-semibold mb-4 text-center text-white">{{ plan.name }}</h3>
                    <p class="text-lg text-white mb-6 text-center">{{ plan.description }}</p>
                    <ul class="text-left text-white mb-6 space-y-4 pb-5">
                        <li
                            v-for="(option, index) in plan.options"
                            :key="index"
                            class="flex items-center"
                        >
                            <svg
                                class="w-5 h-5 text-green-500 mr-2"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                                aria-hidden="true"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M6.293 9.293a1 1 0 011.414 0L10 11.586l2.293-2.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                            {{ option }}
                        </li>
                    </ul>
                    <p class="text-4xl font-extrabold mb-6 text-center text-white">
                        ${{ plan.price }}
                    </p>

                    <button @click="editPlan(plan.id)" class="bg-blue-600 hover:bg-blue-800 text-white px-6 py-2 rounded text-lg mb-6 transition duration-300 ease-in-out">
                        Edit Plan
                    </button>
                </div>
            </div>
        </section>

        <!-- Business Plans Section -->
        <section class="container mx-auto text-center py-8">
            <h2 class="text-3xl font-semibold mb-6 text-black">Business Plans</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div v-for="plan in businessPlans" :key="plan.id" class="plan-card">
                    <h3 class="text-2xl font-semibold mb-4 text-center text-white">{{ plan.name }}</h3>
                    <p class="text-lg text-white mb-6 text-center">{{ plan.description }}</p>
                    <ul class="text-left text-white mb-6 space-y-4 pb-5">
                        <li
                            v-for="(option, index) in plan.options"
                            :key="index"
                            class="flex items-center"
                        >
                            <svg
                                class="w-5 h-5 text-green-500 mr-2"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                                aria-hidden="true"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M6.293 9.293a1 1 0 011.414 0L10 11.586l2.293-2.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                            {{ option }}
                        </li>
                    </ul>
                    <p class="text-4xl font-extrabold mb-6 text-center text-white">
                        ${{ plan.price }}
                    </p>

                    <button @click="editPlan(plan.id)" class="bg-blue-600 hover:bg-blue-800 text-white px-6 py-2 rounded text-lg mb-6 transition duration-300 ease-in-out">
                        Edit Plan
                    </button>
                </div>
            </div>
        </section>
    </div>
</template>

<script>
import { Head } from "@inertiajs/vue3";
import Layout from "../../shared/Layout.vue";

export default {
    components: { Head },

    props: {
        personalPlans: Array,
        businessPlans: Array,
    },

    methods: {
        editPlan(planId) {
            // Navigate to the plan's edit page
            this.$inertia.visit(`/admin/plans/edit/${planId}`);
        }
    },
    layout: Layout,
};
</script>

<style scoped>
.plan-card {
    padding: 3rem;
    margin-top: 3rem;
    background: #1a202c;
    border-radius: 1rem;
    transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
    color: white;
}

.plan-card:hover {
    transform: translateY(-15px) scale(1.02);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
}
</style>