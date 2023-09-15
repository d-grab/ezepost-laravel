<template>
    <div>
        <Head title="Dashboard" ><title class="p-3">Dashboard</title></Head>
        <h1 class="mb-10 text-4xl text-center font-semibold tracking-wider text-violet-950 bg-gradient-to-r from-blue-400 via-purple-500 to-pink-500 p-2 rounded-lg shadow-lg mt-5">Dashboard</h1>
        <div class="flex justify-center mt-8 space-x-4">
            
            <!-- Total Customers Card -->
            <div class="bg-rgb-primary p-6 rounded-lg shadow-lg text-center">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-12 w-12 text-white mx-auto mb-4"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 13l4 4m0 0l4-4m-4 4V3"
                    ></path>
                </svg>
                <h3 class="text-xl font-semibold text-white mb-2">
                    Total Customers
                </h3>
                <p class="text-gray-300">
                    Total Customers Registered In Our Application
                </p>
                <button
                    class="mt-4 px-6 py-2 bg-white text-rgb-primary font-semibold rounded-lg cursor-default"
                >
                    {{ customerCount }} Customers
                </button>
            </div>

            <!-- Total Packages Card -->
            <div
                class="bg-violet-800 ml-4 p-6 rounded-lg shadow-lg text-center"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-12 w-12 text-white mx-auto mb-4"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                    ></path>
                </svg>
                <h3 class="text-xl font-semibold text-white mb-2">
                    Total Packages
                </h3>
                <p class="text-gray-300">
                    Total Packages Shipped Through Our Application
                </p>
                <button
                    class="mt-4 px-6 py-2 bg-white text-rgb-secondary font-semibold rounded-lg cursor-default"
                >
                    {{ totalPackages }} Packages
                </button>
            </div>
        
        
            <div
                class="bg-fuchsia-800 ml-4 p-6 rounded-lg shadow-lg text-center"
            >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5C7.26 4.5 2.98 7.58 1 12c1.98 4.42 6.26 7.5 11 7.5s9.02-3.08 11-7.5c-1.98-4.42-6.26-7.5-11-7.5zM12 17a5 5 0 100-10 5 5 0 000 10z"></path>
    </svg>
                <h3 class="text-xl font-semibold text-white mb-2">
                    Viewed Packages
                </h3>
                <p class="text-gray-300">
                    Total Packages Viewed Through Our Application
                </p>
                <button
                    class="mt-4 px-6 py-2 bg-white text-rgb-secondary font-semibold rounded-lg cursor-default"
                >
                    {{ viewedOnce }} Packages
                </button>
            </div>
            
        
    </div>
    <div class="mt-6">
        <h1 class="mb-10 text-4xl text-center font-semibold tracking-wider text-violet-950 bg-gradient-to-r from-blue-400 via-purple-500 to-pink-500 p-2 rounded-lg shadow-lg mt-8">Active Users</h1></div>
        <div class="flex justify-center mt-8 space-x-4">
            <!-- Top-ups Users Card -->
            <div class="bg-rgb-primary p-6 rounded-lg shadow-lg text-center mr-10">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                </svg>
                <h3 class="text-xl font-semibold text-white mb-2">Top-ups Users</h3>
                <p class="text-gray-300">Users that have topped-up in the application</p>
                <button
                    class="mt-4 px-6 py-2 bg-white text-rgb-secondary font-semibold rounded-lg cursor-default"
                >
                    {{ userTopUp }} Customers
                </button>
            </div>

            <!-- Subscription-Users Card -->
            <div class="bg-fuchsia-800 p-6 rounded-lg shadow-lg text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16m-7 6h7"></path>
                </svg>
                <h3 class="text-xl font-semibold text-white mb-2">Subscription-Users</h3>
                <p class="text-gray-300">Users with active subscriptions in the application</p>
                <button
                    class="mt-4 px-6 py-2 bg-white text-rgb-secondary font-semibold rounded-lg cursor-default"
                >
                    {{ subscribed_customers }} Customers
                </button>
            </div>
            </div>
            <h1 class="mb-10 text-4xl text-center font-semibold tracking-wider text-violet-950 bg-gradient-to-r from-blue-400 via-purple-500 to-pink-500 p-2 rounded-lg shadow-lg mt-8">Type of Users</h1>
<div class="flex justify-center mt-8 space-x-1 mr-8">
    <!-- Top-ups Users Card -->
    <div class="bg-violet-800 p-6 rounded-lg shadow-lg text-center mr-2">
        <!-- Wallet icon for top-ups -->
        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-blue-300 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6h18M3 10h18m-5 4l5.586.293a2 2 0 002.414-2.414L21 4a2 2 0 00-2-2H5a2 2 0 00-2 2v16a2 2 0 002 2h14a2 2 0 002-2V12.414a2 2 0 00-.586-1.414z"></path>
        </svg>
        <h3 class="text-xl font-semibold text-white mb-2">Individual Users</h3>
        <p class="text-gray-300">Users wth active personal plans</p>
        <button class="mt-4 px-6 py-2 bg-white text-rgb-secondary font-semibold rounded-lg cursor-default">
            {{ individualUsers }} Customers
        </button>
    </div>

    <!-- Subscription-Users Card -->
    <div class="bg-rgb-primary p-6 rounded-lg shadow-lg text-center">
        <!-- Icon for subscription, using a simple user group -->
        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-purple-300 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M17 20h5v2a3 3 0 01-3 3H5a3 3 0 01-3-3v-2h5m0 0V4m0 16a6.978 6.978 0 016-1.528A6.978 6.978 0 0117 20z"></path>
        </svg>
        <h3 class="text-xl font-semibold text-white mb-2">Organization Users</h3>
        <p class="text-gray-300">Users with active business plans</p>
        <button class="mt-4 px-6 py-2 bg-white text-rgb-secondary font-semibold rounded-lg cursor-default">
            {{ organisationUsers }} Customers
        </button>
    </div>
</div>

    </div>
</template>

<script>
import { Head } from "@inertiajs/vue3";
import Layout from "../../shared/Layout.vue";

export default {
    components: {
        Head,
    },
    props: {
        customerCount: Number,
        totalPackages: Number,
        viewedOnce: Number, // Add this line for the recentCustomers prop
        subscribed_customers: Number,
        userTopUp: Number,
        individualUsers: Number,
        organisationUsers: Number,
    },
    layout: Layout,
};
</script>
