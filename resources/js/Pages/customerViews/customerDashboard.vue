<template>
    <div>
        <div class="px-4 py-6">
        <Head title="EzePost Dashboard"><title>{{ username }}</title></Head>
        <h1 class="mb-8 text-2xl font-bold text-center">Welcome <span class="text-blue-800">{{ username }}</span> to Your EzePost Dashboard</h1>
        
        </div>
        <!-- Check if flash.success message exists and display it -->
        <div class="flex justify-center mt-20 space-x-10 pt-5">

            <!-- Send Packages Card -->
            <div class="flex flex-col w-72 bg-indigo-950 p-6 rounded-lg shadow-lg relative">
                <div class="flex-1 mb-4">
                    <svg class="w-8 h-8 text-white mx-auto " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 14">
    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m12 7 3-3-3-3m0 12H5.5a4.5 4.5 0 1 1 0-9H14"/>
                    </svg>
        </div>
                <h3 class="text-xl font-semibold text-white mb-2">Send Packages</h3>
                <p class="text-gray-300">Total Send Packages</p>
                <button class="mt-4 px-6 py-2 bg-white text-rgb-primary font-semibold rounded-lg cursor-default">{{ sendPackages }} Packages</button>
            </div>

            <!-- Received Packages Card -->
            <div class="flex flex-col w-72 bg-indigo-950 p-6 rounded-lg shadow-lg relative">
                <div class="flex-1 mb-4">
                    <svg class="w-8 h-8 text-white mx-auto" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 14">
    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7 1 4l3-3m0 12h6.5a4.5 4.5 0 1 0 0-9H2"/>
  </svg>
        </div>
                <h3 class="text-xl font-semibold text-white mb-2">Received Packages</h3>
                <p class="text-gray-300">Total Received Packages</p>
                <button class="mt-4 px-6 py-2 bg-white text-rgb-secondary font-semibold rounded-lg cursor-default">{{ receivedPackages }} Packages</button>
            </div>
        </div>

        <div class="flex justify-center mt-8 space-x-10">
            <!-- Viewed Packages Card -->
            <div class="flex flex-col w-72 bg-rgb-primary p-6 rounded-lg shadow-lg relative">
                <div class="flex-1 mb-4">
                <svg class="w-8 h-8 text-white mx-auto" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M15.077.019a4.658 4.658 0 0 0-4.083 4.714V7H2a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-1.006V4.68a2.624 2.624 0 0 1 2.271-2.67 2.5 2.5 0 0 1 2.729 2.49V8a1 1 0 0 0 2 0V4.5A4.505 4.505 0 0 0 15.077.019ZM9 15.167a1 1 0 1 1-2 0v-3a1 1 0 1 1 2 0v3Z"/>
                </svg>
                </div>
                <h3 class="text-xl font-semibold text-white mb-2">Viewed Packages</h3>
                <p class="text-gray-300">Total Packages Viewed</p>
                <button class="mt-4 px-6 py-2 bg-white text-rgb-secondary font-semibold rounded-lg cursor-default">{{ viewedOnce }} Packages</button>
            </div>

            <!-- Total Packages Card -->
            <div class="flex flex-col w-72 bg-rgb-primary p-6 rounded-lg shadow-lg relative">
                <div class="flex-1 mb-4">
                <svg class="w-8 h-8 text-white mx-auto" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
    <path d="m1.56 6.245 8 3.924a1 1 0 0 0 .88 0l8-3.924a1 1 0 0 0 0-1.8l-8-3.925a1 1 0 0 0-.88 0l-8 3.925a1 1 0 0 0 0 1.8Z"/>
    <path d="M18 8.376a1 1 0 0 0-1 1v.163l-7 3.434-7-3.434v-.163a1 1 0 0 0-2 0v.786a1 1 0 0 0 .56.9l8 3.925a1 1 0 0 0 .88 0l8-3.925a1 1 0 0 0 .56-.9v-.786a1 1 0 0 0-1-1Z"/>
    <path d="M17.993 13.191a1 1 0 0 0-1 1v.163l-7 3.435-7-3.435v-.163a1 1 0 1 0-2 0v.787a1 1 0 0 0 .56.9l8 3.925a1 1 0 0 0 .88 0l8-3.925a1 1 0 0 0 .56-.9v-.787a1 1 0 0 0-1-1Z"/>
  </svg>
</div>
                <h3 class="text-xl font-semibold text-white mb-2">All Packages</h3>
                <p class="text-gray-300">All Packages Sent and Received</p>
                <button class="mt-4 px-6 py-2 bg-white text-rgb-secondary font-semibold rounded-lg cursor-default">{{ totalPackages }} Packages</button>
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
        username: String,
        sendPackages: Number,
        receivedPackages: Number,
        totalPackages: Number,
        viewedOnce: Number,
        flash: Object, // This is required to receive the flash prop
        controlstring: String,
    },
    layout: Layout,
    
        
    
};
</script>