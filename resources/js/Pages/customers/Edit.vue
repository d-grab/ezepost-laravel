<template>
    <div>
        <Head title="Customers" />
        <h1 class="mb-8 text-3xl font-bold">Customers</h1>
        
        <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
            <form
                class="p-2 bg-white rounded-md shadow overflow-x-auto"
                @submit.prevent="updateCustomer"
            >
                <div class="flex flex-wrap -mb-8 -mr-6 p-8">
                    <text-input
                        v-model="editedCustomer.name"
                        :error="errors.name"
                        class="pb-8 pr-6 w-full lg:w-1/2"
                        label="Name"
                    />
                    <text-input
                        v-model="editedCustomer.email"
                        :error="errors.email"
                        class="pb-8 pr-6 w-full lg:w-1/2"
                        label="Email"
                    />
                    <text-input
                        v-model="editedCustomer.phone"
                        :error="errors.phone"
                        class="pb-8 pr-6 w-full lg:w-1/2"
                        label="Phone"
                    />
                </div>
                <div
                    class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100"
                >
                    <loading-button
                        :loading="editedCustomer.processing"
                        class="btn-indigo ml-auto"
                        type="submit"
                        >Update Customer</loading-button
                    >
                      <!-- New Block User Button -->
                      <button 
    @click="blockCustomer(editedCustomer.id)"
    class="btn-danger-manual ml-auto"
>
    Block Customer
</button>
<button 
    @click="unblockCustomer(editedCustomer.id)" 
    class="btn-info-manual ml-auto"
>
    Unblock Customer
</button>
                </div>
                
            </form>
            
        </div>

        
        <h2 class="mt-12 text-2xl font-bold p-2">Packages Transferred</h2>
        <search-filter
                v-model="form.search"
                class="mr-4 w-full max-w-md"
                @reset="reset"
            >
            </search-filter>
        <div class="mt-6 bg-white rounded shadow overflow-x-auto">
            <table class="w-full whitespace-nowrap">
                <tr class="text-left font-bold">
                    <th class="pb-4 pt-6 px-6">Sender Display Name</th>
                    <th class="pb-4 pt-6 px-6">File Name</th>
                    <th class="pb-4 pt-6 px-6">Receiver Display Name</th>
                    <th class="pb-4 pt-6 px-6">Package mpID</th>

                    <th class="pb-4 pt-6 px-6">View Receipt </th>
                    <th class="pb-4 pt-6 px-6">Download Receipt</th>
                </tr>
              
                <tr 
                    v-for="packageTransfered in packages"
                    :key="packageTransfered.id"
                    class="hover:bg-gray-100 focus-within:bg-gray-100"
                >
                    <td class="border-t">
                        <Link
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ packageTransfered.s_name }}
                        </Link>
                    </td>
                    <td class="border-t">
                        <Link class="flex items-center px-6 py-4" tabindex="-1">
                            {{ packageTransfered.fileName }}
                        </Link>
                    </td>
                    <td class="border-t">
                        <Link class="flex items-center px-6 py-4" tabindex="-1">
                            {{ packageTransfered.r_name }}
                        </Link>
                    </td>
                    <td class="border-t">
                        <Link class="flex items-center px-6 py-4" tabindex="-1">
                            {{ packageTransfered.mpID }}
                        </Link>
                    </td>

                    <a
    v-bind:href="'/vepost-tracking/view-pdf/' + packageTransfered.id"
    target="_blank"
    class="flex items-center px-6 py-4 ml-8"
>
    <!-- new SVG for view -->
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17a5 5 0 1 0 0-10 5 5 0 0 0 0 10zm0 0 0 0 0 0zm-2-4h4"></path></svg>
</a>
                    <td class="border-t">
    <a
        v-bind:href="'/vepost-tracking/pdf/' + packageTransfered.id"
        class="flex items-center px-6 py-4 ml-12"
        download
    >
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 15v4c0 1.1.9 2 2 2h14a2 2 0 0 0 2-2v-4M17 9l-5 5-5-5M12 12.8V2.5"/></svg>
    </a>
</td>
                </tr>
                <tr v-if="packages.length === 0">
                    <td class="px-6 py-4 border-t" colspan="4">
                        No Packages found.
                    </td>
                </tr>
            </table>
        </div>
    </div>
</template>

<script>
import { Head, Link } from "@inertiajs/vue3";
import LoadingButton from "../../shared/LoadingButton.vue";
import Layout from "../../shared/Layout.vue";
import TextInput from "../../shared/TextInput.vue";
import throttle from "lodash/throttle";
import SearchFilter from "../../shared/SearchFilter.vue";
import pickBy from "lodash/pickBy";

export default {
    components: {
        Head,
        Link,
        LoadingButton,
        TextInput,
        SearchFilter,
    },
    props: {
        customer: Object,
        errors: Object,
        packages: Object,
    },
    watch: {
        form: {
            deep: true,
            handler: throttle(function () {
                this.$inertia.get(`/customers/${this.customer.id}/edit`, pickBy(this.form), {
    preserveState: true,
});
            }, 150),
        },
    },
    layout: Layout,
    data() {
        return {
            editedCustomer: { ...this.customer },
            successMessage: "",
            errorMessage: "",
            form: {
                search: "",
            },
        };
    },
    methods: {
        async updateCustomer() {
            try {
                await this.$inertia.put(
                    `/customers/${this.editedCustomer.id}/update`,
                    this.editedCustomer
                );
                this.successMessage = "Customer updated successfully.";
                this.errorMessage = ""; // Clear previous error message
            } catch (error) {
                this.successMessage = ""; // Clear previous success message
                this.errorMessage =
                    "An error occurred while updating the customer.";
            }
        },
        reset() {
    this.form.search = "";
},

        async blockCustomer(id) {
            try {
                await this.$inertia.post(`/block/customer/${id}`); // Send block request to server
                alert('The user has been successfully blocked.'); // Success Message
            } catch (error) {
                alert('An error occurred while blocking the user.'); // Error Message
            }
        },

        async unblockCustomer(id) {
            try {
                await this.$inertia.post(`/unblock/customer/${id}`);
                alert('The user has been successfully unblocked.');
    }       catch (error) {
                alert('An error occurred while unblocking the user.');
    }
},

        downloadPDF(id) {
            console.log(id);
            this.$inertia.get(`/vepost-tracking/pdf/${id}`);
        },
    },
};
</script>
