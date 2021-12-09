<template>
    <div class="mt-48 flex justify-center">
        <div class="w-full max-w-md">
            <form @submit.prevent="submit">
                <div class="bg-green-500 shadow-lg rounded px-12 pt-6 pb-8 mb-4">
                    <!-- @csrf -->
                    <div
                        class="text-2xl flex justify-center border-b-2 py-2 mb-4"
                    >
                        Login
                    </div>
                    <div class="mb-4">
                        <label
                            class="block text-sm font-normal mb-2"
                            for="username"
                        >
                            Email
                        </label>
                        <input
                            class="shadow text-green-500 appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline"
                            name="email"
                            type="email"
                            v-model="email"
                            required
                            autofocus
                            placeholder="Email"
                        />
                        <span v-if="error" class="flex items-center font-medium tracking-wide text-red-500 text-xs mt-1 ml-1">
                            Wrong email/password
		                </span>

                    </div>
                    <div class="mb-6">
                        <label
                            class="block text-sm font-normal mb-2"
                            for="password"
                        >
                            Password
                        </label>
                        <input
                            class="shadow text-green-500 appearance-none border rounded w-full py-2 px-3 mb-3 leading-tight focus:outline-none focus:shadow-outline"
                            type="password"
                            placeholder="Password"
                            name="password"
                            v-model="password"
                            required
                            autocomplete="current-password"
                        />
                    </div>
                    <div class="flex items-center justify-between">
                        <button
                            type="submit"
                            class="px-4 py-2 rounded text-white inline-block shadow-lg bg-gray-500 hover:bg-gray-600 focus:bg-gray-700">Sign In
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            email: "",
            password: "",
            error: false
        }
    },
    methods: {
        submit() {
            axios.post('/api/login', {
                email: this.email,
                password: this.password,
            }).then(response => {
                console.log("logg");
                localStorage.setItem('token', response.data.access_token);
                this.$router.push("/songs");
                // location.reload();
            }).catch(error => {
                if (error.response.status === 401) {
                    this.error = true;
                    this.password = "";
                }
            });
        }
    }
}
</script>
