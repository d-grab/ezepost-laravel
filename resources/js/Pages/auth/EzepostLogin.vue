<template>
    <Head title="Login" />
    <guest-nav></guest-nav>

    <div
        class="flex items-center justify-center p-6 min-h-screen bg-gradient-to-b from-blue-800 via-blue-900 to-gray-900"
    >
        <div class="w-full max-w-md">
              <!-- Displaying general error messages here -->
        <div v-if="form.error" class="bg-red-600 text-white p-3 rounded mb-6">
            {{ form.error }}
        </div>
            
            <form
                class="mt-8 bg-white rounded-lg shadow-xl overflow-hidden"
                @submit.prevent="login"
            >
                <div class="px-10 py-12">
                    <h1 class="text-center text-3xl font-bold mb-4">
                        Welcome Back!
                    </h1>
                    
                    <div
                        class="mt-6 mx-auto w-24 border-b-2 border-rgb-primary"
                    />
                    
        
                    <text-input
                        v-model="form.username"
                        
                        class="mt-10 text-input"
                        label="Username"
                        placeholder="Enter your username"
                        autofocus
                        autocapitalize="off"
                        
                    />
                    
                    <text-input
                        v-model="form.password"
                        
                        class="mt-6 text-input"
                        label="Password"
                        type="password"
                        placeholder="Enter your password"
                    />
                    <label
                        class="flex items-center mt-6 select-none"
                        for="remember"
                    >
                        <input
                            id="remember"
                            v-model="form.remember"
                            class="mr-1"
                            type="checkbox"
                        />
                        <span class="text-sm">Remember Me</span>
                    </label>
                    <p class="mt-6">
                        Dont have an account yet ?
                        <a
                            href="/signup"
                            class="text-decoration-line: underline font-bold"
                        >
                            Register Now !
                        </a>
                    </p>
                </div>

                <div
                    class="flex px-10 py-4 bg-gray-100 items-center border-t border-gray-100"
                >
                    <loading-button
                        :loading="form.processing"
                        class="btn-indigo loading-button"
                        type="submit"
                    >
                        Login
                    </loading-button>
                    <Link
                        href="/forget-password"
                        class="ml-4 text-gray-500 hover:text-indigo-600"
                        >Forgot your password?</Link
                    >
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import { Head, Link } from "@inertiajs/vue3";
import Logo from "../../shared/Logo.vue";
import TextInput from "../../shared/TextInput.vue";
import LoadingButton from "../../shared/LoadingButton.vue";
import GuestNav from "../../shared/GuestNav.vue";

export default {
    components: {
        Head,
        LoadingButton,
        Logo,
        TextInput,
        GuestNav,
        Link,
    },
    props: {
        error: String,
    
    },
    
    data() {
        return {
            form: this.$inertia.form({
                username: "",
                password: "",
                remember: false,
                error: "",
            }),
        };
    },
    
    methods: {
        
        login() {
            console.log("Form data before submit:", this.form);
            this.form.post("/login", {
                onError: (errors) => {
                    // Set a general error message if received from the server
                    if (errors.error) {
                        this.form.error = errors.error;
                    }
                }
            })},
    },
};
</script>
