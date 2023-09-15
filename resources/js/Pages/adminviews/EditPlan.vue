<template>
    <div>
        <Head title="Edit Plan" />
        <div class="container mx-auto py-4">
            <h1 class="text-3xl font-semibold mb-6 text-white text-center p-4 rounded shadow-md bg-gradient-to-r from-blue-500 to-purple-600">
        Edit Plan: {{ plan.name }}
    </h1>

            <!-- Form for editing -->
            <form @submit.prevent="submit" class="w-full max-w-xl mx-auto bg-white p-6 rounded shadow-md">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="plan-name">Edit Name:</label>
                    <input v-model="plan.name" type="text" id="plan-name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"/>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="plan-price">Edit Price:</label>
                    <input v-model="plan.price" type="number" step=".01" id="plan-price" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"/>
                    
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="plan-description">Edit Description:</label>
                    <textarea v-model="plan.description" id="plan-description" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="plan-options">Edit Options:</label>
                    <textarea v-model="plan.options" id="plan-options" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
                </div>

                <div class="mt-6">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import { Head } from "@inertiajs/vue3";
import Layout from "../../shared/Layout.vue";

export default {
    components: {
        Head,
        Layout
    },
    validations: {
    
  },
    props: ['plan'],
    methods: {
        submit() {
            this.$inertia.post(`/admin/plans/update/${this.plan.id}`, this.plan);
            // await this.$inertia.post(`/admin/plans/update/${this.plan.id}`, this.plan);
        }
    },
    layout: Layout
}
</script>

<style scoped>
/* You can have any scoped styles specific to this component here */
</style>
